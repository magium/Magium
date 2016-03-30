<?php

namespace Magium\Util\Configuration;

interface ConfigurationProviderInterface
{

    public function configureObject(ConfigurableObjectInterface $object);

}