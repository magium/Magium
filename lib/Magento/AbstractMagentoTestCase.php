<?php

namespace Magium\Magento;

use Magium\AbstractTestCase;

abstract class AbstractMagentoTestCase extends AbstractTestCase
{

    protected function setUp()
    {
        parent::setUp();

        $this->di->instanceManager()->setTypePreference(
            'Magium\\Magento\\Actions\\Checkout\\PaymentMethods\\PaymentMethodInterface',
            ['Magium\\Magento\\Actions\\Checkout\\PaymentMethods\\NoPaymentMethod']
        );

        $this->di->instanceManager()->setTypePreference(
            'Magium\\Magento\\Actions\\Checkout\\ShippingMethods\\ShippingMethodInterface',
            ['Magium\\Magento\\Actions\\Checkout\\ShippingMethods\\FirstAvailable']
        );

    }
    /**
     *
     * @param string $theme
     * @return \Magium\Magento\Themes\ThemeConfiguration
     */

    public function getTheme($theme = 'Magium\Magento\Themes\ThemeConfiguration')
    {
        return $this->get($theme);
    }

    /**
     *
     * @param string $customer
     * @return \Magium\Magento\Admin\Account
     */

    public function getAdminAccount($adminAccountCLass = 'Magium\Magento\Admin\Account')
    {
        return $this->get($adminAccountCLass);
    }

    /**
     *
     * @param string $navigator
     * @return \Magium\Navigators\BaseMenuNavigator
     */

    public function getNavigator($navigator = 'Magium\Magento\Navigators\BaseMenuNavigator')
    {
        return $this->get($navigator);
    }

    /**
     * @param $method
     * @return \Magium\Magento\Actions\Checkout\PaymentMethods\PaymentMethodInterface
     */

    public function setPaymentMethod($method)
    {
        // If we are passed just the class name we will prepend it with Magium\Magento\Actions\Checkout\PaymentMethods
        if (strpos($method, '\\') === false) {
            $method = 'Magium\\Magento\\Actions\\Checkout\\PaymentMethods\\' . $method;
        }
        $methodInstance = $this->get($method);
        self::assertInstanceOf('Magium\\Magento\\Actions\\Checkout\\PaymentMethods\\PaymentMethodInterface', $methodInstance);
        $this->di->instanceManager()->unsetTypePreferences('Magium\\Magento\\Actions\\Checkout\\PaymentMethods\\PaymentMethodInterface');
        $this->di->instanceManager()->setTypePreference('Magium\\Magento\\Actions\\Checkout\\PaymentMethods\\PaymentMethodInterface', [$method]);

    }

    /**
     *
     * @param string $navigator
     * @return \Magium\Magento\Navigators\AdminMenuNavigator
     */

    public function getAdminNavigator($navigator = 'Magium\Magento\Navigators\AdminMenuNavigator')
    {
        return $this->get($navigator);
    }

    public function commandOpen($url)
    {
        $this->get('Magium\Commands\Open')->open($url);
    }

    public function switchThemeConfiguration($fullyQualifiedClassName)
    {
        $this->di->instanceManager()->setTypePreference('Magium\Magento\Navigators\BaseNavigator', $fullyQualifiedClassName);
    }
}