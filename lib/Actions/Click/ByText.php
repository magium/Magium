<?php

namespace Magium\Actions\Click;

use Magium\Actions\AbstractInteraction;
use Magium\Actions\ByTextTrait;

class ByText extends AbstractInteraction
{

    const ACTION = 'Click\ByText';

    use ByTextTrait;

    public function execute($param)
    {
        $element = $this->getElement($this->webDriver, $param);
        $element->click();
    }

}
