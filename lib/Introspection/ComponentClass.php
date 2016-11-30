<?php

namespace Magium\Introspection;

class ComponentClass
{

    protected $class;
    protected $baseType;
    protected $functionalType;
    protected $heirarchy;

    public function __construct($class, $baseType, $functionalType, array $heirarchy)
    {
        $this->class = $class;
        $this->baseType = $baseType;
        $this->functionalType = $functionalType;
        $this->heirarchy = $heirarchy;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return mixed
     */
    public function getBaseType()
    {
        return $this->baseType;
    }

    /**
     * @return mixed
     */
    public function getFunctionalType()
    {
        return $this->functionalType;
    }

    /**
     * @return array
     */
    public function getHeirarchy(): array
    {
        return $this->heirarchy;
    }


}
