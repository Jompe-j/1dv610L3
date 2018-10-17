<?php


namespace model;


class RegisterCredentialsModel implements ICredentialsModel {
    private $username = '';
    private $password = '';
    private $successStatus = false;
    private $issueCode = 0;

    public function __construct(string $username, string $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setIssueCode(int $code): void {
        $this->issueCode = $code;
    }

    public function getIssueCode(): int{
        return $this->issueCode;
    }

    public function getSuccessStatus(): bool {
        return $this->successStatus;
    }

    public function setSuccessStatus(bool $successStatus): void {
        $this->successStatus = $successStatus;
    }


}