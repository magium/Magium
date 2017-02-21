<?php

namespace Magium\TestCase;

use Interop\Container\ContainerInterface;
use Magium\AbstractTestCase;
use Magium\InvalidConfigurationException;
use Magium\TestCaseConfiguration;
use Magium\Util\Api\Clairvoyant\Clairvoyant;
use Magium\Util\Api\Request;
use Magium\Util\Configuration\ClassConfigurationReader;
use Magium\Util\Configuration\ConfigurationCollector\DefaultPropertyCollector;
use Magium\Util\Configuration\ConfigurationProviderInterface;
use Magium\Util\Configuration\ConfigurationReader;
use Magium\Util\Configuration\EnvironmentConfigurationReader;
use Magium\Util\Configuration\StandardConfigurationProvider;
use Magium\Util\Log\Logger;
use Magium\Util\TestCase\RegistrationListener;
use Magium\WebDriver\LoggingRemoteExecuteMethod;
use Magium\WebDriver\WebDriver;
use Zend\Di\Config;
use Zend\Di\Di;
use Zend\I18n\Translator\Translator;
use Zend\Log\Writer\Noop;

class Initializer
{

    protected $testCaseConfiguration = 'Magium\TestCaseConfiguration';
    protected $testCaseConfigurationObject;
    protected $initialized;
    protected $configurationProvider;
    protected static $initDi;

    public function __construct(
        $testCaseConfigurationType = null,
        TestCaseConfiguration $object = null,
        ConfigurationProviderInterface $configurationProvider = null
    )
    {
        if ($testCaseConfigurationType !== null) {
            $this->testCaseConfiguration = $testCaseConfigurationType;
        }
        if ($object instanceof TestCaseConfiguration) {
            $this->testCaseConfigurationObject = $object;
        }

        $this->configurationProvider = $configurationProvider;
        if (!$this->configurationProvider instanceof ConfigurationProviderInterface) {
            $preference = self::getInitializationDependencyInjectionContainer()->instanceManager()->getTypePreferences(ConfigurationProviderInterface::class);
            if (is_array($preference) && count($preference)) {
                $preference = array_shift($preference);
            } else {
                $preference = StandardConfigurationProvider::class;
            }
            $this->configurationProvider = self::getInitializationDependencyInjectionContainer()->get($preference);
        }
    }

    public static function setInitializationDependencyInjectionContainer(ContainerInterface $container)
    {
        self::$initDi = $container;
    }

    /**
     * This method returns a DI container that will ONLY be used to initialize this class
     *
     * @return Di
     */

    public static function getInitializationDependencyInjectionContainer()
    {
        if (!self::$initDi instanceof Di) {
            self::$initDi = new Di();
            self::$initDi->instanceManager()->addTypePreference(ConfigurationProviderInterface::class, StandardConfigurationProvider::class);
        }
        return self::$initDi;
    }

    protected function injectTestCaseHierarchy(AbstractTestCase $testCase)
    {
        $rc = new \ReflectionClass($testCase);
        while ($rc->getParentClass()) {
            $class = $rc->getParentClass()->getName();
            $testCase->getDi()->instanceManager()->addSharedInstance($testCase, $class);
            $rc = new \ReflectionClass($class);
        }
    }

    protected function configureWebDriver(AbstractTestCase $testCase)
    {
        $webDriver = $testCase->getDi()->get('Magium\WebDriver\WebDriver');
        if ($webDriver instanceof WebDriver) {
            $testCase->setWebdriver($webDriver);
            $testCase->setTypePreference(
                'Facebook\WebDriver\WebDriver',
                'Magium\WebDriver\WebDriver'
            );
            $testCase->setTypePreference(
                'Facebook\WebDriver\RemoteWebDriver',
                'Magium\WebDriver\WebDriver'
            );
        } else {
            throw new InvalidConfigurationException('DIC has misconfigured WebDriver object');
        }
    }

    protected function initLoggingExecutor(AbstractTestCase $testCase)
    {
        $remote = $testCase->getDi()->get('Magium\WebDriver\LoggingRemoteExecuteMethod');
        if ($remote instanceof LoggingRemoteExecuteMethod) {
            $testCase->getWebdriver()->setRemoteExecuteMethod($remote);
        } else {
            throw new InvalidConfigurationException('DIC has invalid logger configured');
        }
    }

    protected function configureClairvoyant(AbstractTestCase $testCase)
    {
        // This is going to be refactored in a completely backwards compatible way.  Currently, because the DiC is
        // rebuilt for each request it doesn't maintain state between tests.  This is a good thing... except when
        // something that understands it (the MasterListener) does retain state.

        $clairvoyant = $this->initClairvoyant($testCase);

        /* @var $clairvoyant \Magium\Util\Api\Clairvoyant\Clairvoyant */
        $request = $testCase->get('Magium\Util\Api\Request');
        if ($request instanceof Request) {
            $clairvoyant->setApiRequest($request);
        }
        $clairvoyant->reset();
        $clairvoyant->setSessionId($testCase->getWebdriver()->getSessionID());
        $clairvoyant->setCapability($this->testCaseConfigurationObject->getCapabilities());
        $testCase->getLogger()->addWriter($clairvoyant);
    }

    protected function executeCallbacks(AbstractTestCase $testCase)
    {
        RegistrationListener::executeCallbacks($testCase);
    }

    protected function setCharacteristics(AbstractTestCase $testCase)
    {
        $testCase->getLogger()->addCharacteristic(Logger::CHARACTERISTIC_BROWSER, $testCase->getWebdriver()->getBrowser());
        $testCase->getLogger()->addCharacteristic(Logger::CHARACTERISTIC_OPERATING_SYSTEM, $testCase->getWebdriver()->getPlatform());
    }

    protected function attachMasterListener(AbstractTestCase $testCase)
    {
        $testCase->getDi()->instanceManager()->addSharedInstance(AbstractTestCase::getMasterListener(), 'Magium\Util\Phpunit\MasterListener');
    }

    public function initialize(AbstractTestCase $testCase, $force = false)
    {
        if ($this->initialized === $testCase && !$force) {
            return;
        }
        $this->configureDi($testCase);
        $this->attachMasterListener($testCase);
        $this->injectTestCaseHierarchy($testCase);
        $this->configureWebDriver($testCase);
        $this->initLoggingExecutor($testCase);
        $this->configureClairvoyant($testCase);
        $this->setCharacteristics($testCase);
        $this->executeCallbacks($testCase);
        $this->initialized = $testCase;
    }


    protected function initClairvoyant(AbstractTestCase $testCase)
    {
        $clairvoyant = AbstractTestCase::getMasterListener()->getListener('Magium\Util\Api\Clairvoyant\Clairvoyant');
        if ($clairvoyant instanceof Clairvoyant) {
            $testCase->getDi()->instanceManager()->addSharedInstance($clairvoyant, get_class($clairvoyant));
        } else {
            $clairvoyant = $testCase->get('Magium\Util\Api\Clairvoyant\Clairvoyant');
            if ($clairvoyant instanceof Clairvoyant) {
                AbstractTestCase::getMasterListener()->addListener($clairvoyant);
            } else {
                throw new InvalidConfigurationException('Invalid Clairvoyant preference');
            }
        }
        return $clairvoyant;
    }

    protected function getDefaultConfiguration()
    {
        return [
            'definition' => [
                'class' => [
                    'Magium\WebDriver\WebDriver' => [
                        'instantiator' => 'Magium\WebDriver\WebDriverFactory::create'
                    ],

                    'Magium\WebDriver\WebDriverFactory' => [
                        'create'       => $this->testCaseConfigurationObject->getWebDriverConfiguration()
                    ]
                ]
            ],
            'instance'  => [
                'preference' => [
                    Translator::class => [\Magium\Util\Translator\Translator::class]
                ],
                'Magium\Util\Translator\Translator' => [
                    'parameters'    => [
                        'locale'    => 'en_US'
                    ]
                ],
                'Magium\Util\Log\Logger'   => [
                    'parameters'    => [
                        'options'   => [
                            'writers' => [
                                [
                                    'name' => Noop::class,
                                    'options' => []
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function configureDi(AbstractTestCase $testCase)
    {

        if (!$this->testCaseConfigurationObject instanceof TestCaseConfiguration) {
            if ($testCase->getDi() instanceof Di) {

                $testCaseConfiguration = $testCase->get($this->testCaseConfiguration);
                if ($testCaseConfiguration instanceof TestCaseConfiguration) {
                    $this->testCaseConfigurationObject = $testCaseConfiguration;
                }
            } else {
                $this->testCaseConfigurationObject = new $this->testCaseConfiguration(
                    $this->configurationProvider
                    , new DefaultPropertyCollector()
                );
            }
        }
        if ($testCase->getDi() instanceof Di) {
            return;
        }
        /* @var $configuration TestCaseConfiguration */
        $configArray = $this->getDefaultConfiguration();

        $count = 0;

        $path = realpath(__DIR__ . '/../');

        while ($count++ < 5) {
            $dir = "{$path}/configuration/";
            if (is_dir($dir)) {
                foreach (glob($dir . '*.php') as $file) {
                    $configArray = array_merge_recursive($configArray, include $file);
                }
                break;
            }
            $path .= '/../';
        }


        $configArray = $this->testCaseConfigurationObject->reprocessConfiguration($configArray);
        $configuration = new Config($configArray);

        $di = new Di();
        $configuration->configure($di);
        $testCase->setDi($di);
        $di->instanceManager()->addSharedInstance($di, Di::class);

        $this->setConfigurationProvider($testCase);
    }

    public function setConfigurationProvider(AbstractTestCase $testCase)
    {
        $configuredProvider = self::getInitializationDependencyInjectionContainer()->instanceManager()->getTypePreferences(ConfigurationProviderInterface::class);
        if (is_array($configuredProvider) && count($configuredProvider)) {
            $configuredProvider = array_shift($configuredProvider);
        } else {
            $configuredProvider = StandardConfigurationProvider::class;
        }
        $testCase->setTypePreference(
            ConfigurationProviderInterface::class,
            $configuredProvider
        );
        $this->configurationProvider->configureDi($testCase->getDi());

    }

}
