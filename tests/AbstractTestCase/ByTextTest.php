<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;

class ByTextTest extends AbstractTestCase
{

    public function testLinkPreference()
    {
        $this->writePage();
        $element = $this->byText('Text 1');
        self::assertEquals('a', $element->getTagName());
    }


    public function testSpanPreference()
    {
        $this->writePage();
        $element = $this->byText('Text 2');
        self::assertEquals('span', $element->getTagName());
    }

    public function testSpecificPreference()
    {
        $this->writePage();
        $element = $this->byText('Text 2', 'li');
        self::assertEquals('li', $element->getTagName());
    }

    protected function writePage()
    {


        $body = <<<SCRIPT
            document.write('<html><body>
<ol>
<li>Text 1</li>
<li><a>Text 1</a></li>
<li>Text 2</li>
<li><span><a>Text 2</a></span></li>
            </body></html>')
SCRIPT;

        $script = preg_replace("/[\n\r]/", '', $body);
        $this->webdriver->executeScript($script);

    }

}