<?php

namespace Magium\Navigators;

interface OptionallyConfigurableNavigatorInterface extends NavigatorInterface
{

    public function navigateTo($instructions = null);

}