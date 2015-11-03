<?php

namespace Tests\Gmail;

use Magium\AbstractTestCase;

class LoginTest extends AbstractTestCase
{
    public function testGmailLogin()
    {
        $login = $this->get('Magium\Gmail\Login');
        $login->login();
        $this->assertElementExists('//a[@title="Inbox"]', self::BY_XPATH);
    }
}