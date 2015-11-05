<?php

namespace Tests\Magento\Customer;

use Magium\AbstractTestCase;
use Magium\Magento\Identities\CustomerIdentity;

class CustomerAuthenticationTest extends \PHPUnit_Framework_TestCase
{
    public function testGeneratedEmailAddressUsesWordCharsOnly()
    {
        $customer = new CustomerIdentity();
        $emailAddress = $customer->generateUniqueEmailAddress();
        $parts = explode('@', $emailAddress);
        self::assertEquals(0, preg_match('/\W/', $parts[0]));
        self::assertEquals($emailAddress, $customer->getEmailAddress());
    }

    public function testGeneratedEmailAddressUsesSpecifiedDomain()
    {
        $customer = new CustomerIdentity();
        $emailAddress = $customer->generateUniqueEmailAddress('eschrade.com');
        $parts = explode('@', $emailAddress);
        self::assertEquals('eschrade.com', $parts[1]);
        self::assertEquals($emailAddress, $customer->getEmailAddress());
    }
}