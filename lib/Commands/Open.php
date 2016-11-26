<?php

namespace Magium\Commands;

use Magium\Util\Log\Logger;
use Magium\WebDriver\WebDriver;


class Open implements CommandInterface
{
    protected $webdriver;
    protected $logger;
    
    public function __construct(
        WebDriver $webdriver,
        Logger    $log
    )
    {
        $this->webdriver = $webdriver;
        $this->logger = $log;
    }
    
    public function open($url)
    {
        $this->logger->info(
            sprintf('Opening URL %s', $url),
            [
                'activity'  => 'open',
                'url'       => $url
            ]
        );
        $this->webdriver->get($url);
        $this->logger->info(
            sprintf('URL %s has been opened', $url),
            [
                'activity'  => 'open',
                'url'       => $url
            ]
        );
        // This is done because firefox does not always scroll the admin menus into view.
        // TODO - find a better way to beat Firefox into submission.
        $this->webdriver->manage()->window()->maximize();
    }
    
}