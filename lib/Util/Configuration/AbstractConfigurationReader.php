<?php

namespace Magium\Util\Configuration;

abstract class AbstractConfigurationReader
{

    protected function introspectClass(ConfigurableObjectInterface $object)
    {
        $originalReflectionClass = $reflectionClass = new \ReflectionClass($object);
        $classes = [
            $reflectionClass->getName()
        ];
        while (($class = $reflectionClass->getParentClass()) !== false) {
            $classes[] = $class->getName();
            foreach ($class->getInterfaceNames() as $interface) {
                $reflectionInterface = new \ReflectionClass($interface);
                $interfaces = $reflectionInterface->getInterfaceNames();
                $classes = array_merge($interfaces, $classes);
            }
            $reflectionClass = $class;
        }

        foreach ($originalReflectionClass->getInterfaceNames() as $name){
            $interfaces[] = $name;
            $reflectionInterface = new \ReflectionClass($name);
            $theseInterfaces = $reflectionInterface->getInterfaceNames();
            $theseInterfaces = array_reverse($theseInterfaces);

            $interfaces = array_merge($theseInterfaces, $interfaces);
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
