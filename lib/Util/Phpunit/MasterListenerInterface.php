<?php

namespace Magium\Util\Phpunit;

use PHPUnit\Framework\TestListener;

interface MasterListenerInterface
{

    public function clear();

    public function addListener($listener);

}
