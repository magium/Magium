<?php

namespace Tests\Magium\Assertions;

use Magium\AbstractTestCase;
use Magium\Assertions\Browser\TitleContains;
use Magium\Assertions\Browser\TitleEndsWith;
use Magium\Assertions\Browser\TitleEquals;
use Magium\Assertions\Browser\TitleNotContains;
use Magium\Assertions\Browser\TitleNotEquals;
use Magium\Assertions\Browser\TitleStartsWith;


class TitleAssertionTest extends AbstractTestCase
{

    protected $_filename;

    public function testTitleEquals()
    {
        $assertion = $this->getAssertion(TitleEquals::ASSERTION);
        $assertion->assertSelector('Yelling Children');
    }

    public function testTitleNotEquals()
    {
        $assertion = $this->getAssertion(TitleNotEquals::ASSERTION);
        $assertion->assertSelector('Quiet Children');
    }

    public function testTitleStartsWith()
    {
        $assertion = $this->getAssertion(TitleStartsWith::ASSERTION);
        $assertion->assertSelector('Yelling');
    }

    public function testTitleEndsWith()
    {
        $assertion = $this->getAssertion(TitleEndsWith::ASSERTION);
        $assertion->assertSelector('Children');
    }

    public function testTitleContains()
    {
        $assertion = $this->getAssertion(TitleContains::ASSERTION);
        $assertion->assertSelector('Children');
    }

    public function testTitleNotContains()
    {
        $assertion = $this->getAssertion(TitleNotContains::ASSERTION);
        $assertion->assertSelector('Silence');
    }

    protected function setUp()
    {
        parent::setUp();
        $content = <<<HTML
<html>
<head>
<title> Yelling Children </title> <!-- spaces intentional -->
</head>
<body>
</body>
</html>
HTML;
        $this->_filename = tempnam(sys_get_temp_dir(), 'test');
        file_put_contents($this->_filename, $content);
        $this->commandOpen('file://' . $this->_filename);
    }

    protected function tearDown()
    {
        if ($this->_filename && file_exists($this->_filename)) {
            unlink($this->_filename);
        }
        parent::tearDown();
    }

}
