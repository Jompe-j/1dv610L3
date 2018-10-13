<?php


namespace model;


use http\Exception;

class LoginModel
{
    private $credentials;

    public function __construct() {

    }

    public function userCredentialsLogin(LoginCredentialsModel $credentials): void {
        $registry = new LoginDbModel();
            $registry->connectToDb();
            $registry->userExist($credentials->getUsername());
            $registry->matchCredentials($credentials->getUsername(), $credentials->getPassword());
            $this->setSession($credentials->getUsername());
            var_dump(isset($_SESSION['username']));

    }

    public function setSession($username): void {
        $_SESSION['username'] = $username;
    }

    public function isSessionSet(): bool {
        if(isset($_SESSION['username'])){
            return true;
        }
        return false;
    }
    public function clearSession(): void {
        session_unset();
        session_destroy();
    }



    public function cookieAttemptLogin(\model\LoginCredentialsModel $cookieCredentials) : LoginAttempt {
        if($this->isCookieValid($cookieCredentials->getUsername(), $cookieCredentials->getPassword())){
            $_SESSION['username'] = $cookieCredentials->getUsername();
            return new LoginAttempt(true, 'Welcome back with cookie');
        }
        return new LoginAttempt(false, 'Wrong information in cookies');
    }




    public function formAttemptLogin(\model\LoginCredentialsModel $credentialsModel) : LoginAttempt {
        $this->credentials = $credentialsModel;

        if(empty($credentialsModel->getUsername())){
            return new LoginAttempt(false, 'Username is missing');
        }

        if(empty($credentialsModel->getPassword())){
            return new LoginAttempt(false, 'Password is missing');
        }

        $attemptSuccess = $this->matchCredentials();

        if ($attemptSuccess){
                $_SESSION['username'] = $this->credentials->getUsername();
            if ($this->credentials->getWantCookies()) {
                $this->setCookies();

                return new LoginAttempt(true, 'Welcome and you will be remembered');
            }
            return new LoginAttempt(true, 'Welcome');
        }

        return new LoginAttempt(false, 'Wrong name or password');
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['username']);
    }



    private function matchCredentials(): bool {
        $pdo = new LoginDbModel();
        $matchingCredentials = $pdo->providedCredentialsMatchDatabase($this->credentials->getUsername(), $this->credentials->getPassword());
        if (!$matchingCredentials) {
            return false;
        }

        if ($matchingCredentials) {
            return true;
        }

        return false;
    }

    private function setCookies(): void {
        $username = $this->credentials->getUsername();
        $expiration = time() + 86400;
        $this->setTokenToDb($username, $expiration);
        setcookie(\model\LoginConstants::getCookieName(), $username, $expiration);
        setcookie(\model\LoginConstants::getCookiePassword(), $this->getToken($username), $expiration);
    }

    private function setTokenToDb($username, $expiration): void {
        $pdo = new LoginDbModel();
        $pdo->setTokenToDb($username, $expiration);
    }

    private function getToken($username) : string {
        $pdo = new LoginDbModel();
        return $pdo->getToken($username);
    }

    private function isCookieValid($username, $cookieToken): bool {
        $pdo = new LoginDbModel();
        return $pdo->visitorCookieIsValid($username, $cookieToken);
    }



}