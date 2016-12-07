<?php

namespace Magium\Assertions\Browser;

trait TitleTrait
{

    protected $title;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function assertSelector($title)
    {
        $this->title = $title;
        $this->assert();
    }

    abstract function assert();

}
