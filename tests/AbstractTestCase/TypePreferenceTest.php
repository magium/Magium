<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;
use Magium\Util\EmailGenerator\Generator;

class TypePreferenceTest extends AbstractTestCase
{

    public function testTypePreference()
    {
        $this->setTypePreference('Magium\Util\EmailGenerator\Generator', 'Tests\Magium\AbstractTestCase\Super');
        $obj = $this->get('Magium\Util\EmailGenerator\Generator');
        self::assertInstanceOf('Tests\Magium\AbstractTestCase\Super', $obj);
    }

}


class Super extends Generator
{

    public function doSomethingAwesome()
    {
        // You can use your imagination
    }

}