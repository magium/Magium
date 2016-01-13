<?php

namespace Tests\FastSelect;

use Magium\AbstractTestCase;
use Magium\WebDriver\FastSelectElement;

class FastSelectTest extends AbstractTestCase
{

    public function testGetOptions()
    {
        $this->writePage();
        $fast = new FastSelectElement($this->webdriver, '//select[@id="select"]');
        $result = $fast->getOptions();
        self::assertCount(2, $result);
    }

    public function testGetSelectedOptions()
    {
        $this->writePage();
        $this->webdriver->byXpath('//*[@id="select"]/option[1]')->click();
        $fast = new FastSelectElement($this->webdriver, '//select[@id="select"]');
        $result = $fast->getSelectedOptions();
        self::assertCount(1, $result);
    }


    public function testClearSelectedOptions()
    {
        $this->writePage();
        $this->webdriver->byXpath('//*[@id="select"]/option[1]')->click();
        $fast = new FastSelectElement($this->webdriver, '//select[@id="select"]');
        $fast->clearSelectedOptions();
        $result = $fast->getSelectedOptions();
        self::assertCount(0, $result);
    }

    protected $filename;

    protected function tearDown()
    {
        parent::tearDown();
        unlink($this->filename);
    }

    protected function writePage()
    {
        $script =
<<<SCRIPT
<html>
<body>
<form id="test">
    <select id="select">
        <option value="1">One</option>
        <option value="2">Two</option>
    </select>
</form>
</body>
</html>
SCRIPT;
        $this->filename = tempnam(sys_get_temp_dir(), 'test');
        $fh = fopen($this->filename, 'w+');
        fwrite($fh, $script);
        fclose($fh);
        chmod($this->filename, 0666);
        $this->commandOpen('file://' . $this->filename);

    }

}