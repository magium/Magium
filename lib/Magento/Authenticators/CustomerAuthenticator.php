<?php

namespace Magium\Magento\Authenticators;

use Magium\AbstractConfigurableElement;

class CustomerAuthenticator extends AbstractConfigurableElement
{
    protected $account                        = 'test@example.com';
    protected $password                     = 'password';
    protected $loginUrl                    = 'http://localhost/customer/account/';
    
    public function getUrl()
    {
        return $this->loginUrl;
    }
    
    public function getAccount()
    {
        return $this->account;
    }

    public function getPassword()
    {
        return $this->password;
    }

}