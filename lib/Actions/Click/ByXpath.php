<?php

namespace Magium\Actions\Click;

use Magium\Actions\AbstractInteraction;
use Magium\Actions\ByTextTrait;
use Magium\Actions\ByXpathTrait;

class ByXpath extends AbstractInteraction
{

    const ACTION = 'Click\ByXpath';

    use ByXpathTrait;

    public function execute($param)
    {
        $element = $this->getElement($this->webDriver, $param);
        $element->click();
    }

}
