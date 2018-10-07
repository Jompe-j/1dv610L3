<?php

namespace model;


class TokenModel
{
    private $token;
    private $isStrong;

    public function __construct()
    {
        $this->generateToken();
    }

    private function generateToken()
    {
        $bytes = openssl_random_pseudo_bytes(12, $cstrong);
        $this->token = bin2hex($bytes);
    }

    public function getToken() {
        return $this->token;
    }

    public function getIsStrong() {
        return $this->isStrong;
    }


}