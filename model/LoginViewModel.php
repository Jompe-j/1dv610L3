<?php

namespace model;

class LoginViewModel
{
    private $message;
    private $isLoggedIn;
    private $username;
    private $isRegistering;

    public function __construct($isLoggedIn, $message, $username, $getToRegistering)
    {
        $this->setIsLoggedIn($isLoggedIn);
        $this->setMessage($message);
        $this->setUsername($username);
        $this->setIsRegistering($getToRegistering);
    }

    private function setIsLoggedIn($isLoggedIn): void {
        $this->isLoggedIn = $isLoggedIn;
    }

    public function getIsLoggedIn() {
        return $this->isLoggedIn;
    }

    private function setMessage($message): void {
        $this->message = $message;
    }

    public function getMessage() {
        return $this->message;
    }

    private function setUsername($username): void {
        $this->username = $username;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getIsRegistering()
    {
        return $this->isRegistering;
    }

    private function setIsRegistering($isRegistering): void
    {
        $this->isRegistering = $isRegistering;
    }

}