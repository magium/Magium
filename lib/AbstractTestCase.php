<?php

namespace Magium;

use Facebook\WebDriver\Remote\DesiredCapabilities;
abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /*
     * @var \Magium\WebDriver\WebDriver
     */
    protected $webdriver;
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
                        'instantiator' => 'Magium\WebDriver\WebDriver::create',
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

        $count  = 0;
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
    
    public function byXpath($xpath)
    {
        return $this->webdriver->byXpath($xpath);
    }
    
    public function byId($id)
    {
        return $this->webdriver->byId($id);
    }
    
    public function byCssSelector($selector)
    {
        return $this->webdriver->byCssSelector($selector);
    }
    
}