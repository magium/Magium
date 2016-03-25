<?php

namespace Tests\Magium\Assertions;

use Magium\AbstractTestCase;
use Magium\Assertions\Element\IsBelow;
use Magium\Assertions\Element\IsRight;

class BelowBesideTest extends AbstractTestCase
{

    public function testIsBelow()
    {
        $this->writePage();
        $topElement = $this->byId('top-left');
        $bottomElement = $this->byId('bottom-left');
        $assertion = $this->getAssertion(IsBelow::ASSERTION);
        /* @var $assertion IsBelow */
        $assertion->setAboveElement($topElement);
        $assertion->setBelowElement($bottomElement);
        $this->getAssertionLogger()->execute($assertion);
    }

    public function testIsBelowFailsWhenWrong()
    {
        $this->setExpectedException('PHPUnit_Framework_AssertionFailedError');
        $this->writePage();
        $topElement = $this->byId('bottom-left');
        $bottomElement = $this->byId('top-left');
        $assertion = $this->getAssertion(IsBelow::ASSERTION);
        /* @var $assertion IsBelow */
        $assertion->setAboveElement($topElement);
        $assertion->setBelowElement($bottomElement);
        $this->getAssertionLogger()->execute($assertion);
    }

    public function testIsRight()
    {
        $this->writePage();
        $leftElement = $this->byId('top-left');
        $rightElement = $this->byId('top-right');

        $assertion = $this->getAssertion(IsRight::ASSERTION);
        /* @var $assertion IsRight */
        $assertion->setRightElement($rightElement);
        $assertion->setLeftElement($leftElement);
        $this->getAssertionLogger()->execute($assertion);
    }

    public function testIsRightFailsWhenWrong()
    {
        $this->setExpectedException('PHPUnit_Framework_AssertionFailedError');
        $this->writePage();
        $leftElement = $this->byId('top-right');
        $rightElement = $this->byId('top-left');

        $assertion = $this->getAssertion(IsRight::ASSERTION);
        /* @var $assertion IsRight */
        $assertion->setRightElement($rightElement);
        $assertion->setLeftElement($leftElement);
        $this->getAssertionLogger()->execute($assertion);
    }

    protected function writePage()
    {
        $script = <<<SCRIPT
<html>
<body>
<div id="top-right" style="float: right">Top Right</div>
<div id="top-left" >Top Left</div>
<div id="bottom-right" style="float: right" >Bottom Right</div>
<div id="bottom-left" >Bottom Left</div>
</body>
</html>
SCRIPT;
        $filename = tempnam(sys_get_temp_dir(), 'test') . '.html';
        $fh = fopen($filename, 'w+');
        fwrite($fh, $script);
        fclose($fh);
        $this->commandOpen('file://' . $filename);

        unlink($filename);
    }

}