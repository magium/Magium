<?php

namespace Magium\Actions\Mousemove;

use Magium\Actions\AbstractInteraction;
use Magium\Actions\ByTextTrait;
use Magium\Actions\ByXpathTrait;

class ByXpath extends AbstractInteraction
{

    const ACTION = 'Mousemove\ByXpath';

    use ByXpathTrait;

    public function execute($param)
    {
        $element = $this->getElement($this->webDriver, $param);
        $this->webDriver->getMouse()->mouseMove($element->getCoordinates());
    }

}
