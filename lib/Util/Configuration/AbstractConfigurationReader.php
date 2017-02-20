<?php

namespace Magium\Util\Configuration;

abstract class AbstractConfigurationReader
{

    protected $classes = [];

    protected function introspectClass(ConfigurableObjectInterface $object)
    {
        $this->classes = [];
        $originalReflectionClass = $reflectionClass = new \ReflectionClass($object);
        $this->addClass($reflectionClass->getName());
        while (($class = $reflectionClass->getParentClass()) !== false) {
            $this->addClass($class->getName());
            foreach ($class->getInterfaceNames() as $interface) {
                $this->addClass($interface);
                $reflectionInterface = new \ReflectionClass($interface);
                $interfaces = $reflectionInterface->getInterfaceNames();
                foreach ($interfaces as $interface) {
                    $this->addClass($interface);
                }
            }
            $reflectionClass = $class;
        }

        foreach ($originalReflectionClass->getInterfaceNames() as $name){
            $this->addClass($name);
            $interfaces[] = $name;
            $reflectionInterface = new \ReflectionClass($name);
            $theseInterfaces = $reflectionInterface->getInterfaceNames();
            $theseInterfaces = array_reverse($theseInterfaces);
            foreach ($theseInterfaces as $interface) {
                $this->addClass($interface);
            }
        }

        $this->classes = array_reverse($this->classes);
        return $this->classes;
    }

    protected function addClass($name)
    {
        if (!in_array($name, $this->classes)) {
            $this->classes[] = $name;
        }
    }

    protected function recursivelyIntrospectInterface($interfaceName)
    {
        $allInterfaces = [];
        $reflectionInterface = new \ReflectionClass($interfaceName);
        $interfaces = $reflectionInterface->getInterfaces();
        foreach ($interfaces as $interface) {
            $interfaceList = $this->recursivelyIntrospectInterface($interface);
            $allInterfaces = array_merge($allInterfaces, $interfaceList);
        }

        while (($parentInterface = $reflectionInterface->getParentClass()) != false) {
            $allInterfaces[] = $parentInterface->getName();
            $interfaceList = $this->recursivelyIntrospectInterface($parentInterface->getName());
            $allInterfaces = array_merge($allInterfaces, $interfaceList);
            $reflectionInterface = $parentInterface;
        }

        return $allInterfaces;
    }

    abstract public function configure(ConfigurableObjectInterface $object);

}
