<?php

namespace Magium;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\WebDriverException;
use Magium\Assertions\Element\Clickable;
use Magium\Assertions\Element\Exists;
use Magium\Assertions\Element\NotClickable;
use Magium\Assertions\Element\NotDisplayed;
use Magium\Assertions\Element\NotExists;
use Magium\Assertions\LoggingAssertionExecutor;
use Magium\Util\Phpunit\MasterListener;
use Magium\Util\TestCase\RegistrationListener;
use Magium\WebDriver\WebDriver;
use PHPUnit_Framework_TestResult;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{

    protected static $baseNamespaces = [];

    protected $baseThemeClass = 'Magium\Themes\ThemeConfigurationInterface';

    protected $postCallbacks = [];

    /**
     * @var MasterListener
     */

    protected static $masterListener;

    /**
     * @var \Magium\WebDriver\WebDriver
     */
    protected $webdriver;

    /**
     * @var \Zend\Di\Di
     */

    protected $di;

    protected $textElementNodeSearch = [
        'h1', 'h2', 'h3', 'span', 'a', 'li', 'label', 'option'
    ];

    protected $testCaseConfiguration = 'Magium\TestCaseConfiguration';

    const BY_XPATH = 'byXpath';
    const BY_ID    = 'byId';
    const BY_CSS_SELECTOR = 'byCssSelector';
    const BY_TEXT = 'byText';

    protected static $registrationCallbacks;

    protected function setUp()
    {
        /*
         * Putting this in the setup and not in the property means that an extending class can inject itself easily
         * before the Magium namespace, thus, taking preference over the base namespace
         */
        self::addBaseNamespace('Magium');
        $configuration = new $this->testCaseConfiguration();
        /* @var $configuration TestCaseConfiguration */
        $configArray = [
            'definition' => [
                'class' => [
                    'Magium\WebDriver\WebDriver' => [
                        'instantiator' => 'Magium\WebDriver\WebDriverFactory::create'
                    ],

                    'Magium\WebDriver\WebDriverFactory' => [
                        'create'       => $configuration->getWebDriverConfiguration()
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


        $configArray = $configuration->reprocessConfiguration($configArray);
        $configuration = new \Zend\Di\Config($configArray);

        $this->di = new \Zend\Di\Di();
        $configuration->configure($this->di);
        $this->di->instanceManager()->addSharedInstance(self::$masterListener, 'Magium\Util\Phpunit\MasterListener');

        $rc = new \ReflectionClass($this);
        while ($rc->getParentClass()) {
            $class = $rc->getParentClass()->getName();
            $this->di->instanceManager()->addSharedInstance($this, $class);
            $rc = new \ReflectionClass($class);
        }
        $this->webdriver = $this->di->get('Magium\WebDriver\WebDriver');

        $this->webdriver->setRemoteExecuteMethod($this->di->get('Magium\WebDriver\LoggingRemoteExecuteMethod'));

        RegistrationListener::executeCallbacks($this);
    }

    public function __construct($name = null, array $data = [], $dataName = null)
    {
        self::getMasterListener();
        parent::__construct($name, $data, $dataName);
    }

    public function setTestResultObject(PHPUnit_Framework_TestResult $result)
    {
        // This odd little function is here because the first place where you can reliably add a listener without
        // having to make a phpunit.xml or program argument change

        self::getMasterListener()->bindToResult($result);
        parent::setTestResultObject($result);
    }

    /**
     * @return MasterListener
     */

    public static function getMasterListener()
    {
        if (!self::$masterListener instanceof MasterListener) {
            self::$masterListener = new MasterListener();
        }
        return self::$masterListener;
    }

    protected function tearDown()
    {
        foreach ($this->postCallbacks as $callback) {
            if (is_callable($callback)) {
                call_user_func($callback);
            }
        }
        parent::tearDown();
        if ($this->webdriver instanceof WebDriver) {
            $this->webdriver->quit();
            $this->webdriver = null;
        }
    }


    public function filterWebDriverAction($by)
    {
        switch ($by) {
            case WebDriver::BY_XPATH:
                return 'xpath';
                break;
            case WebDriver::BY_CSS_SELECTOR:
                return 'css_selector';
                break;
            case WebDriver::BY_ID:
                return 'id';
                break;
            default:
                return $by;
                break;
        }
    }

    public function assertElementClickable($selector, $by = WebDriver::BY_ID)
    {
        $this->elementAssertion($selector, $by, Clickable::ASSERTION);
    }


    public function assertElementNotClickable($selector, $by = WebDriver::BY_ID)
    {
        $this->elementAssertion($selector, $by, NotClickable::ASSERTION);

    }

    public function setTestCaseConfigurationClass($class)
    {
        $this->testCaseConfiguration = $class;
    }

    public static function addBaseNamespace($namespace)
    {
        if (!in_array($namespace, self::$baseNamespaces)) {
            self::$baseNamespaces[] = trim($namespace, '\\');
        }
    }

    public static function resolveClass( $class, $prefix = null)
    {
        $origClass = $class;
        if ($prefix !== null) {
            $class = "{$prefix}\\{$class}";
        }
        foreach (self::$baseNamespaces as $namespace) {
            if (strpos($namespace, $class) === 0) {
                // We have a fully qualified class name
                return $class;
            }
        }

        foreach (self::$baseNamespaces as $namespace) {
            $fqClass = $namespace . '\\' . $class;
            if (class_exists($fqClass)) {
                return $fqClass;
            }
        }
        return $origClass;
    }

    public function setTypePreference($type, $preference)
    {
        $type = self::resolveClass($type);
        $preference = self::resolveClass($preference);
        $this->di->instanceManager()->unsetTypePreferences($type);
        $this->di->instanceManager()->setTypePreference($type, [$preference]);

    }

    protected function normalizeClassRequest($class)
    {
        return str_replace('/', '\\', $class);
    }

    public function addPostTestCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidConfigurationException('Callback is not callable');
        }
        $this->postCallbacks[] = $callback;
    }

    /**

     * @param string $theme
     * @return \Magium\Themes\ThemeConfigurationInterface
     */

    public function getTheme($theme = null)
    {
        if ($theme === null) {
            return $this->get($this->baseThemeClass);
        }
        $theme = self::resolveClass($theme, 'Themes');
        return $this->get($theme);
    }

    /**
     *
     * @param string $navigator
     * @return mixed
     */

    public function getAction($action)
    {
        $action = self::resolveClass($action, 'Actions');

        return $this->get($action);
    }


    /**
     * @param string $name
     * @return \Magium\Magento\Identities\AbstractEntity
     */

    public function getIdentity($name = 'Customer')
    {
        $name = self::resolveClass($name, 'Identities');

        return $this->get($name);
    }

    /**
     *
     * @param string $navigator
     * @return \Magium\Magento\Navigators\BaseMenuNavigator
     */

    public function getNavigator($navigator = 'BaseMenu')
    {
        $navigator = self::resolveClass($navigator, 'Navigators');

        return $this->get($navigator);
    }

    public function getAssertion($assertion)
    {
        $assertion = self::resolveClass($assertion, 'Assertions');

        return $this->get($assertion);
    }

    /**
     *
     * @param string $extractor
     * @return \Magium\Extractors\AbstractExtractor
     */

    public function getExtractor($extractor)
    {
        $extractor = self::resolveClass($extractor, 'Extractors');

        return $this->get($extractor);
    }

    /**
     * Sleep the specified amount of time.
     *
     * Options: 1s (1 second), 1ms (1 millisecond), 1us (1 microsecond), 1ns (1 nanosecond)
     *
     * @param $time
     */

    public function sleep($time)
    {
        $length = (int)$time;

        if (strpos($time, 'ms') !== false) {
            usleep($length * 1000);
        } else if (strpos($time, 'us') !== false) {
            usleep($length);
        } else if (strpos($time, 'ns') !== false) {
            time_nanosleep(0, $length);
        } else {
            sleep($length);
        }
    }


    public function commandOpen($url)
    {
        $this->get('Magium\Commands\Open')->open($url);
    }


    /**
     * @return \Magium\Util\Log\Logger
     */

    public function getLogger()
    {
        return $this->get('Magium\Util\Log\Logger');
    }

    public function get($class)
    {
        $class = $this->normalizeClassRequest($class);
        $preferredClass = $this->di->instanceManager()->getTypePreferences($class);
        if (is_array($preferredClass) && count($preferredClass) > 0) {
            $class = array_shift($preferredClass);
        }
        return $this->di->get($class);
    }


    public function assertElementExists($selector, $by = 'byId')
    {
        $this->elementAssertion($selector, $by, Exists::ASSERTION);
    }

    public function assertTitleEquals($title)
    {
        $webTitle = $this->webdriver->getTitle();
        self::assertEquals($title, $webTitle);
    }


    public function assertTitleContains($title)
    {
        $webTitle = $this->webdriver->getTitle();
        self::assertContains($title, $webTitle);
    }


    public function assertNotTitleEquals($title)
    {
        $webTitle = $this->webdriver->getTitle();
        self::assertNotEquals($title, $webTitle);
    }


    public function assertNotTitleContains($title)
    {
        $webTitle = $this->webdriver->getTitle();
        self::assertNotContains($title, $webTitle);
    }

    public function assertURLEquals($url)
    {
        $webUrl = $this->webdriver->getCurrentURL();
        self::assertEquals($url, $webUrl);
    }

    public function assertURLContains($url)
    {
        $webUrl = $this->webdriver->getCurrentURL();
        self::assertContains($url, $webUrl);
    }


    public function assertURLNotEquals($url)
    {
        $webUrl = $this->webdriver->getCurrentURL();
        self::assertNotEquals($url, $webUrl);
    }

    public function assertURLNotContains($url)
    {
        $webUrl = $this->webdriver->getCurrentURL();
        self::assertNotContains($url, $webUrl);
    }

    protected function elementAssertion($selector, $by, $name)
    {
        $executor = $this->getAssertion(LoggingAssertionExecutor::ASSERTION);
        $assertion = $this->getAssertion($name);
        $assertion->setSelector($selector)->setBy($by);
        $executor->execute($assertion);
    }

    public function assertElementDisplayed($selector, $by = 'byId')
    {
        $this->elementAssertion($selector, $by, NotDisplayed::ASSERTION);
    }

    public function assertElementNotDisplayed($selector, $by = 'byId')
    {
        try {
            $this->assertElementExists($selector, $by);
            self::assertFalse(
                $this->webdriver->$by($selector)->isDisplayed(),
                sprintf('The element: %s, is displayed and it should not have been', $selector)
            );
        } catch (\Exception $e) {
            $this->fail(sprintf('Element "%s" cannot be found using selector "%s"', $selector, $by));
        }
    }

    public function assertElementNotExists($selector, $by = 'byId')
    {
        $this->elementAssertion($selector, $by, NotExists::ASSERTION);
    }


    public function switchThemeConfiguration($fullyQualifiedClassName)
    {

        if (is_subclass_of($fullyQualifiedClassName, 'Magium\Themes\ThemeConfigurationInterface')) {
            $this->baseThemeClass = $fullyQualifiedClassName;
            $this->di->instanceManager()->unsetTypePreferences('Magium\Themes\ThemeConfigurationInterface');
            $this->di->instanceManager()->setTypePreference('Magium\Themes\ThemeConfigurationInterface', [$fullyQualifiedClassName]);

            if (is_subclass_of($fullyQualifiedClassName, 'Magium\Themes\BaseThemeInterface')) {
                $this->di->instanceManager()->unsetTypePreferences('Magium\Themes\BaseThemeInterface');
                $this->di->instanceManager()->setTypePreference('Magium\Themes\BaseThemeInterface', [$fullyQualifiedClassName]);
            }
            $this->getTheme()->configure($this);
        } else {
            throw new InvalidConfigurationException('The theme configuration implement Magium\Themes\ThemeConfigurationInterface');
        }

    }

    public static function assertWebDriverElement($element)
    {
        self::assertInstanceOf('Facebook\WebDriver\WebDriverElement', $element);
    }

    public function assertElementHasText($node, $text, $message = null)
    {
        try {
            $this->byXpath(sprintf('//%s[contains(., "%s")]', $node, addslashes($text)));
        } catch (\Exception $e) {
            $this->fail('The body did not contain the text: ' . $text);
        }
    }

    public function assertPageHasText($text)
    {
        try {
            $this->webdriver->byXpath(sprintf('//body[contains(., "%s")]', $text));
            // If the element is not found then an exception will be thrown
        } catch (\Exception $e) {
            $this->fail('The body did not contain the text: ' . $text);
        }

    }

    public function assertPageNotHasText($text)
    {
        try {
            $this->webdriver->byXpath(sprintf('//body[contains(., "%s")]', $text));
            $this->fail('The page contains the words: ' . $text);
        } catch (NoSuchElementException $e) {
            // Exception thrown is a success
        }
    }

    /**
     * @param $xpath
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */

    public function byXpath($xpath)
    {
        return $this->webdriver->byXpath($xpath);
    }

    /**
     * @param $id
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */

    public function byId($id)
    {
        return $this->webdriver->byId($id);
    }

    /**
     * @param $selector
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */

    public function byCssSelector($selector)
    {
        return $this->webdriver->byCssSelector($selector);
    }


    /**
     * @param string $text
     * @param string $specificNodeType
     * @param string $parentElementSelector
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    public function byText($text, $specificNodeType = null, $parentElementSelector = null)
    {
        $xpathTemplate = '//%s[concat(" ",normalize-space(.)," ") = " %s "]';
        if ($parentElementSelector !== null) {
            $xpathTemplate = $parentElementSelector . $xpathTemplate;
        }
        if ($specificNodeType !== null) {
            return $this->byXpath(sprintf($xpathTemplate, $specificNodeType, $this->getTranslator()->translatePlaceholders($text)));
        }

        foreach ($this->textElementNodeSearch as $nodeName) {
            $xpath = sprintf($xpathTemplate, $nodeName, $this->getTranslator()->translatePlaceholders($text));
            if ($this->webdriver->elementExists($xpath, WebDriver::BY_XPATH)) {
                return $this->webdriver->byXpath($xpath);
            }
        }
        // This is here for consistency with the other by* methods
        WebDriverException::throwException(7, 'Could not find element with text: ' . $this->getTranslator()->translatePlaceholders($text), []);
    }


    /**
     * @param string $text
     * @param string $specificNodeType
     * @param string $parentElementSelector
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    public function byContainsText($text, $specificNodeType = null, $parentElementSelector = null)
    {
        $xpathTemplate = '//%s[contains(., "%s")]';
        if ($parentElementSelector !== null) {
            $xpathTemplate = $parentElementSelector . $xpathTemplate;
        }
        if ($specificNodeType !== null) {
            return $this->byXpath(sprintf($xpathTemplate, $specificNodeType, $this->getTranslator()->translatePlaceholders($text)));
        }

        foreach ($this->textElementNodeSearch as $nodeName) {
            $xpath = sprintf($xpathTemplate, $nodeName, $this->getTranslator()->translatePlaceholders($text));
            if ($this->webdriver->elementExists($xpath, WebDriver::BY_XPATH)) {
                return $this->webdriver->byXpath($xpath);
            }
        }
        // This is here for consistency with the other by* methods
        WebDriverException::throwException(7, 'Could not find element with text: ' . $this->getTranslator()->translatePlaceholders($text), []);
    }

    /**
     * @return \Magium\Util\Translator\Translator
     */

    public function getTranslator()
    {
        return $this->get('Magium\Util\Translator\Translator');
    }

    public function addTranslationCsvFile($file, $locale)
    {
        $this->getTranslator()->addTranslationCsvFile($file, $locale);
    }
}
