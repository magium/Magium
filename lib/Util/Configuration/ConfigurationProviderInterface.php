<?php

namespace Magium\Util\Configuration;

use Zend\Di\Di;

interface ConfigurationProviderInterface
{

    public function configureObject(ConfigurableObjectInterface $object);

    public function configureDi(Di $di);

}