<?php

namespace model;

class LoginCredentialsModel
{
    private $username = "";
    private $password = "";
    private $wantCookies = false;

    public function __construct($username, $password, $wantCookies)
    {
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setWantCookies($wantCookies);
    }

    public function getUsername(){
        return $this->username;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getWantCookies() {
        return $this->wantCookies;
    }

    private function setUsername($username){
        $this->username = $username;
    }

    private function setPassword($password){
        $this->password = $password;

    }
    private function setWantCookies($wantCookies) {

            $this->wantCookies = $wantCookies;
    }


}