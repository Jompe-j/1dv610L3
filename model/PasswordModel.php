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
    public function __construct($password) {
        $this->validatePassword($password);
    }

    public function getPassword(): string {
        return $this->password;
    }

    private function validatePassword($password): void {
        if(!empty($password)){
            $this->password = $password;
        }
        throw new \InvalidArgumentException('No Password');
    }
}