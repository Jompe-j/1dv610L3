<?php


namespace model;


use http\Exception;

class LoginModel
{
    private $credentials;
    private $registry;


    public function __construct() {
        $this->registry = new LoginDbModel();

    }
    public function userCredentialsLogin(LoginCredentialsModel $credentials): LoginCredentialsModel {
        $this->credentials = $credentials;
        $successCode = 11; // Code for login with credentials.
        try{
            $this->connectToDb();
            $this->checkForUser();
            $this->matchCredentials();
            $this->setSuccessfulLogin($successCode);
        } catch (\Exception $exception){
            $this->setUnsuccessfulLogin($exception->getCode());
        }
        return $this->credentials;
    }

    public function cookieAttemptLogin(LoginCredentialsModel $cookieCredentials): LoginCredentialsModel {
        $this->credentials = $cookieCredentials;
        $successCode = 12; // Code for login with cookies
        try{
            $this->connectToDb();
            $this->isCookieValid();
            $this->setSuccessfulLogin($successCode);
        } catch (\Exception $exception){
            $this->setUnsuccessfulLogin($exception->getCode());
        }
        return $this->credentials;
    }

    public function isSessionSet(): LoginCredentialsModel {
        $successCode = 0; // Code for attempt with session.
        $this->credentials = new LoginCredentialsModel();
        if(isset($_SESSION['username'])){
            $this->setSuccessfulLogin($successCode);
        } else {
            $this->setUnsuccessfulLogin($successCode);
        }

        return $this->credentials;

    }

    public function setCookieToRegistry(CookieSettingsModel $cookieSettings, LoginCredentialsModel $credentials): LoginCredentialsModel {
        $this->credentials = $credentials;
        $successCode = 13;
        $this->connectToDb();
        $this->setToken($cookieSettings);
        $this->setSuccessfulLogin($successCode);
        return $this->credentials;

    }

    public function clearSession(): void {
        session_unset();
        session_destroy();
    }

    private function connectToDb(): void {
        $this->registry->connectToDb();
    }

    private function checkForUser(): void {
        $this->registry->userExist($this->credentials->getUsername());
    }

    private function matchCredentials(): void {
        $this->registry->matchCredentials($this->credentials->getUsername(), $this->credentials->getPassword());
    }

    private function setSession(): void {
        $_SESSION['username'] = $this->credentials->getUsername();

    }

    private function setSuccessfulLogin(int $successCode): void {
        $this->setSession();
        $this->credentials->setIssueCode($successCode);
        $this->credentials->setSuccess(true);
    }

    private function setUnsuccessfulLogin(int $code): void {
        $this->credentials->setIssuecode($code);
        $this->credentials->setSuccess(false);
    }


    private function isCookieValid(): void {
        $this->registry->validateUserCookie($this->credentials->getUsername(), $this->credentials->getPassword());
    }

    private function setToken(CookieSettingsModel $cookieSettings): void {
        $this->registry->setTokenToDb(
            $cookieSettings->getUsername(),
            $cookieSettings->getToken(),
            $cookieSettings->getExpiration()
        );
    }

    public function logOut($credentials) {
        $successCode = 250;
        $this->credentials = $credentials;
        $this->clearSession();
        $this->setUnsuccessfulLogin($successCode);
        return $this->credentials;
    }


}