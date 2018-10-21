<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-14
 * Time: 11:47
 */

namespace model;


class CookieSettingsModel {

    private $username;
    private $token;
    private $expiration;

    public function __construct($username, $token, $expiration) {
        $this->username = $username;
        $this->token = $token;
        $this->expiration = $expiration;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getToken(){
        return $this->token;
    }

    public function getExpiration(){
        return $this->expiration;
    }
}