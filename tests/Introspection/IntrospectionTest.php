<?php

namespace Tests\Magium\Introspection;

use Magium\AbstractTestCase;
use Magium\Assertions\AssertInterface;
use Magium\Assertions\SelectorAssertionInterface;
use Magium\Assertions\Xpath\Displayed;
use Magium\Introspection\ComponentClass;
use Magium\Introspection\Introspector;

class IntrospectionTest extends AbstractTestCase
{

    public function testIntrospection()
    {
        $introspection = $this->get(Introspector::class);
        /* @var $introspection Introspector */
        $magiumPath = realpath(__DIR__ . '/../../lib');
        $result = $introspection->introspect($magiumPath);

        self::assertGreaterThan(0, count($result));
        self::assertArrayHasKey(Displayed::class, $result);
        $item = $result[Displayed::class];
        /* @var $item ComponentClass */
        self::assertInstanceOf(ComponentClass::class, $item);
        self::assertEquals(Displayed::class, $item->getClass());
        self::assertEquals(AssertInterface::class, $item->getBaseType());
        self::assertEquals(SelectorAssertionInterface::class, $item->getFunctionalType());
        self::assertGreaterThan(0, $item->getHierarchy());
    }

}
