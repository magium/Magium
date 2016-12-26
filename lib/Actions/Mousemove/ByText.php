<?php

namespace Magium\Actions\Mousemove;

use Magium\Actions\AbstractInteraction;
use Magium\Actions\ByTextTrait;

class ByText extends AbstractInteraction
{

    const ACTION = 'Mousemove\ByText';

    use ByTextTrait;

    public function execute($param)
    {
        $element = $this->getElement($this->webDriver, $param);
        $this->webDriver->getMouse()->mouseMove($element->getCoordinates());
    }

}
