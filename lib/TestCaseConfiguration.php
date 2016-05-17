<?php

namespace Magium;


class TestCaseConfiguration extends AbstractConfigurableElement
{

    const CAPABILITIES_CHROME = 'chrome';

    const CAPABILITIES_ANDROID = 'android';

    const CAPABILITIES_FIREFOX = 'firefox';
    const CAPABILITIES_IE = 'internetExplorer';
    const CAPABILITIES_IPAD = 'ipad';
    const CAPABILITIES_IPHONE = 'iphone';

    const CAPABILITIES_OPERA_BLINK = 'operaBlink';
    const CAPABILITIES_OPERA_PRESTO = 'operaPresto';
    const CAPABILITIES_PHANTOMJS = 'phantomjs';
    const CAPABILITIES_SAFARI = 'safari';

    public $capabilities = self::CAPABILITIES_CHROME;

    public $webDriverRemote = 'http://localhost:4444/wd/hub';


    public function getWebDriverConfiguration()
    {
        $capabilities = call_user_func("\\Facebook\\WebDriver\\Remote\\DesiredCapabilities::{$this->capabilities}");
        return [
            'url' => [
                'default' => $this->webDriverRemote],
                'desired_capabilities' => ['default' => $capabilities]
            ];
    }

    /**
     * @return string
     */
    public function getCapabilities()
    {
        return $this->capabilities;
    }

    public function reprocessConfiguration(array $config)
    {
        return $config;
    }


}