<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-13
 * Time: 12:15
 */

namespace model;



use http\Exception\InvalidArgumentException;

class UsernameModel {

    private $username = '';

    public function __construct() {

    }

    public function getUsername(): string {
        return $this->username;
    }

    public function validateUsername($username): void {
        if($username !== ''){
            $this->username = $username;
            return;
        }
        throw new \InvalidArgumentException('Invalid username', 1);
    }


}