<?php

namespace Magium\Introspection;

class ComponentClass
{

    protected $class;
    protected $baseType;
    protected $functionalType;
    protected $hierarchy;

    public function __construct($class, $baseType, $functionalType, array $hierarchy)
    {
        $this->class = $class;
        $this->baseType = $baseType;
        $this->functionalType = $functionalType;
        $this->hierarchy = $hierarchy;
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
    public function getHierarchy()
    {
        return $this->hierarchy;
    }


}
