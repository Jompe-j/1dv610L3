<?php


namespace model;


use http\Exception\InvalidArgumentException;
use http\Exception\UnexpectedValueException;

class RegisterModel
{
    private $credentials;

    public function __construct() {
        $this->register = new LoginDbModel();
    }

    public function formAttemptRegistration(\model\RegisterCredentialsModel $registerCredentials): RegisterCredentialsModel {
        $this->credentials = $registerCredentials;
        $successCode = 31;
        try{
            $this->connectToDb();
            $this->isUsernameAvailable();
            $this->registerUser();
            $this->successfulRegistration($successCode);
        } catch (\Exception $exception){
            $this->unSuccessfulRegistration($exception->getCode());
        }
        return $this->credentials;
    }

    private function isUsernameAvailable(): void {
        try{
            $this->register->userExist($this->credentials->getUsername());
        } catch (\Exception $exception){
            return;
        }
        throw new \InvalidArgumentException('Username already exists', 3);

    }

    private function registerUser(): void {
        $this->register->insertUser($this->credentials->getUsername(), $this->credentials->getPassword());
    }

    private function successfulRegistration(int $successCode): void {
        $this->credentials->setSuccessStatus(true);
        $this->credentials->setIssueCode($successCode);
    }

    private function unSuccessfulRegistration(int $failureCode): void {
        $this->credentials->setSuccessStatus(false);
        $this->credentials->setIssueCode($failureCode);
    }

    private function connectToDb(): void {
        $this->register->connectToDb();
    }

}