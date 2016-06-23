<?php

namespace Tests\Magium\Assertions;

use Magium\AbstractTestCase;
use Magium\Assertions\Browser\LogEmpty;

class BrowserLogTest extends AbstractTestCase
{

    public function testLogIsEmpty()
    {
        $file = tempnam(sys_get_temp_dir(), 'cached').'.html';
        file_put_contents($file, <<<HTML
<html><body><p>This is a wonderful test!</p></body></html>
HTML
        );
        $this->commandOpen('file://' . $file);

        $assertion = $this->getAssertion(LogEmpty::ASSERTION);
        /* @var $assertion LogEmpty */

        $assertion->assert();
    }

    public function testLogIsNotEmpty()
    {
        $file = tempnam(sys_get_temp_dir(), 'cached').'.html';
        file_put_contents($file, <<<HTML
<htm l><body><p>This is a wonderful test!</p><img src="boogers"></body></html>
HTML
        );
        $this->commandOpen('file://' . $file);

        $assertion = $this->getAssertion(LogEmpty::ASSERTION);
        /* @var $assertion LogEmpty */
        $this->setExpectedException('PHPUnit_Framework_AssertionFailedError');
        $assertion->assert();
    }

}