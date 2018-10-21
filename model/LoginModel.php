<?php


namespace model;

class LoginModel
{
    private $credentials;
    private $registry;
    private $cookieLoginCode = 12;
    private $credentialsLoginCode = 11;
    private $sessionLoginCode = 0;
    private $setCookieCode = 13;
    private $logoutCode = 250;
    private $sessionName = 'username';

    public function __construct() {
        $this->registry = new LoginDbModel();
    }

    public function userCredentialsLogin(LoginCredentialsModel $credentials): LoginCredentialsModel {
        $this->credentials = $credentials;
        $successCode = $this->credentialsLoginCode;
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
        $successCode = $this->cookieLoginCode;
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
        $successCode = $this->sessionLoginCode;
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
        $successCode = $this->setCookieCode;
        $this->connectToDb();
        $this->setToken($cookieSettings);
        $this->updateCredentialsSuccess($successCode);
        return $this->credentials;
    }

    public function clearSession(): void {
        session_unset();
        session_destroy();
    }

    public function logOut(): LoginCredentialsModel {
        $successCode = $this->logoutCode;
        $this->credentials = new LoginCredentialsModel();
        $this->clearSession();
        $this->updateCredentialsFailure($successCode);
        return $this->credentials;
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
        $_SESSION[$this->sessionName] = $this->credentials->getUsername();
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

    private function isSessionSet(): bool {
        return isset($_SESSION[$this->sessionName]);
    }

    private function setUsernameFromSession(): void {
        $this->credentials->setUsername($_SESSION[$this->sessionName]);
    }
}