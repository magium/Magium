<?php

namespace Magium\Assertions\Text;

trait TextTrait
{

    protected function createXpath($text)
    {
        $selector = sprintf('//*[concat(" ", normalize-space(.), " ") = " %s "]', addslashes($text));

        return $selector;
    }

}
