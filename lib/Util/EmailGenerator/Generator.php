<?php

namespace Magium\Util\EmailGenerator;

class Generator
{
    public function generate($domain = null)
    {
        $rand = uniqid(openssl_random_pseudo_bytes(10));
        $encoded = base64_encode($rand);
        $username = preg_replace('/\W/', '', $encoded);

        return $username . '@' . $domain;
    }
}