<?php

namespace Magium\Magento\Themes\Customer;

use Magium\AbstractConfigurableElement;

class ThemeConfiguration extends AbstractConfigurableElement
{

    protected $accountNavigationXpath   = '//div[contains(concat(" ",normalize-space(@class)," ")," block-account ")]/descendant::a[.="%s"]';
    protected $accountSectionHeaderXpath = '//div[contains(concat(" ",normalize-space(@class)," ")," col-main ")]/descendant::h1[.="%s"]';

    /**
     * @return string
     */
    public function getAccountNavigationXpath()
    {
        return $this->accountNavigationXpath;
    }

    /**
     * @return string
     */
    public function getAccountSectionHeaderXpath()
    {
        return $this->accountSectionHeaderXpath;
    }



}