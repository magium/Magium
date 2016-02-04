<?php

namespace Magium\Identities;

interface NameInterface
{

    public function getFirstName();
    public function getLastName();
    public function setFirstName($value);
    public function setLastName($value);

}