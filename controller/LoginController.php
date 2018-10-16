<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-11
 * Time: 12:13
 */

namespace controller;



use model\LoginCredentialsModel;
use model\LoginDbModel;
use model\LoginStateModel;

class LoginController
{
    private $view;
    private $dateTimeView;
    private $loginModel;
    private $loginForm;
    private $credentials;
    private $cookieWasSet = false;

    public function __construct(\view\LayoutView $layoutView, \view\DateTimeView $dateTimeView)
    {
        $this->view = $layoutView; // TODO When should dependencies be injected and when should they be created within the class?
        $this->dateTimeView = $dateTimeView;
        $this->loginForm = new \view\LoginFormView();
        $this->loginModel = new \model\LoginModel();
    }

    public function loginAttemptWithCredentials():bool {
        $this->credentials = $this->loginForm->getLoginCredentials();

        if(!$this->attemptUpdateCredentials()){
            return false;
        }

        if(!$this->loginWithCredentials()){
            return false;
        }

        if( $this->credentials->getKeepLoggedIn()){
            $this->loginForm->setCookie();
            $this->loginModel->setCookieToRegistry($this->loginForm->getCookieSettings()); // TODO Split into two functions in dbmodel.
            $this->cookieWasSet = true;
        }

        $this->setLoggedInStatus(true);
        return true;
    }

    public function notLoggedIn(): void
    {
        $this->view->render($this->loginForm, $this->dateTimeView );
    }

    public function loginWithSession(): bool {
        return $this->loginModel->isSessionSet();
    }


    public function loginWithCookies(): bool {
        try{
            $credentials = $this->loginForm->getCookieCrentials();
            return $this->loginModel->cookieAttemptLogin($credentials);
        } catch (\Exception $exception){
            return false;
        }
    }

    private function attemptUpdateCredentials(): bool {
        try {
            $this->updateCredentials();
            return true;
        } catch (\Exception $exception) {
            $this->clearCredentialsPassword();
            $this->credentials->setIssueCode($exception->getCode());
            $this->loginForm->setCredentials($this->credentials);
            return false;
        }
    }

    private function updateCredentials(): void {
        $this->credentials->setUsername($this->loginForm->getUsername());
        $this->credentials->setPassword($this->loginForm->getPassword());
        $this->credentials->setKeepLoggedIn($this->loginForm->getKeepLoggedIn());
    }
    private function loginWithCredentials(): bool {
        try {
            $this->loginModel->userCredentialsLogin($this->credentials);
            return true;
        } catch (\Exception $exception) {
            $this->credentials->setIssueCode($exception->getCode());
            $this->loginForm->setCredentials($this->credentials);
            return false;
        }
    }

    public function setLoggedInStatus($status): void {
        $this->view->setIsLoggedInStatus($status);
    }

    private function clearCredentialsPassword(): void { $this->credentials->setPassword = ''; }

    public function logOut(): void {
        $this->loginModel->clearSession();
        $this->view->logOut();
        $logoutCode = 250;
        $this->loginForm->setMessage($logoutCode);
    }

    public function cookieWasSet(): bool {return $this->cookieWasSet; }


}