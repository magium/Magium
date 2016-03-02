<?php

namespace Magium\Util\Configuration;

class BypassConfigurationProvider extends StandardConfigurationProvider
{
    
    public function configureObject(ConfigurableObjectInterface $obj)
    {
        return;
    }

}