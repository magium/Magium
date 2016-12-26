<?php

namespace Magium\Actions\Mousemove;

use Magium\Actions\AbstractInteraction;
use Magium\Actions\ByIdTrait;
use Magium\Actions\ByTextTrait;

class ById extends AbstractInteraction
{

    const ACTION = 'Mousemove\ById';

    use ByIdTrait;

    public function execute($param)
    {
        $element = $this->getElement($this->webDriver, $param);
        $this->webDriver->getMouse()->mouseMove($element->getCoordinates());
    }

}
