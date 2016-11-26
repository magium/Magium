<?php

namespace Tests\Magium\TestCase\Configurable;

class ExecuteMe
{

    public $noParams = false;
    public $withParam;

    public function withParam($param)
    {
        $this->withParam = $param;
    }

    public function noParams()
    {
        $this->noParams = true;
    }



}