<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;

class CoreAssertionTest extends AbstractTestCase
{

    public function testAssertElementExists()
    {
        $this->writePage();
        $this->assertElementExists('select1');
    }

    public function testAssertElementNotExists()
    {
        $this->writePage();
        $this->assertElementNotExists('select0000');
    }

    public function testElementDisplayed()
    {
        $this->writePage();
        $this->assertElementDisplayed('select1');
    }


    public function testElementNotDisplayed()
    {
        $this->writePage();
        $this->assertElementExists('hiddenElement');
        $this->assertElementNotDisplayed('hiddenElement');

    }

    public function testTitleIs()
    {
        $this->writePage();
        $this->assertTitleEquals('Test Title');
    }

    public function testTitleContains()
    {
        $this->writePage();
        $this->assertTitleContains('Title');
    }


    public function testTitleNotIs()
    {
        $this->writePage();
        $this->assertNotTitleEquals('My little buttercup');
    }

    public function testTitleNotContains()
    {
        $this->writePage();
        $this->assertNotTitleContains('My little buttercup');
    }

    public function testPageHasText()
    {
        $this->writePage();
        $this->assertPageHasText('Text');
    }

    public function testPageNotHasText()
    {

        $this->writePage();
        $this->assertPageNotHasText('My little buttercup');
    }

    protected function writePage()
    {


        $body = <<<SCRIPT
            document.write('<html>
<head><title>Test Title</title></head>
<body>
<ol>
<li>Text 1</li>
<li><a>Text 1</a></li>
<li>Text 2</li>
<li><span><a>Text 2</a></span></li>
</ol>
<select id="select1">
<option>Some Text</option>
</select>
<select id="select2">
<option selected="selected">Some Text Begin</option>
<option>Some Text</option>
</select>
<input type="checkbox" id="testcheckbox">
<label for="testcheckbox">Checkbox</label>
<div id="hiddenElement" style="display: none;" >You should not see this</div>
            </body></html>')
SCRIPT;

        $script = preg_replace("/[\n\r]/", '', $body);
        $this->webdriver->executeScript($script);

    }

}