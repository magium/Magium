<?php

namespace Magium\Magento\Actions\Checkout;

use Magium\AbstractConfigurableElement;

class PaymentInformation extends AbstractConfigurableElement
{

    protected $creditCardNumber;
    protected $expiryDate;
    protected $cvv;

    public function __construct($configurationFile = null)
    {
        /*
         * Note: payment information is placed in this class instead of the payment step because I wanted to make
         * the payment easily configurable which is why this class extends AbstractConfigurableElement.  The payment
         * step class does not extend AbstractConfigurableElement (because it needs the constructor for dependency
         * injection) and so this class is here so payment can be globally configured.
         *
         * This way if you want to handle your own credit card you only have to configure this class
        */
        $this->creditCardNumber = '4111111111111111';
        $this->expiryDate = '01/' . date('', time() + (60 * 60 * 24 * 365 * 5));  // January plus 5 years
        $this->cvv = '123';
        parent::__construct($configurationFile);
    }

    /**
     * @return string
     */
    public function getCreditCardNumber()
    {
        return $this->creditCardNumber;
    }

    /**
     * @param string $creditCardNumber
     */
    public function setCreditCardNumber($creditCardNumber)
    {
        $this->creditCardNumber = $creditCardNumber;
    }

    /**
     * @return string
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @param string $expiryDate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * @return string
     */
    public function getCvv()
    {
        return $this->cvv;
    }

    /**
     * @param string $cvv
     */
    public function setCvv($cvv)
    {
        $this->cvv = $cvv;
    }



}