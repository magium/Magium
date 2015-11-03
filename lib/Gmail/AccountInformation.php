<?php

namespace Magium\Gmail;

use Magium\AbstractConfigurableElement;

class AccountInformation extends AbstractConfigurableElement
{

    protected $emailAddress;
    protected $password;

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function getPassword()
    {
        return $this->password;
    }


}