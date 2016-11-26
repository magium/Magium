<?php

namespace Magium\Navigators;

interface ConfigurableNavigatorInterface extends NavigatorInterface
{

    public function navigateTo($instructions);

}