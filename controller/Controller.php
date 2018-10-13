<?php

namespace controller;


use view\CalculatorView;
use view\View;

class Controller
{
    private $view;
    private $dateTimeView;
    private $registerModel;

    private $loginController;

    public function __construct(\view\LayoutView $view)
    {
        $this->view = $view;
        $this->dateTimeView = new \view\DateTimeView();
    }

    public function checkViewState() : void {
        $this->loginController = new LoginController($this->view, $this->dateTimeView);

        if($this->view->isLoggingOut()){
            $this->loginController->logOut();
        }

        if($this->view->userTryToLogin() && $this->loginController->loginAttempt()) {
            $this->renderContent();
            return;
        }

        if($this->loginController->isLoggedIn()){
            $this->renderContent();
            return;
        }


            $this->loginController->notLoggedIn(); // TODO SHould not be neccessary when other functionality is in place.

    }

    private function renderContent(): void {
        $this->view->render(new CalculatorView(), $this->dateTimeView);
    }






    // Old Code
    public function createLoginViewModel() : \model\LoginViewModel {

        if (isset($_POST[\model\LoginConstants::getLogout()]) && $this->loginModel->isLoggedIn()) {
            $this->loginModel->logOut();

            return new \model\LoginViewModel(
                false,
                'Bye bye!',
                '',
                false
                );
        }

        if (!$this->loginModel->isLoggedIn() && isset($_COOKIE[\model\LoginConstants::getCookieName()], $_COOKIE[\model\LoginConstants::getCookiePassword()])){
           $cookieCredentials = new \model\LoginCredentialsModel(
               $_COOKIE[\model\LoginConstants::getCookieName()],
               $_COOKIE[\model\LoginConstants::getCookiePassword()],
               false);

            $this->loginModel->cookieAttemptLogin($cookieCredentials);


           $cookieAttempt = $this->loginModel->cookieAttemptLogin($cookieCredentials);

           return new \model\LoginViewModel(
               $cookieAttempt->getSuccess(),
               $cookieAttempt->getMessage(),
               $cookieCredentials->getUsername(),
               false);
        }

        if (isset($_POST[\model\LoginConstants::getLogin()]) && !$this->loginModel->isLoggedIn()){
            $loginCredentials = new \model\LoginCredentialsModel(
                $_POST[\model\LoginConstants::getName()],
                $_POST[\model\LoginConstants::getPassword()],
                $this->checkForWantCookie());

            $attempt = $this->loginModel->formAttemptLogin($loginCredentials);

            return new \model\LoginViewModel(
                $attempt->getSuccess(),
                $attempt->getMessage(),
                $loginCredentials->getUsername(),
                false);
        }

        if(isset($_POST[\model\LoginConstants::getRegister()])){
                $registerCredentials = new \model\RegisterCredentialsModel(
                $_POST[\model\LoginConstants::getRegisterName()],
                $_POST[\model\LoginConstants::getRegisterPassword()],
                $_POST[\model\LoginConstants::getRegisterSamePassword()]);

                $registerAttempt = $this->registerModel->formAttemptRegistration($registerCredentials);

                if ($registerAttempt->getSuccess()){
                    return new \model\LoginViewModel(
                        false,
                        $registerAttempt->getMessage(),
                        $registerAttempt->getUsername(),
                        false
                    );
                }

                return new \model\LoginViewModel(
                    $registerAttempt->getSuccess(),
                    $registerAttempt->getMessage(),
                    $registerAttempt->getUsername(),
                    true
                );
            }



        if(isset($_GET['toLogin'])){
            return new \model\LoginViewModel(
                false,
                '',
                '',
                false
            );
        }

        if(isset($_GET[\model\LoginConstants::getToRegister()])){
            return new \model\LoginViewModel(
                false,
                '',
                '',
                true
            );
        }

        return new \model\LoginViewModel(
            $this->loginModel->isLoggedIn(),
            '',
            '',
            false);
    }


    private function checkForWantCookie(): bool {
        if (isset($_POST[\model\LoginConstants::getKeep()])){
            return true;
        }
        return false;
    }




}