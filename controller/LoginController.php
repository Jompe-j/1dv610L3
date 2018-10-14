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

class LoginController
{
    private $view;
    private $dateTimeView;
    private $persistentDataRegistry;
    private $loginModel;
    private $loginForm;
    private $credentials;

    public function __construct(\view\LayoutView $layoutView, \view\DateTimeView $dateTimeView)
    {
        $this->view = $layoutView; // TODO When should dependencies be injected and when should they be created within the class?
        $this->dateTimeView = $dateTimeView;
        $this->persistentDataRegistry = new LoginDbModel();
        $this->loginForm = new \view\LoginFormView();
        $this->loginModel = new \model\LoginModel();

    }

    public function loginAttempt():bool {
        try {
            $this->loginForm->areCredentialsValid();
            $this->credentials = $this->loginForm->getCredentials();
        } catch (\Exception $exception) {
            $this->view->setIsLoggedInStatus(false);
            return false;
        }

        try{
            $this->loginModel->userCredentialsLogin($this->credentials);
        } catch (\Exception $exception){
            $this->loginForm->loginExceptionHandler($exception);
            $this->view->setIsLoggedInStatus(false);
            return false;
        }

        if( $this->loginForm->getKeepLoggedIn()){
            $this->loginForm->setCookie();
            $cookieSettings = $this->loginForm->getCookieSettings();
            $this->loginModel->setCookieToRegistry($cookieSettings); // TODO Split into two functions in dbmodel.
        }

        $this->setLoggedInStatus(true);
        $this->credentials->setIsLoggedIn(true); // TODO Is this a reference with sideffects?
        return true;
    }

    public function notLoggedIn(): void
    {
        $this->view->render($this->loginForm, $this->dateTimeView );
    }

    public function isLoggedIn(): bool {
        // TODO should also handle cookies

        return $this->loginModel->isSessionSet();
    }

    public function logOut(): void {
        $this->view->logOut(); // TODO should layoutview handle log out?
        $this->loginModel->clearSession();

    }

    public function setLoggedInStatus($status): void {
        // $status = $this->loginController->loginAttempt();
        $this->view->setIsLoggedInStatus($status);

    }

}