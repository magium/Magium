<?php

namespace Magium\Util\Log;

interface LoggerAware
{
    public function setLogger(Logger $logger);
}