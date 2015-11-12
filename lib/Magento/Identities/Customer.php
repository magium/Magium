<?php

namespace Magium\Magento\Identities;

class Customer extends AbstractEntity
{
    protected $emailAddress          = 'test@example.com';
    protected $password              = 'password';

    protected $billingFirstName        = 'Kevin';
    protected $billingLastName         = 'Schroeder';
    protected $billingCompany          = '';
    protected $billingAddress          = '10451 Jefferson Blvd';
    protected $billingAddress2         = '';
    protected $billingCity             = 'Culver City';
    protected $billingRegionId         = 'California';
    protected $billingPostCode         = '90232';
    protected $billingCountryId        = 'US';
    protected $billingTelephone        = '123-123-1234';
    protected $billingFax              = '';

    protected $shippingFirstName        = 'Kevin';
    protected $shippingLastName         = 'Schroeder';
    protected $shippingCompany          = '';
    protected $shippingAddress          = '10451 Jefferson Blvd';
    protected $shippingAddress2         = '';
    protected $shippingCity             = 'Culver City';
    protected $shippingRegionId         = 'California';
    protected $shippingPostCode         = '90232';
    protected $shippingCountryId        = 'US';
    protected $shippingTelephone        = '123-123-1234';
    protected $shippingFax              = '';

    protected $uniqueEmailAddressGenerated = false;

    public function generateUniqueEmailAddress($domain = 'example.com')
    {
        $this->uniqueEmailAddressGenerated = true;
        $rand = uniqid(openssl_random_pseudo_bytes(10));
        $encoded = base64_encode($rand);
        $username = preg_replace('/\W/', '', $encoded);

        $this->emailAddress = $username . '@' . $domain;
        return $this->emailAddress;
    }

    /**
     * @return boolean
     */
    public function isUniqueEmailAddressGenerated()
    {
        return $this->uniqueEmailAddressGenerated;
    }

    /**
     * @param boolean $uniqueEmailAddressGenerated
     */
    public function setUniqueEmailAddressGenerated($uniqueEmailAddressGenerated)
    {
        $this->uniqueEmailAddressGenerated = $uniqueEmailAddressGenerated;
    }



    /**
     * @return string
     */
    public function getBillingFirstName()
    {
        return $this->billingFirstName;
    }

    /**
     * @param string $billingFirstName
     */
    public function setBillingFirstName($billingFirstName)
    {
        $this->billingFirstName = $billingFirstName;
    }

    /**
     * @return string
     */
    public function getBillingLastName()
    {
        return $this->billingLastName;
    }

    /**
     * @param string $billingFastName
     */
    public function setBillingLastName($billingLastName)
    {
        $this->billingLastName = $billingLastName;
    }

    /**
     * @return string
     */
    public function getBillingCompany()
    {
        return $this->billingCompany;
    }

    /**
     * @param string $billingCompany
     */
    public function setBillingCompany($billingCompany)
    {
        $this->billingCompany = $billingCompany;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param string $billingAddress
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }

    /**
     * @return string
     */
    public function getBillingAddress2()
    {
        return $this->billingAddress2;
    }

    /**
     * @param string $billingAddress2
     */
    public function setBillingAddress2($billingAddress2)
    {
        $this->billingAddress2 = $billingAddress2;
    }

    /**
     * @return string
     */
    public function getBillingCity()
    {
        return $this->billingCity;
    }

    /**
     * @param string $billingCity
     */
    public function setBillingCity($billingCity)
    {
        $this->billingCity = $billingCity;
    }

    /**
     * @return string
     */
    public function getBillingRegionId()
    {
        return $this->billingRegionId;
    }

    /**
     * @param string $billingRegionId
     */
    public function setBillingRegionId($billingRegionId)
    {
        $this->billingRegionId = $billingRegionId;
    }

    /**
     * @return string
     */
    public function getBillingPostCode()
    {
        return $this->billingPostCode;
    }

    /**
     * @param string $billingPostCode
     */
    public function setBillingPostCode($billingPostCode)
    {
        $this->billingPostCode = $billingPostCode;
    }

    /**
     * @return string
     */
    public function getBillingCountryId()
    {
        return $this->billingCountryId;
    }

    /**
     * @param string $billingCountryId
     */
    public function setBillingCountryId($billingCountryId)
    {
        $this->billingCountryId = $billingCountryId;
    }

    /**
     * @return string
     */
    public function getBillingTelephone()
    {
        return $this->billingTelephone;
    }

    /**
     * @param string $billingTelephone
     */
    public function setBillingTelephone($billingTelephone)
    {
        $this->billingTelephone = $billingTelephone;
    }

    /**
     * @return string
     */
    public function getBillingFax()
    {
        return $this->billingFax;
    }

    /**
     * @param string $billingFax
     */
    public function setBillingFax($billingFax)
    {
        $this->billingFax = $billingFax;
    }

    /**
     * @return string
     */
    public function getShippingFirstName()
    {
        return $this->shippingFirstName;
    }

    /**
     * @param string $shippingFirstName
     */
    public function setShippingFirstName($shippingFirstName)
    {
        $this->shippingFirstName = $shippingFirstName;
    }

    /**
     * @return string
     */
    public function getShippingLastName()
    {
        return $this->shippingLastName;
    }

    /**
     * @param string $shippingLastName
     */
    public function setShippingLastName($shippingLastName)
    {
        $this->shippingLastName = $shippingLastName;
    }

    /**
     * @return string
     */
    public function getShippingCompany()
    {
        return $this->shippingCompany;
    }

    /**
     * @param string $shippingCompany
     */
    public function setShippingCompany($shippingCompany)
    {
        $this->shippingCompany = $shippingCompany;
    }


    /**
     * @return string
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @param string $shippingAddress
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }

    /**
     * @return string
     */
    public function getShippingAddress2()
    {
        return $this->shippingAddress2;
    }

    /**
     * @param string $shippingAddress2
     */
    public function setShippingAddress2($shippingAddress2)
    {
        $this->shippingAddress2 = $shippingAddress2;
    }

    /**
     * @return string
     */
    public function getShippingCity()
    {
        return $this->shippingCity;
    }

    /**
     * @param string $shippingCity
     */
    public function setShippingCity($shippingCity)
    {
        $this->shippingCity = $shippingCity;
    }

    /**
     * @return string
     */
    public function getShippingRegionId()
    {
        return $this->shippingRegionId;
    }

    /**
     * @param string $shippingRegionId
     */
    public function setShippingRegionId($shippingRegionId)
    {
        $this->shippingRegionId = $shippingRegionId;
    }

    /**
     * @return string
     */
    public function getShippingPostCode()
    {
        return $this->shippingPostCode;
    }

    /**
     * @param string $shippingPostCode
     */
    public function setShippingPostCode($shippingPostCode)
    {
        $this->shippingPostCode = $shippingPostCode;
    }

    /**
     * @return string
     */
    public function getShippingCountryId()
    {
        return $this->shippingCountryId;
    }

    /**
     * @param string $shippingCountryId
     */
    public function setShippingCountryId($shippingCountryId)
    {
        $this->shippingCountryId = $shippingCountryId;
    }

    /**
     * @return string
     */
    public function getShippingTelephone()
    {
        return $this->shippingTelephone;
    }

    /**
     * @param string $shippingTelephone
     */
    public function setShippingTelephone($shippingTelephone)
    {
        $this->shippingTelephone = $shippingTelephone;
    }

    /**
     * @return string
     */
    public function getShippingFax()
    {
        return $this->shippingFax;
    }

    /**
     * @param string $shippingFax
     */
    public function setShippingFax($shippingFax)
    {
        $this->shippingFax = $shippingFax;
    }



}