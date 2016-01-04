<?php

namespace Magium;

use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Magium\WebDriver\WebDriver;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{

    protected static $baseNamespaces = ['Magium'];

    protected $baseThemeClass = 'Magium\Themes\ThemeConfigurationInterface';

    protected $postCallbacks = [];

    /**
     * @var \Magium\WebDriver\WebDriver
     */
    protected $webdriver;

    /**
     * @var \Zend\Di\Di
     */

    protected $di;

    protected $textElementNodeSearch = [
        'span', 'a', 'li', 'label', 'option'
    ];

    const BY_XPATH = 'byXpath';
    const BY_ID    = 'byId';
    const BY_CSS_SELECTOR = 'byCssSelector';
    const BY_TEXT = 'byText';

    protected function setUp()
    {


        $configArray = [
            'definition' => [
                'class' => [
                    'Magium\WebDriver\WebDriver' => [
                        'instantiator' => 'Magium\WebDriver\WebDriverFactory::create'
                    ],
                    'Magium\WebDriver\WebDriverFactory' => [
                        'create'       => [
                            'url' => ['default' => 'http://localhost:4444/wd/hub'],
                            'desired_capabilities' => ['default' => DesiredCapabilities::chrome()]
                        ]
                    ]
                ]
            ],
            'instance'  => [
                'Zend\Log\Logger'   => [
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
                    $configArray = array_merge($configArray, include $file);
                }
                break;
            }
            $path .= '../';
        }


        $configuration = new \Zend\Di\Config($configArray);
        // TODO set configurable configuration
        $this->di = new \Zend\Di\Di();
        $configuration->configure($this->di);

        $rc = new \ReflectionClass($this);
        while ($rc->getParentClass()) {
            $class = $rc->getParentClass()->getName();
            $this->di->instanceManager()->addSharedInstance($this, $class);
            $rc = new \ReflectionClass($class);
        }

        $this->webdriver = $this->di->get('Magium\WebDriver\WebDriver');
    }

    protected function tearDown()
    {
        foreach ($this->postCallbacks as $callback) {
            if (is_callable($callback)) {
                call_user_func($callback);
            }
        }
        parent::tearDown();
        if ($this->webdriver) {
            $this->webdriver->close();
        }
    }

    public static function addBaseNamespace($namespace)
    {
        if (!in_array($namespace, self::$baseNamespaces)) {
            self::$baseNamespaces[] = trim($namespace, '\\');
        }
    }

    public static function resolveClass($class)
    {
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
        return $class;
    }

    public function setTypePreference($type, $preference)
    {
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
        $theme = self::resolveClass('Themes\\' . $theme);
        return $this->get($theme);
    }

    /**
     *
     * @param string $navigator
     * @return mixed
     */

    public function getAction($action)
    {
        $action = self::resolveClass('Actions\\' . $action);

        return $this->get($action);
    }


    /**
     * @param string $name
     * @return \Magium\Magento\Identities\AbstractEntity
     */

    public function getIdentity($name = 'Customer')
    {
        $name = self::resolveClass('Identities\\' . $name);

        return $this->get($name);
    }

    /**
     *
     * @param string $navigator
     * @return \Magium\Magento\Navigators\BaseMenuNavigator
     */

    public function getNavigator($navigator = 'BaseMenu')
    {
        $navigator = self::resolveClass('Navigators\\' . $navigator);

        return $this->get($navigator);
    }

    public function getAssertion($assertion)
    {
        $assertion = self::resolveClass('Assertions\\' . $assertion);

        return $this->get($assertion);
    }

    /**
     *
     * @param string $extractor
     * @return \Magium\Extractors\AbstractExtractor
     */

    public function getExtractor($extractor)
    {
        $extractor = self::resolveClass('Extractors\\' . $extractor);

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
     * @return \Zend\Log\Logger
     */

    public function getLogger()
    {
        return $this->get('Zend\Log\Logger');
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
        try {
            self::assertWebDriverElement($this->webdriver->$by($selector));
        } catch (\Exception $e) {
            self::assertTrue(false, sprintf('Element "%s" cannot be found using selector "%s": %s', $selector, $by, $e->getMessage()));
        }
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

    public function assertElementDisplayed($selector, $by = 'byId')
    {
        try {
            $this->assertElementExists($selector, $by);
            self::assertTrue(
                $this->webdriver->$by($selector)->isDisplayed(),
                sprintf('The element: %s, is not displayed and it should have been', $selector)
            );
        } catch (\Exception $e) {
            self::assertTrue(false, sprintf('Element "%s" cannot be found using selector "%s"', $selector, $by));
        }
    }

    public function assertElementNotDisplayed($selector, $by = 'byId')
    {
        try {
            $this->assertElementExists($selector, $by);
            self::assertFalse(
                $this->webdriver->$by($selector)->isDisplayed(),
                sprintf('The element: %s, is displayed and it should not have been')
            );
        } catch (\Exception $e) {
            self::assertTrue(false, sprintf('Element "%s" cannot be found using selector "%s"', $selector, $by));
        }
    }

    public function assertElementNotExists($selector, $by = 'byId')
    {
        try {
            self::assertWebDriverElement($this->webdriver->$by($selector));
            self::assertTrue(false, sprintf('Element "%s" was found using selector "%s"', $selector, $by));
        } catch (\Exception $e) {
        }
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
        $element = $this->byXpath(sprintf('//%s[contains(., "%s")]', $node, addslashes($text)));
        self::assertNotNull($element, 'Text could not be found in an element ' . $node);
    }

    public function assertPageHasText($text, $message = null)
    {
        $this->assertElementHasText('body', $text, $message);
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
            return $this->byXpath(sprintf($xpathTemplate, $specificNodeType, $this->getTranslator()->translate($text)));
        }

        foreach ($this->textElementNodeSearch as $nodeName) {
            $xpath = sprintf($xpathTemplate, $nodeName, $this->getTranslator()->translate($text));
            if ($this->webdriver->elementExists($xpath, WebDriver::BY_XPATH)) {
                return $this->webdriver->byXpath($xpath);
            }
        }
        // This is here for consistency with the other by* methods
        WebDriverException::throwException(7, 'Could not find element with text: ' . $this->getTranslator()->translate($text), []);
    }


    /**
     * @param string $text
     * @param string $specificNodeType
     * @param string $parentElementSelector
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    public function containsText($text, $specificNodeType = null, $parentElementSelector = null)
    {
        $xpathTemplate = '//%s[contains(., "%s")]';
        if ($parentElementSelector !== null) {
            $xpathTemplate = $parentElementSelector . $xpathTemplate;
        }
        if ($specificNodeType !== null) {
            return $this->byXpath(sprintf($xpathTemplate, $specificNodeType, $this->getTranslator()->translate($text)));
        }

        foreach ($this->textElementNodeSearch as $nodeName) {
            $xpath = sprintf($xpathTemplate, $nodeName, $this->getTranslator()->translate($text));
            if ($this->webdriver->elementExists($xpath, WebDriver::BY_XPATH)) {
                return $this->webdriver->byXpath($xpath);
            }
        }
        // This is here for consistency with the other by* methods
        WebDriverException::throwException(7, 'Could not find element with text: ' . $this->getTranslator()->translate($text), []);
    }

    /**
     * @return \Magium\Util\Translator\Translator
     */

    public function getTranslator()
    {
        return $this->get('Magium\Util\Translator\Translator');
    }

}
