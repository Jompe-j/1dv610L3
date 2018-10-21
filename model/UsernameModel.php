<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-13
 * Time: 12:15
 */

namespace model;


class UsernameModel {

    private $username = '';

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
        $this->validateUsername();
    }

    public function validateUsername(): void {
        if ($this->username === ''){
            $code = 1;
            throw new \LengthException('Invalid Length of username', $code);
        }
    }
}