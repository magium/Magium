<?php

namespace Magium\Assertions;

use Magium\AbstractTestCase;
use Magium\Assertions\Element\AbstractSelectorAssertion;
use Magium\Util\Log\Logger;

class LoggingAssertionExecutor
{

    const ASSERTION = 'LoggingAssertionExecutor';

    protected $logger;
    protected $testCase;

    public function __construct(
        Logger $logger,
        AbstractTestCase $testCase
    )
    {
        $this->logger = $logger;
        $this->testCase = $testCase;
    }

    public function execute(AbstractAssertion $assertion, array $extra = [])
    {

        if ($assertion instanceof AbstractSelectorAssertion) {
            $extra = array_merge($extra, [
                'selector'  => $assertion->getSelector(),
                'by'        => $assertion->getBy()
            ]);
        }
        try {
            $assertion->assert();
            $this->logger->logAssertionSuccess( $assertion, $extra );
        } catch (\Exception $e) {
            $this->logger->logAssertionFailure($e, $assertion, $extra);
            $this->testCase->fail($e->getMessage());
        }
    }

}