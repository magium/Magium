<?php

namespace Magium\Util\Phpunit;

interface MasterListenerAware
{
    public function setMasterListener(MasterListener $listener);
}