<?php


namespace model;


class RegisterCredentialsModel
{
    private $username = "";
    private $password = "";
    private $samePassword = "";

    public function __construct($username, $password, $samePassword)
    {
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setSamePassword($samePassword);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSamePassword(): string
    {
        return $this->samePassword;
    }

    private function setUsername($username){
        $this->username = $username;
    }

    private function setPassword($password){
        $this->password = $password;

    }

    private function setSamePassword($samePassword)
    {
        $this->samePassword = $samePassword;
    }
}