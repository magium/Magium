<?php

namespace Magium\Util\Configuration;

use Zend\Di\Di;

class BypassConfigurationProvider implements ConfigurationProviderInterface
{
    
    public function configureObject(ConfigurableObjectInterface $obj)
    {
        return;
    }

    public function configureDi(Di $di)
    {
        return;
    }

}