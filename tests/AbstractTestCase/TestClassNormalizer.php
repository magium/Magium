<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;

class TestClassNormalizer extends AbstractTestCase
{
    public function testClassNormalizer()
    {
        $object = $this->get('Magium/Util/EmailGenerator/Generator');
        self::assertInstanceOf('Magium\Util\EmailGenerator\Generator', $object);
    }
}