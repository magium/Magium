<?php

namespace Magium\Actions\Click;

use Magium\Actions\AbstractInteraction;
use Magium\Actions\ByCssTrait;
use Magium\Actions\ByTextTrait;

class ByCss extends AbstractInteraction
{

    const ACTION = 'Click\ByCss';

    use ByCssTrait;

    public function execute($param)
    {
        $element = $this->getElement($this->webDriver, $param);
        $element->click();
    }

}
