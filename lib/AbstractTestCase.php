<?php

namespace Magium;

use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magium\WebDriver\WebDriver
     */
    protected $webdriver;

    /**
     * @var \Zend\Di\Di
     */

    protected $di;

    const BY_XPATH = 'byXpath';
    const BY_ID    = 'byId';
    const BY_CSS_SELECTOR = 'byCssSelector';

    protected function setUp()
    {
        $defaults = [
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

        $diConfigPath = realpath(__DIR__ . '/../configuration/di.php');
        $configArray = array_merge($defaults, include $diConfigPath);


        $configuration = new \Zend\Di\Config($configArray);
        // TODO set configurable configuration
        $this->di = new \Zend\Di\Di();
        $configuration->configure($this->di);
        $this->di->instanceManager()->addSharedInstance($this, 'Magium\AbstractTestCase');
        // TODO I don't like this because it is hard-coded.  So this might change when I get a chance to think about it
        $this->di->instanceManager()->addSharedInstance($this, 'Magium\Magento\AbstractMagentoTestCase');
        $this->webdriver = $this->di->get('Magium\WebDriver\WebDriver');
        
    }

    protected function tearDown()
    {
        parent::tearDown();
        if ($this->webdriver) {
            $this->webdriver->close();
        }
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
    
}