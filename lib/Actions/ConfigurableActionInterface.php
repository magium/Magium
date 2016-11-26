<?php

namespace Magium\Actions;

interface ConfigurableActionInterface extends ActionInterface
{

    public function execute($param);

}