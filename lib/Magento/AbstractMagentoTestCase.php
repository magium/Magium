<?php

namespace Magium\Magento;

use Magium\AbstractTestCase;

abstract class AbstractMagentoTestCase extends AbstractTestCase
{
    /**
     *
     * @param string $theme
     * @return \Magium\Themes\ThemeConfiguration
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
