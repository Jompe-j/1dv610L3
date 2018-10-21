<?php

namespace model;


class RegisterAttempt
{
    private $message = '';
    private $username = '';
    private $success = false;

    /**
     * LoginAttempt constructor.
     * @param bool $success
     * @param string $message
     */
    public function __construct(bool $success, string $username, string $message)
    {
        $this->setSuccess($success);
        $this->setUsername($username);
        $this->setMessage($message);
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param bool $success
     */
    private function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @param string $message
     */
    private function setMessage(string $message): void
    {
        $this->message = $message;
    }

    private function setUsername(string $username)
    {
        $this->username = $username;
    }
}