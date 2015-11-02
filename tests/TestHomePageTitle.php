<?php

class TestHomePageTitle extends Magium\AbstractTestCase
{
    public function testTitleExists()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->assertElementExists('//title', self::BY_XPATH);
    }

}