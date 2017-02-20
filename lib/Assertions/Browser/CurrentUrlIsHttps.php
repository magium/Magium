<?php

namespace Magium\Assertions\Browser;

use Magium\Assertions\AbstractAssertion;
use PHPUnit\Framework\TestCase;

class CurrentUrlIsHttps extends AbstractAssertion
{

    const ASSERTION = 'Browser\CurrentUrlIsHttps';


    public function assert()
    {
        $url = $this->webDriver->getCurrentURL();
        $scheme = parse_url($url, PHP_URL_SCHEME);
        TestCase::assertEquals('https', $scheme);
    }

}
