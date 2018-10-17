<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-13
 * Time: 12:54
 */

namespace model;


class PasswordModel {

    /**
     * PasswordModel constructor.
     * @param $getPassword
     */

    private $password = '';

    public function setPassword($password): void{
            $this->password = $password;
            $this->validatePassword();
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function validatePassword(): void {
        if ($this->password  === ''){
            $code = 2;
            throw new \LengthException('Invalid Length of Password', $code);
        }
    }
}