<?php

namespace Magium\Identities;

interface NameInterface extends IdentityInterface
{

    public function getFirstName();
    public function getLastName();
    public function setFirstName($value);
    public function setLastName($value);

}
