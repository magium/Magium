<?php

namespace Magium\Magento\Identities;

use Magium\AbstractConfigurableElement;

abstract class AbstractEntity extends AbstractConfigurableElement
{
    protected $password = '123123qa';

    public function getPassword()
    {
        return $this->password;
    }
}