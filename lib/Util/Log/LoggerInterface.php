<?php

namespace Magium\Util\Log;

use Magium\Assertions\AbstractAssertion;

interface LoggerInterface extends \Zend\Log\LoggerInterface
{

    const CHARACTERISTIC_BROWSER = 'browser';
    const CHARACTERISTIC_BROWSER_VERSION = 'browser-version';
    const CHARACTERISTIC_OPERATING_SYSTEM = 'operating-system';
    const CHARACTERISTIC_THEME = 'theme';

    const STATUS_PASSED = 'passed';
    const STATUS_FAILED = 'failed';
    const STATUS_SKIPPED = 'skipped';
    const STATUS_INCOMPLETE = 'incomplete';
    const STATUS_RISKY = 'risky';

    const NAME_DEFAULT = 'unknown';

    public function logStep($stepId);

    public function logAssertionSuccess(AbstractAssertion $assertion, array $extra);

    public function logAssertionFailure(\Exception $e, AbstractAssertion $assertion, array $extra);

    public function addCharacteristic($type, $value);

    public function addWriter($writer, $priority = 1, array $options = null);
}
