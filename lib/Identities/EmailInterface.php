<?php

namespace Magium\Identities;

interface EmailInterface extends IdentityInterface
{

    public function getEmailAddress();

    public function setEmailAddress($value);

}
