<?php

namespace model;

class LoginCredentialsModel
{
    private $username;
    private $password;
    private $wantCookies = false;
    private $isLoggedIn = false;

    public function __construct(UsernameModel $username, PasswordModel $password, bool $wantCookies)
    {
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setWantCookies($wantCookies);
    }

    public function getUsername(){
        return $this->username->getUsername();
    }

    public function getPassword(){
        return $this->password->getPassword();
    }

    public function getWantCookies(): bool {
        return $this->wantCookies;
    }

    private function setUsername(UsernameModel $username): void {
        $this->username = $username;
    }

    private function setPassword(PasswordModel $password): void {
        $this->password = $password;
    }

    private function setWantCookies(bool $wantCookies): void {
        $this->wantCookies = $wantCookies;
    }

    public function getIsLoggedIn(): bool { return $this->isLoggedIn;    }

    public function setIsLoggedIn(bool $isLoggedIn): void {
        $this->isLoggedIn = $isLoggedIn;
    }


}