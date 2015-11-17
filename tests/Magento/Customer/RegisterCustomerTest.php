<?php

namespace Tests\Magento\Customer;

use Magium\Magento\AbstractMagentoTestCase;

class RegisterCustomerTest extends AbstractMagentoTestCase
{

    public function testNavigateToLogin()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());

        $this->getIdentity()->generateUniqueEmailAddress();

        // Yes, yes.   I know I'm technically testing two bits of functionality here.
        $this->getAction('Customer\Register')->register();
        $this->getAction('Customer\Logout')->logout();
    }
    

}