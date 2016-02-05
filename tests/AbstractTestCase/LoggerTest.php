<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;

class LoggerTest extends AbstractTestCase
{

    public function testLoggerFindLoggedById()
    {
        $this->writeTest();
        $logBuilder = $this->getMockBuilder('Zend\Log\Writer\WriterInterface');
        $logBuilder->setMethods(['addFilter', 'setFormatter', 'write', 'shutdown']);

        $logEvent = null;
        $adapter = $logBuilder->getMock();
        $adapter->method('write')->with(
            $this->callback(function() use (&$logEvent) {
                if ($logEvent === null) {
                    $log = func_get_args();
                    $logEvent = array_shift($log);
                }
                return true;
            })
        );
        $this->getLogger()->addWriter($adapter);

        $this->byId('button');
        $this->performAssertions($logEvent);
        self::assertArrayHasKey('by', $logEvent['extra']);
        self::assertArrayHasKey('selector', $logEvent['extra']);
        self::assertEquals('webdriver-activity', $logEvent['extra']['type']);
        self::assertEquals('select', $logEvent['extra']['activity']);
        self::assertEquals('id', $logEvent['extra']['by']);
        self::assertEquals('button', $logEvent['extra']['selector']);

    }

    public function testLoggerFindLoggedByXpath()
    {
        $this->writeTest();
        $logBuilder = $this->getMockBuilder('Zend\Log\Writer\WriterInterface');
        $logBuilder->setMethods(['addFilter', 'setFormatter', 'write', 'shutdown']);

        $logEvent = null;
        $adapter = $logBuilder->getMock();
        $adapter->method('write')->with(
            $this->callback(function() use (&$logEvent) {
                if ($logEvent === null) {
                    $log = func_get_args();
                    $logEvent = array_shift($log);
                }
                return true;
            })
        );
        $this->getLogger()->addWriter($adapter);

        $this->byXpath('//button');
        $this->performAssertions($logEvent);
        self::assertArrayHasKey('by', $logEvent['extra']);
        self::assertArrayHasKey('selector', $logEvent['extra']);
        self::assertEquals('webdriver-activity', $logEvent['extra']['type']);
        self::assertEquals('select', $logEvent['extra']['activity']);
        self::assertEquals('xpath', $logEvent['extra']['by']);
        self::assertEquals('//button', $logEvent['extra']['selector']);

    }
    public function testLoggerClickIsLogged()
    {
        $this->writeTest();
        $logBuilder = $this->getMockBuilder('Zend\Log\Writer\WriterInterface');
        $logBuilder->setMethods(['addFilter', 'setFormatter', 'write', 'shutdown']);

        $logEvent = 0;
        $adapter = $logBuilder->getMock();
        $adapter->method('write')->with(
            $this->callback(function() use (&$logEvent) {

                if ($logEvent === 2) {
                    // We want the second action
                    $log = func_get_args();
                    $logEvent = array_shift($log);
                } else if (is_numeric($logEvent)) {
                    $logEvent++;
                }
                return true;
            })
        );
        $this->getLogger()->addWriter($adapter);

        $this->byId('button')->click();
        $this->performAssertions($logEvent);
        self::assertArrayHasKey('activity', $logEvent['extra']);
        self::assertArrayHasKey('command', $logEvent['extra']);
        self::assertEquals('webdriver-activity', $logEvent['extra']['type']);
        self::assertEquals('action', $logEvent['extra']['activity']);
        self::assertEquals('clickElement', $logEvent['extra']['command']);

    }

    protected function performAssertions($logEvent)
    {
        self::assertNotNull($logEvent);
        self::assertArrayHasKey('message', $logEvent);
        self::assertArrayHasKey('extra', $logEvent);
        self::assertArrayHasKey('type', $logEvent['extra']);
        self::assertArrayHasKey('activity', $logEvent['extra']);
    }

    protected function writeTest()
    {
        $html = <<<HTML
<html>
<body>
<button id="button">Click Me</button>
</body>
</html>
HTML;
        $filename = tempnam(sys_get_temp_dir(), '') . '.html';
        $fh = fopen($filename, 'w+');
        fwrite($fh, $html);
        fclose($fh);

        $this->commandOpen('file://' . $filename);
        unlink($filename);

    }
}