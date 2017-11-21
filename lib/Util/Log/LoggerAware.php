<?php

namespace Magium\Util\Log;

interface LoggerAware
{
    public function setLogger(LoggerInterface $logger);
}
