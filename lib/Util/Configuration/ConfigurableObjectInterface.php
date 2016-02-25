<?php

namespace Magium\Util\Configuration;

interface ConfigurableObjectInterface
{

    public function get($key);

    public function set($key, $value);

    public function getDeclaredOptions();

}