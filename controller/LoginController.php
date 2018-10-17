<?php

namespace controller;

use model\ICredentialsModel;
use model\LoginCredentialsModel;
use model\LoginStateModel;

class LoginController
{
    private $view;
    private $dateTimeView;
    private $loginModel;
    private $loginForm;
    private $credentials;
    private $cookieCredentials;

    public function __construct(\view\LayoutView $layoutView, \view\DateTimeView $dateTimeView)
    {
        $this->view = $layoutView; // TODO When should dependencies be injected and when should they be created within the class?
        $this->dateTimeView = $dateTimeView;
        $this->loginForm = new \view\LoginFormView();
        $this->loginModel = new \model\LoginModel();
    }

    public function attemptDifferentLoginWays(): bool {
        if($this->attemptWithSession() || $this->attemptWithCookies()){
            return true;
        }

        if($this->view->userTryToLogin()){
           return $this->attemptWithCredentials();
        }

        return false;
    }

    public function setFormCredentials(ICredentialsModel $credentials){
        $this->loginForm->setCredentials($credentials);
    }

    private function attemptWithCredentials(): bool {
        try{
            $this->getUserCredentials();
        } catch (\LengthException $exception){
            return false;
        }
        $this->loginWithCredentials();
        $this->attemptToSetCookiesAtLogin();
        return $this->credentials->getSuccess();
    }

    private function getUserCredentials(): void {
        $this->credentials = $this->loginForm->getCredentials();
    }

    private function loginWithCredentials(): void {
        $this->credentials = $this->loginModel->userCredentialsLogin($this->credentials);
        $this->setLoggedInStatus($this->credentials);
    }

    public function renderForm(): void {
        $this->view->render($this->loginForm, $this->dateTimeView );
    }

    private function attemptWithSession(): bool {
        $this->credentials = $this->loginModel->isSessionSet();
        $this->setLoggedInStatus($this->credentials);
        return $this->credentials->getSuccess();
    }

    private function attemptWithCookies(): bool {
        try{
            $this->getCookieCredentials();
        } catch (\Exception $exception){
            return false;
        }
        $this->loginWithCookies();
        return $this->cookieCredentials->getSuccess();
    }

    private function getCookieCredentials(): void {
        $this->cookieCredentials = $this->loginForm->getCookieCredentials();
    }

    private function loginWithCookies(): void {
        $this->loginModel->cookieAttemptLogin($this->cookieCredentials);
        $this->setLoggedInStatus($this->cookieCredentials);
    }

    private function setLoggedInStatus(LoginCredentialsModel $credentials): void {
        $this->view->setMessageCode($credentials->getIssueCode());
        $this->view->setIsLoggedInStatus($credentials->getSuccess());
    }

    private function logOut(): void {
        if ($this->attemptDifferentLoginWays()) {
            $this->credentials = $this->loginModel->logOut($this->credentials);
            $this->loginForm->setCredentials($this->credentials);
            $this->view->logOut();
        }
    }

    private function attemptToSetCookiesAtLogin(): void {
        if ($this->credentials->getKeepLoggedIn() && $this->credentials->getSuccess()){
            $this->setCookieInView();
            $this->credentials = $this->updateCookieRegistryResult();
            $this->view->setMessageCode($this->credentials->getIssueCode());// TODO Split into two functions in dbmodel.
        }
    }

    public function attemptToLogOut(): bool {
        if($this->view->isLoggingOut()){
            $this->logOut();
            return true;
        }
        return false;
    }


    private function setCookieInView(): void {
        $this->loginForm->setCookie();
    }

    private function updateCookieRegistryResult(): LoginCredentialsModel {
       return $this->loginModel->setCookieToRegistry($this->loginForm->getCookieSettings(), $this->credentials);
    }


}