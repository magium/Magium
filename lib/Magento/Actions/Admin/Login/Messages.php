<?php

namespace Magium\Magento\Actions\Admin\Login;

use Magium\WebDriver\WebDriver;

class Messages
{
    protected $webdriver;
    protected $messages = [];

    public function __construct(
        WebDriver $webDriver
    ) {
        $this->webdriver = $webDriver;
    }

    public function addMessage($message)
    {
        $this->messages[] = $message;
    }


    public function hasMessages()
    {
        return count($this->messages) > 0;
    }

}