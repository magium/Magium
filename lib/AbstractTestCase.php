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

    protected function setUp()
    {
        $configuration = new \Zend\Di\Config(array(
            'definition' => array(
                'class' => array(
                    'Magium\WebDriver\WebDriver' => array(
                        'instantiator' => 'Magium\WebDriver\WebDriver::create',
                        'create'       => [
                            'url' => ['default' => 'http://localhost:4444/wd/hub'],
                            'desired_capabilities' => ['default' => DesiredCapabilities::chrome()]
                        ]
                    )
                )
            )
        ));
        // TODO set configurable configuration
        $this->di = new \Zend\Di\Di();
        $configuration->configure($this->di);
        $this->di->instanceManager()->addSharedInstance($this, 'Magium\AbstractTestCase');
        $this->webdriver = $this->di->get('Magium\WebDriver\WebDriver');
        
    }
    
    public function get($class)
    {
        return $this->di->get($class);
    }

    /**
     *
     * @param string $customer
     * @return \Magium\Customer\Customer
     */

    public function getCustomer($customer = 'Magium\Customer\Customer')
    {
        return $this->get($customer);
    }
    /**
     *
     * @param string $theme
     * @return \Magium\Themes\ThemeConfiguration
     */

    public function getTheme($theme = 'Magium\Themes\ThemeConfiguration')
    {
        return $this->get($theme);
    }

    public function assertElementExists($selector, $by = 'byId')
    {
        try {
            self::assertWebDriverElement($this->webdriver->$by($selector));
        } catch (\Exception $e) {
            self::assertTrue(false, sprintf('Element "%s" cannot be found using selector "%s"', $selector, $by));
        }
    }

    public static function assertWebDriverElement($element)
    {
        self::assertInstanceOf('Facebook\WebDriver\WebDriverElement', $element);
    }
    
    /**
     * 
     * @param string $customer
     * @return \Magium\Admin\Account
     */
    
    public function getAdminAccount($adminAccountCLass = 'Magium\Admin\Account')
    {
        return $this->get($adminAccountCLass);
    }
    
    /**
     * 
     * @param string $navigator
     * @return \Magium\Navigators\BaseMenuNavigator
     */
    
    public function getNavigator($navigator = 'Magium\Navigators\BaseMenuNavigator')
    {
        return $this->get($navigator);
    }
    
    /**
     *
     * @param string $navigator
     * @return \Magium\Navigators\AdminMenuNavigator
     */
    
    public function getAdminNavigator($navigator = 'Magium\Navigators\AdminMenuNavigator')
    {
        return $this->get($navigator);
    }
    
    public function commandOpen($url)
    {
        $this->get('Magium\Commands\Open')->open($url);
    }

    public function switchThemeConfiguration($fullyQualifiedClassName)
    {
        $this->di->instanceManager()->setTypePreference('Magium\Navigators\BaseNavigator', $fullyQualifiedClassName);    
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