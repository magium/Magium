<?php

namespace Magium\Magento\Themes;

class AdminThemeConfiguration extends ThemeConfiguration
{
    
    protected $loginUsernameField           = '//input[@type="text" and @id="username"]';
    protected $loginPasswordField           = '//input[@type="password" and @id="login"]';
    protected $loginSubmitButton            = '//input[@type="submit" and @value="Login"]';
    
    protected $navigationBaseXPathSelector          = '//ul[@id="nav"]';
    protected $navigationChildXPathSelector1         = 'li/descendant::span[.="%s"]';
    protected $navigationChildXPathSelector         = 'li[contains(concat(" ",normalize-space(@class)," ")," level%d ")]/a[.="%s"]/..';

    protected $adminPopupMessageContainerXpath         = '//*[@id="message-popup-window"]';
    protected $adminPopupMessageCloseButtonXpath        = '//*[@id="message-popup-window"]/descendant::*[@title="close"]';

    public function getAdminPopupMessageContainerXpath()
    {
        return $this->adminPopupMessageContainerXpath;
    }

    public function getAdminPopupMessageCloseButtonXpath()
    {
        return $this->adminPopupMessageCloseButtonXpath;
    }

}