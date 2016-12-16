<?php

namespace Magium\TestCase\Configurable;

class GenericInstruction implements InstructionInterface
{
    protected $className;
    protected $method;
    protected $params;

    /**
     * GenericInstruction constructor.
     * @param $className
     * @param $method
     * @param $params
     */
    public function __construct($className, $method, array $params = null)
    {
        $this->className = $className;
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }



}
