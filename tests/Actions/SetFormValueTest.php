<?php

namespace Tests\Magium\Actions;

use Magium\AbstractTestCase;
use Magium\Actions\SetFormValue;

class SetFormValueTest extends AbstractTestCase
{

    protected $filename;

    protected function tearDown()
    {
        parent::tearDown();
        if ($this->filename && file_exists($this->filename)) {
            unlink($this->filename);
        }
    }

    public function testTextWithLabel()
    {
        $this->writeFile(
            <<<HTML
        <label for="text">Text</label>
        <input type="text" id="text">
HTML
        );
        $this->commandOpen('file://' . $this->filename);
        $this->getAction(SetFormValue::ACTION)->set('Text', 'test value');
        self::assertEquals('test value', $this->byId('text')->getAttribute('value'));
    }


    public function testEmailWithLabel()
    {
        $this->writeFile(
            <<<HTML
        <label for="text">Text</label>
        <input type="email" id="text">
HTML
        );
        $this->commandOpen('file://' . $this->filename);
        $this->getAction(SetFormValue::ACTION)->set('Text', 'test value');
        self::assertEquals('test value', $this->byId('text')->getAttribute('value'));
    }


    public function testTextWithId()
    {
        $this->writeFile(
            <<<HTML
        <label for="text">Text</label>
        <input type="text" id="text">
HTML
        );
        $this->commandOpen('file://' . $this->filename);
        $this->getAction(SetFormValue::ACTION)->set('id:text', 'test value');
        self::assertEquals('test value', $this->byId('text')->getAttribute('value'));
    }

    public function testRadio()
    {
        $this->writeFile(
            <<<HTML
        <label for="radio">Radio</label>
        <input type="radio" id="radio">
HTML
        );
        $this->commandOpen('file://' . $this->filename);
        $this->getAction(SetFormValue::ACTION)->set('Radio', 1);
        self::assertTrue((bool)$this->byId('radio')->getAttribute('checked'));
    }

    public function testCheck()
    {
        $this->writeFile(
            <<<HTML
        <label for="check">Check</label>
        <input type="checkbox" id="check">
HTML
        );
        $this->commandOpen('file://' . $this->filename);
        $this->getAction(SetFormValue::ACTION)->set('Check', 1);
        self::assertTrue((bool)$this->byId('check')->getAttribute('checked'));
    }

    public function testUnCheck()
    {
        $this->writeFile(
            <<<HTML
        <label for="check">Check</label>
        <input type="checkbox" id="check" checked="checked">
HTML
        );
        $this->commandOpen('file://' . $this->filename);
        self::assertTrue((bool)$this->byId('check')->getAttribute('checked'));
        $this->getAction(SetFormValue::ACTION)->set('Check', 0);
        self::assertFalse((bool)$this->byId('check')->getAttribute('checked'));
    }

    protected function writeFile($text)
    {
        $html = sprintf('<html><body><form>%s</form></body>', $text);
        $this->filename = tempnam(sys_get_temp_dir(), 'form');
        file_put_contents($this->filename, $html);
    }

}