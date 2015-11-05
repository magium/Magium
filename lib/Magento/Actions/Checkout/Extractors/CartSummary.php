<?php

namespace Magium\Magento\Actions\Checkout\Extractors;

use Magium\Magento\Actions\Checkout\Steps\StepInterface;
use Magium\Magento\Extractors\AbstractExtractor;

class CartSummary extends AbstractExtractor implements StepInterface
{


    public function extract()
    {
        // @TODO build out this functionality.  Not necessary the moment
    }

    public function execute()
    {
        $this->extract();
    }

}