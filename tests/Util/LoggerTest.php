<?php

namespace Tests\Magium\Util;

use Magium\Util\Log\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{

    public function testLoggerTestRunIdIsCreatedAndRetainedAcrossInstances()
    {

        $logger = new Logger();
        $testRunId = $logger->getTestRunId();

        $logger = new Logger();
        $testRunIdFromNewLogger = $logger->getTestRunId();

        self::assertEquals($testRunId, $testRunIdFromNewLogger);

    }

}
