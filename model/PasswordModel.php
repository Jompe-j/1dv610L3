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
    public function __construct() {
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function validatePassword($password): void {
            if($password !== ''){
            $this->password = $password;
            return;
        }
        throw new \InvalidArgumentException('Invalid password', 2);
    }
}