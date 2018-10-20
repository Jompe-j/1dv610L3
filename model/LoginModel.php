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
            $this->updateCredentialsSuccess($successCode);
        } catch (\Exception $exception){
            $this->updateCredentialsFailure($exception->getCode());
        }
        return $this->credentials;
    }

    public function cookieAttemptLogin(LoginCredentialsModel $cookieCredentials):void{
        $this->credentials = $cookieCredentials;
        $successCode = 12; // Code for login with cookies
        try{
            $this->connectToDb();
            $this->isCookieValid();
            $this->updateCredentialsSuccess($successCode);
        } catch (\Exception $exception){
            $this->credentials = new LoginCredentialsModel(); // Clear all credentials.
            $this->updateCredentialsFailure($exception->getCode());
        }
    }

    public function getUpdatedCredentials(){
        return $this->credentials;
    }

    public function sessionLogin(): void {
        $successCode = 0; // Code for attempt with session.
        $this->credentials = new LoginCredentialsModel();
        if($this->isSessionSet()){
            $this->setUsernameFromSession();
            $this->updateCredentialsSuccess($successCode);
        } else {
            $this->updateCredentialsFailure($successCode);
        }
    }

    public function setCookieToRegistry(CookieSettingsModel $cookieSettings, LoginCredentialsModel $credentials): LoginCredentialsModel {
        $this->credentials = $credentials;
        $successCode = 13;
        $this->connectToDb();
        $this->setToken($cookieSettings);
        $this->updateCredentialsSuccess($successCode);
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

    private function updateCredentialsSuccess(int $successCode): void {
        $this->setSession();
        $this->credentials->setIssueCode($successCode);
        $this->credentials->setSuccess(true);
    }

    private function updateCredentialsFailure(int $code): void {
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
        $this->credentials = $credentials; // TODO Why does the logout need credentials?
        $this->credentials = new LoginCredentialsModel(); // TODO Remove this or line above.
        $this->clearSession();
        $this->updateCredentialsFailure($successCode);
        return $this->credentials;
    }

    private function isSessionSet(): bool {
        if(isset($_SESSION['username'])){
            return true;
        } else {
            return false;
        }
    }

    private function setUsernameFromSession() {
        $this->credentials->setUsername($_SESSION['username']);
    }


}