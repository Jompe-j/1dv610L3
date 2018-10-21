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
    private $calculatorController;
    private $calculatorView;

    public function __construct(\view\LayoutView $view)
    {
        $this->view = $view;
        $this->dateTimeView = new \view\DateTimeView();
        $this->calculatorView = new CalculatorView();
    }

    public function checkViewState() : void {
        $this->loginController = new LoginController($this->view, $this->dateTimeView);
        $this->registerController = new RegisterController($this->view, $this->dateTimeView);
        $this->calculatorController = new CalculatorController();

        if($this->calculatorController->isCalculatorPosting()){
           $this->calculatorView = $this->calculatorController->getUpdatedCalculator();
        }

        if($this->loginController->attemptToLogOut()){
            $this->renderLoginForm();
            return;
        }

        if($this->loginController->attemptDifferentLoginWays()) {
            $this->renderLoginForm();
            return;
        }

        if($this->registerController->isRegistering()){
            if($this->registerController->userTryToRegister() && $this->registerController->attemptToRegister()) {
                $this->loginController->setFormCredentials($this->registerController->getRegistrationCredentials());
                $this->renderLoginForm();
                return;
            }
            $this->renderRegistrationForm();
            return;
        }
        $this->renderLoginForm();
    }

    private function renderLoginForm(): void {
        $this->loginController->renderForm($this->calculatorView);
    }

    public function renderRegistrationForm(): void
    {
        $this->registerController->renderForm($this->calculatorView);
    }
}