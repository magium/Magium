<?php

namespace Magium\Util\TestCase;

use Magium\AbstractTestCase;

interface RegistrationCallbackInterface
{

    public function register(AbstractTestCase $testCase);

}