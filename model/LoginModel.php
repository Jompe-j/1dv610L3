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

    }

    public function setCookieToRegistry(CookieSettingsModel $cookieSettings): void {
        $registry = new LoginDbModel();
        $registry->connectToDb();
        $registry->setTokenToDb(
            $cookieSettings->getUsername(),
            $cookieSettings->getToken(),
            $cookieSettings->getExpiration()
        );
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

    public function cookieAttemptLogin(\model\LoginCredentialsModel $cookieCredentials) : bool {
        if($this->isCookieValid($cookieCredentials->getUsername(), $cookieCredentials->getPassword())){
            $_SESSION['username'] = $cookieCredentials->getUsername();
            return true;
        }
        return false;
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



    private function isCookieValid($username, $cookieToken): bool {
        $pdo = new LoginDbModel();
        $pdo->connectToDb();
        return $pdo->visitorCookieIsValid($username, $cookieToken);
    }



}