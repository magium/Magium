<?php

namespace Magium\Actions\Click;

use Magium\Actions\AbstractInteraction;
use Magium\Actions\ByIdTrait;
use Magium\Actions\ByTextTrait;

class ById extends AbstractInteraction
{

    const ACTION = 'Click\ById';

    use ByIdTrait;

    public function execute($param)
    {
        $element = $this->getElement($this->webDriver, $param);
        $element->click();
    }

}
