<?php

namespace model;

class LoginCredentialsModel
{
    private $username = '';
    private $password = '';
    private $wantCookies = false;
    private $isLoggedIn = false;
    private $issueCode = 0;


    public function getUsername(): string {
        return $this->username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getKeepLoggedIn(): bool {
        return $this->wantCookies;
    }

    public function getIsLoggedIn(): bool { return $this->isLoggedIn;    }

    public function getIssueCode(): int {
        return $this->issueCode;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function setKeepLoggedIn(bool $wantCookies): void {
        $this->wantCookies = $wantCookies;
    }

    public function setIsLoggedIn(bool $isLoggedIn): void {
        $this->isLoggedIn = $isLoggedIn;
    }

    public function setIssueCode($code): void {
        $this->issueCode = $code;
    }


}