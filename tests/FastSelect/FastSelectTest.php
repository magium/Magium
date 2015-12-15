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

    protected function writePage()
    {
        $this->webdriver->executeScript(
<<<SCRIPT
document.write('<html><body><form id="test">    <select id="select">        <option value="1">One</option>        <option value="2">Two</option>    </select></form></body></html>')
SCRIPT
        );
    }

}