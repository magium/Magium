<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;
use Magium\WebDriver\FastSelectElement;

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

    public function testOption()
    {
        $this->writePage();
        $element = $this->byText('Some Text');
        self::assertEquals('option', $element->getTagName());
    }


    public function testOptionWithParentSelector()
    {
        $this->writePage();
        $element = $this->byText('Some Text', null, '//select[@id="select2"]');
        $element->click();
        $select = new FastSelectElement($this->webdriver, '//select[@id="select2"]');
        $options = $select->getSelectedOptions();
        $option = array_shift($options);
        self::assertEquals('Some Text', $option['label']);
    }

    public function testLabelSelector()
    {
        $this->writePage();
        $checkElement = $this->byId('testcheckbox');
        self::assertNull($checkElement->getAttribute('checked'));
        $element = $this->byText('Checkbox');
        $element->click();

        self::assertNotNull($checkElement->getAttribute('checked'));
    }

    public function testOptionWithTranslator()
    {
        $this->writePage();
        $this->byText('{{Some Text}}');
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
<html><body>
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
</body></html>
SCRIPT;

        $this->filename = tempnam(sys_get_temp_dir(), 'test').'.html';
        $fh = fopen($this->filename, 'w+');
        fwrite($fh, $body);
        fclose($fh);
        chmod($this->filename, 0666);
        $this->commandOpen('file://' . $this->filename);

    }

}