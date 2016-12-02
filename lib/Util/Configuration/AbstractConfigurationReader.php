<?php

namespace Magium\Util\Configuration;

abstract class AbstractConfigurationReader
{

    protected function introspectClass(ConfigurableObjectInterface $object)
    {
        $reflectionClass = new \ReflectionClass($object);
        $classes = [
            $reflectionClass->getName()
        ];
        while (($class = $reflectionClass->getParentClass()) !== false) {
            $classes[] = $class->getName();
            $reflectionClass = $class;
        }

        foreach ($reflectionClass->getInterfaceNames() as $name){
            $interfaces[] = $name;
            $theseInterfaces = $this->recursivelyIntrospectInterface($name);
            $interfaces = array_merge($interfaces, $theseInterfaces);
            $classes = array_merge($classes, $interfaces);
        }
        $classes = array_unique($classes);
        $classes = array_reverse($classes);
        return $classes;
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
