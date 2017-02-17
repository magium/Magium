<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;

class ClickableAssertionTest extends AbstractTestCase
{

    public function testElementDisplayedAndClickable()
    {
        $this->writePage(
            <<<SCRIPT
<div><button id="clickme">Text</button></div>
SCRIPT

        );
        $this->assertElementClickable('clickme');
    }

    public function testElementDisplayed()
    {
        $this->writePage(
            <<<SCRIPT
<div><button id="clickme">Text</button></div>
SCRIPT

        );
        $this->assertElementDisplayed('clickme');
    }

    public function testElementDisplayedAndNotClickable()
    {
        $this->writePage(
            <<<SCRIPT
<div style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%">Cover Div</div>
<div><button id="clickme">Text</button></div>
SCRIPT

        );
        $this->assertElementNotClickable('clickme');
    }


    public function testElementNotDisplayedAndClickable()
    {
        $this->writePage(
            <<<SCRIPT
<div style="height: 0px; overflow: hidden; "><button id="clickme">Text</button></div>
SCRIPT

        );
        $this->assertElementNotDisplayed('clickme');
    }


    public function testElementNotDisplayedAndNotClickable()
    {
        $this->writePage(
            <<<SCRIPT
<div style="display: none;"><button id="clickme">Text</button></div>
SCRIPT

        );
        $this->assertElementNotClickable('clickme');

    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->unlink();
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
