<?php

namespace Magium\Magento\Actions\Checkout\Extractors;

use Magium\AbstractTestCase;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Checkout\Steps\StepInterface;
use Magium\Magento\Extractors\AbstractExtractor;
use Magium\WebDriver\WebDriver;

class OrderId extends AbstractExtractor implements StepInterface
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

    public function getOrderId()
    {
        return $this->getValue('order-id');
    }

    public function extract()
    {
        $this->testCase->assertElementDisplayed('//p[contains(., "Your order # is:")]', AbstractTestCase::BY_XPATH);
        $element = $this->webDriver->byXpath('//p[contains(., "Your order # is:")]');
        $text = $element->getText();
        $orderId = preg_replace('/\D/', '', $text);
        $this->values['order-id'] = $orderId;
        return true;
    }

    public function execute()
    {
        $this->extract();
    }

}