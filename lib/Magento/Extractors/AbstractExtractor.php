<?php

namespace Magium\Magento\Extractors;

abstract class AbstractExtractor
{

    protected $values = [];

    public function getValue($id)
    {
        if (isset($this->values[$id])) {
            return $this->values[$id];
        }
        return null;
    }

    abstract function extract();
}