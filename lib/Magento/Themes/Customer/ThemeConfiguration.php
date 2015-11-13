<?php

namespace Magium\Magento\Themes\Customer;

use Magium\AbstractConfigurableElement;

class ThemeConfiguration extends AbstractConfigurableElement
{

    protected $accountNavigationXpath   = '//div[contains(concat(" ",normalize-space(@class)," ")," block-account ")]/descendant::a[.="%s"]';
    protected $accountSectionHeaderXpath = '//div[contains(concat(" ",normalize-space(@class)," ")," col-main ")]/descendant::h1[.="%s"]';

    protected $orderPageName     = 'My Orders';

    protected $viewOrderLinkXpath = '//td[@class="number" and .="%s"]/../td/descendant::a[.="View Order"]';

    protected $orderPageTitleContainsText    = 'Order #';

    /**
     * @return string
     */
    public function getOrderPageTitleContainsText()
    {
        return $this->orderPageTitleContainsText;
    }



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

    /**
     * @return string
     */
    public function getOrderPageName()
    {
        return $this->orderPageName;
    }

    /**
     * @return string
     */
    public function getViewOrderLinkXpath()
    {
        return $this->viewOrderLinkXpath;
    }



}