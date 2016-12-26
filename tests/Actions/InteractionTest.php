<?php

namespace Tests\Magium\Actions;

use Magium\AbstractTestCase;
use Magium\Actions\Click\ByCss;
use Magium\Actions\Click\ById;
use Magium\Actions\Click\ByText;
use Magium\Actions\Click\ByXpath;
use Magium\Actions\Type;
use Magium\WebDriver\WebDriver;

class InteractionTest extends AbstractTestCase
{

    protected $filename;

    public function testType()
    {
        $this->writeFile();
        $this->webdriver->byId('boogers')->click();
        $type = $this->getAction(Type::ACTION);
        self::assertInstanceOf(Type::class, $type);
        /* @var $type Type */
        $type->execute('test value');

        $formValue = $this->webdriver->byId('boogers')->getAttribute('value');
        self::assertEquals('test value', $formValue);
    }

    public function testClickById()
    {
        $this->writeFile();
        $this->assertElementNotDisplayed('todisplay', WebDriver::BY_ID);
        $click = $this->getAction(ById::ACTION);
        self::assertInstanceOf(ById::class, $click);
        /* @var $click ById */
        $click->execute('click');
        $this->assertElementDisplayed('todisplay', WebDriver::BY_ID);
    }

    public function testClickByCss()
    {
        $this->writeFile();
        $this->assertElementNotDisplayed('todisplay', WebDriver::BY_ID);
        $click = $this->getAction(ByCss::ACTION);
        self::assertInstanceOf(ByCss::class, $click);
        /* @var $click ByCss */
        $click->execute('button');
        $this->assertElementDisplayed('todisplay', WebDriver::BY_ID);
    }

    public function testClickByXpath()
    {
        $this->writeFile();
        $this->assertElementNotDisplayed('todisplay', WebDriver::BY_ID);
        $click = $this->getAction(ByXpath::ACTION);
        self::assertInstanceOf(ByXpath::class, $click);
        /* @var $click ByXpath */
        $click->execute('//*[@id="click"]');
        $this->assertElementDisplayed('todisplay', WebDriver::BY_ID);
    }

    public function testClickByText()
    {
        $this->writeFile();
        $this->assertElementNotDisplayed('todisplay', WebDriver::BY_ID);
        $click = $this->getAction(ByText::ACTION);
        self::assertInstanceOf(ByText::class, $click);
        /* @var $click ByText */
        $click->execute('Click');
        $this->assertElementDisplayed('todisplay', WebDriver::BY_ID);
    }

    public function testMoveById()
    {
        $this->writeFile();
        $this->assertElementNotDisplayed('todisplay', WebDriver::BY_ID);
        $click = $this->getAction(\Magium\Actions\Mousemove\ById::ACTION);
        self::assertInstanceOf(\Magium\Actions\Mousemove\ById::class, $click);
        /* @var $click ById */
        $click->execute('move');
        $this->assertElementDisplayed('todisplay', WebDriver::BY_ID);
    }

    public function testMoveByCss()
    {
        $this->writeFile();
        $this->assertElementNotDisplayed('todisplay', WebDriver::BY_ID);
        $click = $this->getAction(\Magium\Actions\Mousemove\ByCss::ACTION);
        self::assertInstanceOf(\Magium\Actions\Mousemove\ByCss::class, $click);
        /* @var $click ByCss */
        $click->execute('button#move');
        $this->assertElementDisplayed('todisplay', WebDriver::BY_ID);
    }

    public function testMoveByXpath()
    {
        $this->writeFile();
        $this->assertElementNotDisplayed('todisplay', WebDriver::BY_ID);
        $click = $this->getAction(\Magium\Actions\Mousemove\ByXpath::ACTION);
        self::assertInstanceOf(\Magium\Actions\Mousemove\ByXpath::class, $click);
        /* @var $click ByXpath */
        $click->execute('//*[@id="move"]');
        $this->assertElementDisplayed('todisplay', WebDriver::BY_ID);
    }

    public function testMoveByText()
    {
        $this->writeFile();
        $this->assertElementNotDisplayed('todisplay', WebDriver::BY_ID);
        $click = $this->getAction(\Magium\Actions\Mousemove\ByText::ACTION);
        self::assertInstanceOf(\Magium\Actions\Mousemove\ByText::class, $click);
        /* @var $click ByText */
        $click->execute('Move');
        $this->assertElementDisplayed('todisplay', WebDriver::BY_ID);
    }

    protected function tearDown()
    {
        unlink($this->filename);
        parent::tearDown();
    }

    protected function writeFile()
    {
        $this->filename = tempnam(sys_get_temp_dir(), 'test') . '.html';
        file_put_contents($this->filename, <<<HTML
<html>
<body>
<input id="boogers">
<button id="click" onclick="document.getElementById('todisplay').style.display='block'">Click</button>
<button id="move" onmouseover="document.getElementById('todisplay').style.display='block'">Move</button>
<div id="todisplay" style="display: none">Display Me</div>
</body>
</html>
HTML
        );
        $this->commandOpen('file://' . $this->filename);

    }

}
