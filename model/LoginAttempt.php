<?php

namespace model;

class LoginAttempt
{
    private $message = '';
    private $success = false;

    /**
     * LoginAttempt constructor.
     * @param bool $success
     * @param string $message
     */
    public function __construct(bool $success, string $message)
    {
        $this->setSuccess($success);
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
}