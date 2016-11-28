<?php

namespace Tests\Magium\TestCase\Configurable;

class ExecuteMe
{

    public $noParams = false;
    public $withParam;

    public function withParam($param)
    {
        $this->withParam = $param;
        return $param;
    }

    public function noParams()
    {
        $this->noParams = true;
    }

    public function multiply($p1, $p2)
    {
        return $p1 * $p2;
    }

    public function getMe()
    {
        return $this;
    }

    public function toString()
    {
        return (string)$this;
    }

    public function __toString()
    {
        return 'to string';
    }


}