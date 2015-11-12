<?php

namespace Magium\Magento\Themes\OnePageCheckout;

use Magium\AbstractConfigurableElement;

class ThemeConfiguration extends AbstractConfigurableElement
{

    /**
     * @var string The continue button when choosing the checkout type
     */

    protected $continueButtonXpath = '//button[@id="onepage-guest-register-button"]';

    /**
     * @var string The checkbox (typically) that sets the guest checkout
     */

    protected $guestCheckoutButtonXpath = '//input[@id="login:guest"]';

    /**
     * @var string The checkbox (typically) that sets the new customer checkout
     */

    protected $registerNewCustomerCheckoutButtonXpath = '//input[@id="login:register"]';

    protected $billingAddressDropdownXpath = '//select[@id="billing-address-select"]';

    protected $customerEmailInputXpath      = '//input[@id="login-email"]';
    protected $customerPasswordInputXpath   = '//input[@id="login-password"]';
    protected $customerButtonXpath          = '//button[@type="submit"]/descendant::span[.="Login"]';

    protected $billingFirstNameXpath      = '//input[@id="billing:firstname"]';
    protected $billingLastNameXpath       = '//input[@id="billing:lastname"]';
    protected $billingCompanyXpath        = '//input[@id="billing:company"]';
    protected $billingEmailAddressXpath   = '//input[@id="billing:email"]';
    protected $billingAddressXpath        = '//input[@id="billing:street1"]';
    protected $billingAddress2Xpath       = '//input[@id="billing:street2"]';
    protected $billingCityXpath           = '//input[@id="billing:city"]';
    /**
     * @var string The Xpath string for the region_id OPTION to click.  Must be sprintf() compatible
     */
    protected $billingRegionIdXpath       = '//select[@id="billing:region_id"]/descendant::option[@title="%s"]';
    protected $billingPostCodeXpath       = '//input[@id="billing:postcode"]';
    /**
     * @var string The Xpath string for the country OPTION to click.  Must be sprintf() compatible
     */
    protected $billingCountryIdXpath      = '//select[@id="billing:country_id"]/descendant::option[@value="%s"]';
    protected $billingTelephoneXpath      = '//input[@id="billing:telephone"]';
    protected $billingFaxXpath            = '//input[@id="billing:fax"]';
    protected $billingContinueButtonXpath = '//div[@id="billing-buttons-container"]/descendant::button[@title="Continue"]';
    protected $billingContinueCompletedXpath   = '//span[@id="billing-please-wait"]';


    protected $shippingFirstNameXpath      = '//input[@id="shipping:firstname"]';
    protected $shippingLastNameXpath       = '//input[@id="shipping:lastname"]';
    protected $shippingCompanyXpath        = '//input[@id="shipping:company"]';
    protected $shippingEmailAddressXpath   = '//input[@id="shipping:email"]';
    protected $shippingAddressXpath        = '//input[@id="shipping:street1"]';
    protected $shippingAddress2Xpath       = '//input[@id="shipping:street2"]';
    protected $shippingCityXpath           = '//input[@id="shipping:city"]';
    /**
     * @var string The Xpath string for the region_id OPTION to click.  Must be sprintf() compatible
     */
    protected $shippingRegionIdXpath       = '//select[@id="shipping:region_id"]/descendant::option[@title="%s"]';
    protected $shippingPostCodeXpath       = '//input[@id="shipping:postcode"]';
    /**
     * @var string The Xpath string for the country OPTION to click.  Must be sprintf() compatible
     */
    protected $shippingCountryIdXpath      = '//select[@id="shipping:country_id"]/descendant::option[@value="%s"]';
    protected $shippingTelephoneXpath      = '//input[@id="shipping:telephone"]';
    protected $shippingFaxXpath            = '//input[@id="shipping:fax"]';
    protected $shippingContinueButtonXpath = '//div[@id="shipping-buttons-container"]/descendant::button[@title="Continue"]';
    protected $shippingContinueCompletedXpath   = '//span[@id="shipping-please-wait"]';
    protected $shippingMethodContinueCompletedXpath   = '//span[@id="shipping-method-please-wait"]';

    protected $shippingMethodContinueButtonXpath = '//div[@id="shipping-method-buttons-container"]/descendant::button';
    protected $defaultShippingXpath             = '//input[@name="shipping_method"]';

    protected $paymentMethodContinueCompleteXpath = '//span[@id="payment-please-wait"]';

    protected $paymentMethodContinueButtonXpath = '//div[@id="payment-buttons-container"]/descendant::button';

    protected $placeOrderButtonXpath        = '//div[@id="review-buttons-container"]/descendant::button[@title="Place Order"]';

    protected $orderReceivedCompleteXpath = '//h1[.="Your order has been received."]';

    protected $shippingMethodFormXpath      = '//form[@id="co-shipping-method-form"]';

    protected $passwordInputXpath           = '//input[@id="billing:customer_password"]';
    protected $confirmPasswordInputXpath           = '//input[@id="billing:confirm_password"]';

    /**
     * @return string
     */
    public function getBillingAddressDropdownXpath()
    {
        return $this->billingAddressDropdownXpath;
    }

    /**
     * @return string
     */
    public function getPasswordInputXpath()
    {
        return $this->passwordInputXpath;
    }

    /**
     * @return string
     */
    public function getConfirmPasswordInputXpath()
    {
        return $this->confirmPasswordInputXpath;
    }



    /**
     * @return string
     */
    public function getRegisterNewCustomerCheckoutButtonXpath()
    {
        return $this->registerNewCustomerCheckoutButtonXpath;
    }

    /**
     * @return string
     */
    public function getCustomerEmailInputXpath()
    {
        return $this->customerEmailInputXpath;
    }

    /**
     * @return string
     */
    public function getCustomerPasswordInputXpath()
    {
        return $this->customerPasswordInputXpath;
    }

    /**
     * @return string
     */
    public function getCustomerButtonXpath()
    {
        return $this->customerButtonXpath;
    }



    /**
     * @return string
     */
    public function getShippingMethodFormXpath()
    {
        return $this->shippingMethodFormXpath;
    }

    /**
     * @return string
     */
    public function getOrderReceivedCompleteXpath()
    {
        return $this->orderReceivedCompleteXpath;
    }


    /**
     * @return string
     */
    public function getPaymentMethodContinueButtonXpath()
    {
        return $this->paymentMethodContinueButtonXpath;
    }

    /**
     * @return string
     */
    public function getShippingMethodContinueButtonXpath()
    {
        return $this->shippingMethodContinueButtonXpath;
    }

     /**
      * @return string
     */
    public function getPlaceOrderButtonXpath()
    {
        return $this->placeOrderButtonXpath;
    }


    /**
     * @return string
     */
    public function getPaymentMethodContinueCompleteXpath()
    {
        return $this->paymentMethodContinueCompleteXpath;
    }


    public function getDefaultShippingXpath()
    {
        return $this->defaultShippingXpath;
    }

    /**
     * @return string
     */
    public function getShippingMethodContinueCompletedXpath()
    {
        return $this->shippingMethodContinueCompletedXpath;
    }

    /**
     * @return string
     */
    public function getShippingFirstNameXpath()
    {
        return $this->shippingFirstNameXpath;
    }

    /**
     * @param string $shippinFirstNameXpath
     */
    public function setShippingFirstNameXpath($shippingFirstNameXpath)
    {
        $this->shippingFirstNameXpath = $shippingFirstNameXpath;
    }

    /**
     * @return string
     */
    public function getShippingLastNameXpath()
    {
        return $this->shippingLastNameXpath;
    }

    /**
     * @param string $shippingLastNameXpath
     */
    public function setShippingLastNameXpath($shippingLastNameXpath)
    {
        $this->shippingLastNameXpath = $shippingLastNameXpath;
    }

    /**
     * @return string
     */
    public function getShippingCompanyXpath()
    {
        return $this->shippingCompanyXpath;
    }

    /**
     * @param string $shippingCompanyXpath
     */
    public function setShippingCompanyXpath($shippingCompanyXpath)
    {
        $this->shippingCompanyXpath = $shippingCompanyXpath;
    }

    /**
     * @return string
     */
    public function getShippingEmailAddressXpath()
    {
        return $this->shippingEmailAddressXpath;
    }

    /**
     * @param string $shippingEmailAddressXpath
     */
    public function setShippingEmailAddressXpath($shippingEmailAddressXpath)
    {
        $this->shippingEmailAddressXpath = $shippingEmailAddressXpath;
    }

    /**
     * @return string
     */
    public function getShippingAddressXpath()
    {
        return $this->shippingAddressXpath;
    }

    /**
     * @param string $shippingAddressXpath
     */
    public function setShippingAddressXpath($shippingAddressXpath)
    {
        $this->shippingAddressXpath = $shippingAddressXpath;
    }

    /**
     * @return string
     */
    public function getShippingAddress2Xpath()
    {
        return $this->shippingAddress2Xpath;
    }

    /**
     * @param string $shippingAddress2Xpath
     */
    public function setShippingAddress2Xpath($shippingAddress2Xpath)
    {
        $this->shippingAddress2Xpath = $shippingAddress2Xpath;
    }

    /**
     * @return string
     */
    public function getShippingCityXpath()
    {
        return $this->shippingCityXpath;
    }

    /**
     * @param string $shippingCityXpath
     */
    public function setShippingCityXpath($shippingCityXpath)
    {
        $this->shippingCityXpath = $shippingCityXpath;
    }

    /**
     * @return string
     */
    public function getShippingRegionIdXpath()
    {
        return $this->shippingRegionIdXpath;
    }

    /**
     * @param string $shippingRegionIdXpath
     */
    public function setShippingRegionIdXpath($shippingRegionIdXpath)
    {
        $this->shippingRegionIdXpath = $shippingRegionIdXpath;
    }

    /**
     * @return string
     */
    public function getShippingPostCodeXpath()
    {
        return $this->shippingPostCodeXpath;
    }

    /**
     * @param string $shippingPostCodeXpath
     */
    public function setShippingPostCodeXpath($shippingPostCodeXpath)
    {
        $this->shippingPostCodeXpath = $shippingPostCodeXpath;
    }

    /**
     * @return string
     */
    public function getShippingCountryIdXpath()
    {
        return $this->shippingCountryIdXpath;
    }

    /**
     * @param string $shippingCountryIdXpath
     */
    public function setShippingCountryIdXpath($shippingCountryIdXpath)
    {
        $this->shippingCountryIdXpath = $shippingCountryIdXpath;
    }

    /**
     * @return string
     */
    public function getShippingTelephoneXpath()
    {
        return $this->shippingTelephoneXpath;
    }

    /**
     * @param string $shippingTelephoneXpath
     */
    public function setShippingTelephoneXpath($shippingTelephoneXpath)
    {
        $this->shippingTelephoneXpath = $shippingTelephoneXpath;
    }

    /**
     * @return string
     */
    public function getShippingFaxXpath()
    {
        return $this->shippingFaxXpath;
    }

    /**
     * @param string $shippingFaxXpath
     */
    public function setShippingFaxXpath($shippingFaxXpath)
    {
        $this->shippingFaxXpath = $shippingFaxXpath;
    }

    /**
     * @return string
     */
    public function getShippingContinueButtonXpath()
    {
        return $this->shippingContinueButtonXpath;
    }

    /**
     * @param string $shippingContinueButtonXpath
     */
    public function setShippingContinueButtonXpath($shippingContinueButtonXpath)
    {
        $this->shippingContinueButtonXpath = $shippingContinueButtonXpath;
    }

    /**
     * @return string
     */
    public function getShippingContinueCompletedXpath()
    {
        return $this->shippingContinueCompletedXpath;
    }

    /**
     * @param string $shippingContinueCompletedXpath
     */
    public function setShippingContinueCompletedXpath($shippingContinueCompletedXpath)
    {
        $this->shippingContinueCompletedXpath = $shippingContinueCompletedXpath;
    }

    public function getBillingContinueCompletedXpath()
    {
        return $this->billingContinueCompletedXpath;
    }

    public function getContinueButtonXpath()
    {
        return $this->continueButtonXpath;
    }

    public function getGuestCheckoutButtonXpath()
    {
        return $this->guestCheckoutButtonXpath;
    }

    /**
     * @return string
     */
    public function getBillingFirstNameXpath()
    {
        return $this->billingFirstNameXpath;
    }

    /**
     * @return string
     */
    public function getBillingLastNameXpath()
    {
        return $this->billingLastNameXpath;
    }

    /**
     * @return string
     */
    public function getBillingCompanyXpath()
    {
        return $this->billingCompanyXpath;
    }

    /**
     * @return string
     */
    public function getBillingEmailAddressXpath()
    {
        return $this->billingEmailAddressXpath;
    }

    /**
     * @return string
     */
    public function getBillingAddressXpath()
    {
        return $this->billingAddressXpath;
    }

    /**
     * @return string
     */
    public function getBillingAddress2Xpath()
    {
        return $this->billingAddress2Xpath;
    }

    /**
     * @return string
     */
    public function getBillingCityXpath()
    {
        return $this->billingCityXpath;
    }

    /**
     * @return string
     */
    public function getBillingRegionIdXpath()
    {
        return $this->billingRegionIdXpath;
    }

    /**
     * @return string
     */
    public function getBillingPostCodeXpath()
    {
        return $this->billingPostCodeXpath;
    }

    /**
     * @return string
     */
    public function getBillingCountryIdXpath()
    {
        return $this->billingCountryIdXpath;
    }

    /**
     * @return string
     */
    public function getBillingTelephoneXpath()
    {
        return $this->billingTelephoneXpath;
    }

    /**
     * @return string
     */
    public function getBillingFaxXpath()
    {
        return $this->billingFaxXpath;
    }

    /**
     * @return string
     */
    public function getBillingContinueButtonXpath()
    {
        return $this->billingContinueButtonXpath;
    }

}