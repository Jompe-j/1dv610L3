<?php

namespace view;

class RegisterCredentialsValidator {

    private $username;

    public function validateUserName($username): void {
        $this->username = $username;
        $this->checkUsernameLength($username);
    }

    private function checkUsernameLength($username): void {
        if(\strlen($username) < 3){
            $code = 11;
            throw new \InvalidArgumentException('Username has too few characters, at least 3 characters.<br>', $code);
        }
    }

    public function validatePassword(string $password): void {
        if(\strlen($password) < 6){
            $code = 12;
            throw new \InvalidArgumentException('Password has too few characters, at least 6 characters.<br>', $code);
        }
    }

    public function matchPassword($password1, $password2): void {
        if($password1 !== $password2){
            $code = 13;
            throw new \InvalidArgumentException('Passwords do not match.', $code);
        }
    }

    public function checkForInvalidCharacters(string $username): void {
        $pattern = '/<.*?\>/';
        $count = 0;
        $match = preg_replace($pattern, '', $username, -1,$count);

        if($count !== 0){
            $this->username = $match;
            $code = 14;
            throw new \InvalidArgumentException('Username contains invalid characters.', $code);
        }
    }

    public function getCleanedUsername(){
        return $this->username;
    }
}