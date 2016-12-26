<?php

namespace Magium\Actions\Mousemove;

use Magium\Actions\AbstractInteraction;
use Magium\Actions\ByCssTrait;
use Magium\Actions\ByTextTrait;

class ByCss extends AbstractInteraction
{

    const ACTION = 'Mousemove\ByCss';

    use ByCssTrait;

    public function execute($param)
    {
        $element = $this->getElement($this->webDriver, $param);
        $this->webDriver->getMouse()->mouseMove($element->getCoordinates());
    }

}
