<?php

namespace Magium\Assertions;

use Magium\Assertions\Element\AbstractSelectorAssertion;
use Magium\Util\Log\Logger;

class LoggingAssertionExecutor
{

    const ASSERTION = 'LoggingAssertionExecutor';

    protected $logger;

    public function __construct(
        Logger $logger

    )
    {
        $this->logger = $logger;
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
            $this->logger->logAssertionFailure($assertion, $extra);
            throw $e;
        }
    }

}