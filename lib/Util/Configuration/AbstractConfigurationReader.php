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
            $classes[] = $name;
        }

        $classes = array_reverse($classes);
        return $classes;
    }

    abstract public function configure(ConfigurableObjectInterface $object);

}
