<?php

namespace Magium\Assertions\Element;

use Magium\Assertions\AbstractAssertion;

abstract class AbstractSelectorAssertion extends AbstractAssertion
{

    protected $selector;

    protected $by;

    /**
     * @param $selector
     * @return $this
     */

    public function setSelector($selector)
    {
        $this->selector = $selector;
        return $this;
    }

    /**
     * @param $by
     * @return $this
     */

    public function setBy($by)
    {
        $this->by = $by;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBy()
    {
        return $this->by;
    }

    /**
     * @return mixed
     */
    public function getSelector()
    {
        return $this->selector;
    }

}