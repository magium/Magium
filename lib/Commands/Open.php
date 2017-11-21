<?php

namespace Magium\Commands;

use Magium\Util\Log\LoggerInterface;
use Magium\WebDriver\WebDriver;


class Open implements CommandInterface
{
    protected $webdriver;
    protected $logger;
    
    public function __construct(
        WebDriver $webdriver,
        LoggerInterface    $log
    )
    {
        $this->webdriver = $webdriver;
        $this->logger = $log;
    }
    
    public function open($url)
    {

        $startTime = microtime(true);
        $this->logger->info(
            sprintf('Opening URL %s', $url),
            [
                'activity' => 'open',
                'url' => $url,
                'start_time' => $startTime
            ]
        );
        $this->webdriver->get($url);
        $endTime = microtime(true);
        $this->logger->info(
            sprintf('URL %s has been opened', $url),
            [
                'activity' => 'open',
                'url' => $url,
                'end_time' => $endTime,
                'elapsed' => ($endTime - $startTime)
            ]
        );
        // This is done because firefox does not always scroll the admin menus into view.
        // TODO - find a better way to beat Firefox into submission.
        try {
            $this->webdriver->manage()->window()->maximize();
        } catch (\Exception $e) {
            // ¯\_(ツ)_/¯
        }
    }
    
}
