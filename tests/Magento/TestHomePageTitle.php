<?php

namespace Tests\Magento;

use Magium\Magento\AbstractMagentoTestCase;

class TestHomePageTitle extends AbstractMagentoTestCase
{
    public function testTitleExists()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->assertElementExists('//title', self::BY_XPATH);
    }

    public function testBadTitleNotExists()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->assertElementNotExists('//title[.="boogers"]', self::BY_XPATH);
    }

}