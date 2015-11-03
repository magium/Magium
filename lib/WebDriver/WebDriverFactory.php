<?php


namespace Magium\WebDriver;

class WebDriverFactory
{

    public static function create(
        $url = 'http://localhost:4444/wd/hub',
        $desired_capabilities = null,
        $connection_timeout_in_ms = null,
        $request_timeout_in_ms = null,
        $http_proxy = null,
        $http_proxy_port = null
    ) {
        return WebDriver::create($url, $desired_capabilities, $connection_timeout_in_ms, $request_timeout_in_ms, $http_proxy, $http_proxy_port);
    }

}