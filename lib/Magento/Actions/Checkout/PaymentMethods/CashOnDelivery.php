<?php

namespace Magium\Magento\Actions\Checkout\PaymentMethods;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\WebDriver\WebDriver;

class CashOnDelivery implements PaymentMethodInterface
{

    protected $webDriver;
    protected $testCase;

    public function __construct(
        WebDriver                   $webDriver,
        AbstractMagentoTestCase     $testCase
    ) {
        $this->webDriver    = $webDriver;
        $this->testCase     = $testCase;
    }

    public function pay($requirePayment)
    {
        if ($requirePayment) {
            $this->testCase->assertElementExists('p_method_cashondelivery');
        }

        if ($this->webDriver->elementDisplayed('p_method_cashondelivery')) {
            $this->webDriver->byId('p_method_cashondelivery')->click();
        }
    }
}