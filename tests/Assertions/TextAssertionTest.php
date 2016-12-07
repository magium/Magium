<?php

namespace Tests\Magium\Assertions;

use Magium\AbstractTestCase;
use Magium\Assertions\Text\Displayed;
use Magium\Assertions\Text\Exists;
use Magium\Assertions\Text\NotDisplayed;
use Magium\Assertions\Text\NotExists;

class TextAssertionTest extends AbstractTestCase
{

    protected $_filename;

    public function testTextExists()
    {
        $exists = $this->getAssertion(Exists::ASSERTION);
        /* @var $exists \Magium\Assertions\Text\Exists */
        $exists->assertSelector('This is displayed');
    }

    public function testTextNotExists()
    {
        $exists = $this->getAssertion(NotExists::ASSERTION);
        /* @var $exists \Magium\Assertions\Text\NotExists */
        $exists->assertSelector('No text');
    }

    public function testNotDisplayed()
    {
        $exists = $this->getAssertion(NotDisplayed::ASSERTION);
        /* @var $exists \Magium\Assertions\Text\NotDisplayed */
        $exists->assertSelector('This is not displayed');
    }

    public function testDisplayed()
    {
        $exists = $this->getAssertion(Displayed::ASSERTION);
        /* @var $exists \Magium\Assertions\Text\Displayed */
        $exists->assertSelector('This is displayed');
    }

    protected function setUp()
    {
        parent::setUp();
        $content = <<<HTML
<html>
<body>
<div>This is displayed</div>
<div style="display: none; ">This is not displayed</div>
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
