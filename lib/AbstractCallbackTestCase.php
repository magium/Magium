<?php

namespace Magium;

abstract class AbstractCallbackTestCase extends AbstractTestCase
{

    public abstract function getCallback();

    public function testExecute()
    {
        $callback = $this->getCallback();
        if (!is_callable($callback)) {
            throw new NotFoundException('Callback not found');
        }
        return $callback();
    }

}