<?php

namespace Tests\Magium\WebDriver;

use Magium\AbstractTestCase;
use Magium\WebDriver\WebDriverElementProxy;

class WebDriverProxyTest extends AbstractTestCase
{

    public function testProxyClick()
    {
        $this->writePage(<<<HTML
<div id="test">Test</div>
<button id="clickme" onclick="alert('test')">Click</button>
<input id="sendMeKeys">
HTML
);
        $proxy = new WebDriverElementProxy(
            $this->webdriver,
            'test'
        );

        self::assertEquals('Test', $proxy->getText());

        $proxy = new WebDriverElementProxy(
            $this->webdriver,
            'sendMeKeys'
        );
        $proxy->sendKeys('Test');
        $this->sleep('100ms');
        self::assertEquals('Test', $proxy->getAttribute('value'));

        $proxy = new WebDriverElementProxy(
            $this->webdriver,
            'clickme'
        );
        $proxy->click();

        $this->webdriver->switchTo()->alert()->dismiss();
    }

    protected $filename;

    protected function writePage($script)
    {
        $script = sprintf('<html><body>%s</body></html>', $script);
        $this->filename = tempnam(sys_get_temp_dir(), '') . '.html';
        $fh = fopen($this->filename, 'w+');
        fwrite($fh, $script);
        fclose($fh);

        $this->commandOpen('file://' . $this->filename);
    }

    protected function unlink()
    {
        unlink($this->filename);
    }

}