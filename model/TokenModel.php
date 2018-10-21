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

    public function getToken() {
        return $this->token;
    }

    public function getIsStrong() {
        return $this->isStrong;
    }

    private function generateToken(): void {
        $bytes = openssl_random_pseudo_bytes(12, $cstrong);
        $this->token = bin2hex($bytes);
    }
}