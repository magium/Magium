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

    public function testAssertElementNotExistsFailsWhenElementExists()
    {
        $this->setExpectedException('PHPUnit_Framework_AssertionFailedError');
        $this->writePage();
        $this->assertElementNotExists('select1');
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

    public function testPageHasTextWithNoTextThrowsException()
    {
        $this->setExpectedException('PHPUnit_Framework_AssertionFailedError');
        $this->writePage();
        $this->assertPageHasText('My little buttercup');
    }

    public function testPageNotHasText()
    {
        $this->writePage();
        $this->assertPageNotHasText('My little buttercup');
    }


    public function testPageNotHasTextWithRightTextThrowsException()
    {
        $this->setExpectedException('PHPUnit_Framework_AssertionFailedError');
        $this->writePage();
        $this->assertPageNotHasText('Text');
    }

    protected $filename;

    protected function tearDown()
    {
        parent::tearDown();
        unlink($this->filename);
    }


    protected function writePage()
    {


        $body = <<<SCRIPT
<html>
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
            </body></html>
SCRIPT;

        $this->filename = tempnam(sys_get_temp_dir(), 'test');
        $fh = fopen($this->filename, 'w+');
        fwrite($fh, $body);
        fclose($fh);
        chmod($this->filename, 0666);
        $this->commandOpen('file://' . $this->filename);

    }

}