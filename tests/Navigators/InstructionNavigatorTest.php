<?php

namespace Tests\Magium\Navigators;

use Magium\AbstractTestCase;
use Magium\Navigators\InstructionNavigator;
use Magium\WebDriver\WebDriver;

class InstructionNavigatorTest extends AbstractTestCase
{

    protected $filename;

    protected function setUp()
    {
        parent::setUp();
        $this->switchThemeConfiguration('Tests\Magium\Navigators\ThemeConfiguration');
    }

    protected function tearDown()
    {
        if (file_exists($this->filename)) {
            unlink($this->filename);
        }
        parent::tearDown();
    }

    public function testClicks()
    {
        $this->writeTest(<<<HTML
<button onclick="writeElement()" id="click">Click</button>
<div id="result" style="display: none; ">Result</div>
<script type="text/javascript">
    function writeElement() {
        document.getElementById('result').style.display = 'block';
        return false;
    }        
</script> 
HTML
        );
        $navigator = $this->getNavigator('InstructionNavigator');
        $navigator->navigateTo([
           [InstructionNavigator::INSTRUCTION_MOUSE_CLICK, '//button[@id="click"]']
        ]);
        $this->sleep('100ms');
        $this->assertElementDisplayed('//div[@id="result"]', WebDriver::BY_XPATH);
    }

    public function testMove()
    {
        $this->writeTest(<<<HTML
<button onmouseover="writeElement()" id="move">Move</button>
<script type="text/javascript">
    function writeElement() {
        document.write('<div id="result">Result</div>');
    }        
</script> 
HTML
        );
        $navigator = $this->getNavigator('InstructionNavigator');
        $navigator->navigateTo([
           [InstructionNavigator::INSTRUCTION_MOUSE_MOVETO, '//button[@id="move"]']
        ]);
        $this->sleep('100ms');
        $this->assertElementExists('//div[@id="result"]', WebDriver::BY_XPATH);
    }

    public function testWaitForExists()
    {
        $this->writeTest(<<<HTML
<script type="text/javascript">
    setTimeout(function() {
        document.write('<div id="result">Result</div>');
    }, 1000);
</script> 
HTML
        );
        $navigator = $this->getNavigator('InstructionNavigator');
        $navigator->navigateTo([
           [InstructionNavigator::INSTRUCTION_WAIT_FOR_EXISTS, '//div[@id="result"]']
        ]);
        $this->sleep('100ms');
        $this->assertElementExists('//div[@id="result"]', WebDriver::BY_XPATH);
    }

    public function testWaitForDisplayed()
    {
        $this->writeTest(<<<HTML
<div id="result" style="display: none; ">Result</div>
<script type="text/javascript">
    setTimeout(function() {
        document.getElementById('result').style.display = 'block';
    }, 1000);
</script> 
HTML
        );
        $navigator = $this->getNavigator('InstructionNavigator');
        $startTime = microtime(true);
        $navigator->navigateTo([
           [InstructionNavigator::INSTRUCTION_WAIT_FOR_DISPLAYED, '//div[@id="result"]']
        ]);
        self::assertGreaterThan($startTime, microtime(true) + 0.5);
        $this->sleep('100ms');
        $this->assertElementDisplayed('//div[@id="result"]', WebDriver::BY_XPATH);
    }

    public function testWaitForHidden()
    {
        $this->writeTest(<<<HTML
<div id="result">Result</div>
<script type="text/javascript">
    setTimeout(function() {
        document.getElementById('result').style.display = 'none';
    }, 1000);
</script> 
HTML
        );
        $navigator = $this->getNavigator('InstructionNavigator');
        $startTime = microtime(true);
        $navigator->navigateTo([
           [InstructionNavigator::INSTRUCTION_WAIT_FOR_HIDDEN, '//div[@id="result"]']
        ]);
        self::assertGreaterThan($startTime, microtime(true) + 0.5);
        $this->sleep('100ms');
        $this->assertElementNotDisplayed('//div[@id="result"]', WebDriver::BY_XPATH);
    }

    public function testWaitForGone()
    {
        $this->writeTest(<<<HTML
<div id="parent">
    <div id="result">Result</div>
</div>
<script type="text/javascript">
    setTimeout(function() {
        document.getElementById('parent').removeChild(document.getElementById('result'));
    }, 1000);
</script> 
HTML
        );
        $navigator = $this->getNavigator('InstructionNavigator');
        $startTime = microtime(true);
        $navigator->navigateTo([
           [InstructionNavigator::INSTRUCTION_WAIT_FOR_NOT_EXISTS, '//div[@id="result"]']
        ]);
        self::assertGreaterThan($startTime, microtime(true) + 0.5);
        $this->sleep('100ms');
        $this->assertElementNotExists('//div[@id="result"]', WebDriver::BY_XPATH);
    }

    protected function writeTest($html)
    {
        $this->filename = tempnam(sys_get_temp_dir(), '') . '.html';
        $content = <<<HTML
<html>
    <body>
        {$html}
    </body>
</html>
HTML;
        file_put_contents($this->filename, $content);
        $this->commandOpen('file://' . $this->filename);
    }

}