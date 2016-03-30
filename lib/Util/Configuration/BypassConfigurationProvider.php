<?php

namespace Magium\Util\Configuration;

class BypassConfigurationProvider implements ConfigurationProviderInterface
{
    
    public function configureObject(ConfigurableObjectInterface $obj)
    {
        return;
    }

}