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
        if (strpos($theme, 'Magium') === false) {
            $theme = 'Magium\Magento\Themes\\' . $theme;
        }
        return $this->get($theme);
    }

    /**
     *
     * @param string $navigator
     * @return mixed
     */

    public function getAction($action)
    {
        if (strpos($action, 'Magium' ) === false) {
            $action = 'Magium\Magento\Actions\\' . $action;
        }
        return $this->get($action);
    }

    /**
     *
     * @param string $navigator
     * @return \Magium\Magento\Navigators\BaseMenuNavigator
     */

    public function getNavigator($navigator = 'BaseMenuNavigator')
    {
        if (strpos($navigator, 'Magium') === false) {
            $navigator = 'Magium\Magento\Navigators\\' . $navigator;
        }
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

    public function commandOpen($url)
    {
        $this->get('Magium\Commands\Open')->open($url);
    }

    public function switchThemeConfiguration($fullyQualifiedClassName)
    {
        $this->di->instanceManager()->setTypePreference('Magium\Magento\Navigators\BaseNavigator', [$fullyQualifiedClassName]);
    }

    /**
     * @param string $name
     * @return \Magium\Magento\Identities\AbstractEntity
     */

    public function getIdentity($name = 'Customer')
    {
        if (strpos($name, 'Magium') === false) {
            $name = 'Magium\Magento\Identities\\' . $name;
        }
        return $this->get($name);
    }
}
