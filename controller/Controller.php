<?php

namespace controller;
use view\CalculatorView;
use view\View;
class Controller
{
    private $view;
    private $dateTimeView;
    private $loginController;
    private $registerController;

    public function __construct(\view\LayoutView $view)
    {
        $this->view = $view;
        $this->dateTimeView = new \view\DateTimeView();
    }

    public function checkViewState() : void {
        $this->loginController = new LoginController($this->view, $this->dateTimeView);
        $this->registerController = new RegisterController($this->view, $this->dateTimeView);

        if($this->loginController->attemptToLogOut()){
            $this->renderForm();
            return;
        }

        if($this->loginController->attemptDifferentLoginWays()) {
            $this->renderContent();
            return;
        }

        if($this->view->isRegistering()){
            $this->registerController->renderForm();
            return;
        }

        if($this->registerController->userTryToRegister()) {
            if($this->registerController->attemptToRegister()){
                $this->loginController->setFormCredentials($this->registerController->getRegistrationCredentials());
                $this->renderForm();
                return;
            }
            $this->registerController->renderForm();
            return;
        }
        $this->renderForm(); // TODO SHould not be neccessary when other functionality is in place.
    }

    private function renderContent(): void {
        $this->view->render(new CalculatorView(), $this->dateTimeView);
    }

    private function renderForm(): void {
        $this->loginController->renderForm();
    }
}