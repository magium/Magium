<?php

namespace Magium\TestCase\Configurable;

interface InstructionInterface
{

    public function getClassName();

    public function getMethod();

    public function getParams();

}