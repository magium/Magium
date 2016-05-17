<?php

namespace Magium\TestCase;

use Magium\AbstractTestCase;
use Magium\InvalidConfigurationException;
use Magium\TestCaseConfiguration;
use Magium\Util\Api\Clairvoyant\Clairvoyant;
use Magium\Util\Api\Request;
use Magium\Util\Configuration\ClassConfigurationReader;
use Magium\Util\Configuration\ConfigurationCollector\DefaultPropertyCollector;
use Magium\Util\Configuration\ConfigurationReader;
use Magium\Util\Configuration\EnvironmentConfigurationReader;
use Magium\Util\Configuration\StandardConfigurationProvider;
use Magium\Util\Log\Logger;
use Magium\Util\TestCase\RegistrationListener;
use Magium\WebDriver\LoggingRemoteExecuteMethod;
use Magium\WebDriver\WebDriver;
use Zend\Di\Config;
use Zend\Di\Di;

class Initializer
{

    protected $testCaseConfiguration = 'Magium\TestCaseConfiguration';
    protected $testCaseConfigurationObject;
    protected $initialized;

    public function __construct(
        $testCaseConfigurationType = null,
        TestCaseConfiguration $object = null
    )
    {
        if ($testCaseConfigurationType !== null) {
            $this->testCaseConfiguration = $testCaseConfigurationType;
        }
        if ($object instanceof TestCaseConfiguration) {
            $this->testCaseConfigurationObject = $object;
        }
    }

    public function initialize(AbstractTestCase $testCase, $force = false)
    {
        if ($this->initialized === $testCase && !$force) {
            return;
        }
        $this->configureDi($testCase);
        $testCase->getDi()->instanceManager()->addSharedInstance(AbstractTestCase::getMasterListener(), 'Magium\Util\Phpunit\MasterListener');

        $rc = new \ReflectionClass($testCase);
        while ($rc->getParentClass()) {
            $class = $rc->getParentClass()->getName();
            $testCase->getDi()->instanceManager()->addSharedInstance($testCase, $class);
            $rc = new \ReflectionClass($class);
        }
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

        $remote = $testCase->getDi()->get('Magium\WebDriver\LoggingRemoteExecuteMethod');
        if ($remote instanceof LoggingRemoteExecuteMethod) {
            $testCase->getWebdriver()->setRemoteExecuteMethod($remote);
        } else {
            throw new InvalidConfigurationException('DIC has invalid logger configured');
        }

        // This is going to be refactored in a completely backwards compatible way.  Currently, because the DiC is
        // rebuilt for each request it doesn't maintain state between tests.  This is a good thing... except when
        // something that understands it (the MasterListener) does restain state.

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
        $testCase->getLogger()->addCharacteristic(Logger::CHARACTERISTIC_BROWSER, $testCase->getWebdriver()->getBrowser());
        $testCase->getLogger()->addCharacteristic(Logger::CHARACTERISTIC_OPERATING_SYSTEM, $testCase->getWebdriver()->getPlatform());

        RegistrationListener::executeCallbacks($testCase);
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
                    'Zend\I18n\Translator\Translator' => ['Magium\Util\Translator\Translator']
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
                                    'name' => 'Zend\Log\Writer\Noop',
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
                    new StandardConfigurationProvider(
                        new ConfigurationReader(),
                        new ClassConfigurationReader(),
                        new EnvironmentConfigurationReader()
                    )
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

        $this->setConfigurationProvider($testCase);
    }

    public function setConfigurationProvider(AbstractTestCase $testCase)
    {
        $testCase->setTypePreference(
            'Magium\Util\Configuration\ConfigurationProviderInterface',
            'Magium\Util\Configuration\StandardConfigurationProvider'
        );

    }

}