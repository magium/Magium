<?php

namespace Magium\TestCase;

use Magium\AbstractTestCase;

class InitializerContainer
{

    protected $initializer;

    public function __construct(
        Initializer $initializer
    )
    {
        $this->initializer = $initializer;
    }

    public function initialize(AbstractTestCase $testCase)
    {
        $this->initializer->initialize($testCase);
    }

}
