<?php

namespace Tests\Magium\AbstractTestCase;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Magium\AbstractTestCase;
use Magium\TestCase\Initializer;

class WebDriverArgumentsTest extends AbstractTestCase
{

    protected function setUp()
    {
        $this->initializer = new EspanolChromeInitializer();
        parent::setUp();
    }
    
    public function testEspanol()
    {
        self::markTestSkipped('This test is skipped because it will break on your system.  It checks to make sure that the language has been set');
        $this->commandOpen('http://magento19.loc/test.php');
        $this->assertPageHasText('es-ES');
    }

}

class EspanolChromeInitializer extends Initializer
{

    protected function getDefaultConfiguration()
    {
        $config = parent::getDefaultConfiguration();
        $capabilitities = $config['definition']['class']['Magium\WebDriver\WebDriverFactory']['create']['desired_capabilities']['default'];
        if ($capabilitities instanceof DesiredCapabilities) {
            /*
             * Following is browser-specific functionality.  Non-browser-generic commands can be set here.  This example
             * changes the language for the browser to Spanish.
             *
             * The code for the HTML page is
             * <html><body><?php echo $_SERVER['HTTP_ACCEPT_LANGUAGE']; ?></body></html>
             */
            $options = new ChromeOptions();
            $options->addArguments(['--lang=es']);
            $capabilitities->setCapability(ChromeOptions::CAPABILITY, $options);
        }
        return $config;
    }


}