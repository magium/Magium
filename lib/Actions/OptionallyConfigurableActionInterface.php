<?php

namespace Magium\Actions;

interface OptionallyConfigurableActionInterface extends ActionInterface
{

    public function execute($param = null);

}