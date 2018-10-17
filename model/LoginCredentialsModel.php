<?php

namespace model;

class LoginCredentialsModel implements ICredentialsModel
{
    private $username;
    private $password;
    private $wantCookies = false;
    private $issueCode = 0;
    private $success = false;

    public function __construct() {
        $this->username = new UsernameModel();
        $this->password = new PasswordModel();
    }

    public function validateUsername(): void {
        $this->username->validateUsername();
    }
    public function getUsername(): string {
        return $this->username->getUsername();
    }

    public function getPassword(): string {
        return $this->password->getPassword();
    }

    public function getKeepLoggedIn(): bool {
        return $this->wantCookies;
    }

    public function getSuccess(): bool { return $this->success;    }

    public function getIssueCode(): int {
        return $this->issueCode;
    }

    public function setUsername(string $username): void {
        $this->username->setUsername($username);
    }

    public function setPassword(string $password): void {
        $this->password->setPassword($password);
    }

    public function setKeepLoggedIn(bool $wantCookies): void {
        $this->wantCookies = $wantCookies;
    }

    public function setSuccess(bool $success): void {
        $this->success = $success;
    }

    public function setIssueCode($code): void {
        $this->issueCode = $code;
    }

    public function validatePassword(): void {
        $this->password->validatePassword();
    }


}