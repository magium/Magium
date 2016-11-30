<?php

namespace Magium\Identities;

interface AddressInterface extends IdentityInterface
{
    public function getFirstName();
    public function getLastName();
    public function getCompany();
    public function getAddress();
    public function getAddress2();
    public function getCity();
    public function getRegionId();
    public function getPostCode();
    public function getCountryId();

    public function setFirstName($value);
    public function setLastName($value);
    public function setCompany($value);
    public function setAddress($value);
    public function setAddress2($value);
    public function setCity($value);
    public function setRegionId($value);
    public function setPostCode($value);
    public function setCountryId($value);
}
