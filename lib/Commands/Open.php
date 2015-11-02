<?php

namespace Magium\Commands;

use Magium\WebDriver\WebDriver;
use Zend\Log\Logger;

class Open
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
        $this->logger->info(sprintf('Opening URL %s', $url));
        $this->webdriver->get($url);
        $this->logger->info(sprintf('URL %s has been opened', $url));
    }
    
}