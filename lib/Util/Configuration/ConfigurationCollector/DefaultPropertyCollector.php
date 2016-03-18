<?php

namespace Magium\Util\Configuration\ConfigurationCollector;

use Magium\AbstractConfigurableElement;

class DefaultPropertyCollector
{

    /**
     * @param AbstractConfigurableElement|string $element
     * @return Property[]
     */

    public function extract(
        $element
    )
    {
        $return = [];
        $reflection = new \ReflectionClass($element);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        $defaultValues = $reflection->getDefaultProperties();
        foreach ($properties as $property) {
            $value = '<empty>';
            if (isset($defaultValues[$property->getName()])) {
                $value = $defaultValues[$property->getName()];
            }
            if ($property->getDocComment()) {
                $option = new Property(
                    $property->getName(),
                    $value,
                    $property->getDocComment()
                );
            } else {
                $option = new Property(
                    $property->getName(),
                    $value
                );
            }
            $return[] = $option;
        }
        return $return;
    }

}