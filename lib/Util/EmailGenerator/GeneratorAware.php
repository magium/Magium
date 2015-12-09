<?php

namespace Magium\Util\EmailGenerator;

interface GeneratorAware
{

    public function setGenerator(Generator $generator);

}