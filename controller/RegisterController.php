<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-16
 * Time: 13:07
 */

namespace controller;
use http\Exception;
use view\CalculatorView;

class RegisterController {

    private $view;
    private $dateTimeView;
    private $registerForm;
    private $registerModel;
    private $credentials;

    public function __construct(\view\LayoutView $layoutView, \view\DateTimeView $dateTimeView) {
        $this->view = $layoutView; // TODO When should dependencies be injected and when should they be created within the class?
        $this->dateTimeView = $dateTimeView;
        $this->registerForm = new \view\RegisterFormView();
        $this->registerModel = new \model\RegisterModel();
    }

    public function renderForm(CalculatorView $calculatorView): void {
        $this->view->setIsRegisteringStatus(true);
        $this->view->render($this->registerForm, $calculatorView, $this->dateTimeView);
    }

    public function userTryToRegister(): bool {
        return $this->registerForm->userTryToRegister();
    }

    public function attemptToRegister(): bool {
           return $this->attemptWithCredentials();

    }

    public function getRegistrationCredentials() {
        return $this->credentials;
    }

    private function attemptWithCredentials(): bool {
        try{
            $this->credentials = $this->registerForm->getCredentials();
            $this->credentials = $this->registerModel->formAttemptRegistration($this->credentials);
        } catch (\Exception $exception){
            return false;
        }
        return $this->credentials->getSuccessStatus();
    }




}