<?php

namespace Magium\Util\Configuration\ConfigurationCollector;

class Property
{

    protected $name;
    protected $description;
    protected $defaultValue;

    public function __construct(
        $name,
        $defaultValue,
        $description = null
    )
    {
        $this->name = $name;
        $this->defaultValue = $defaultValue;
        $this->description = $description;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }



}