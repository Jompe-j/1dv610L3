<?php
/**
 * Created by IntelliJ IDEA.
 * User: johna
 * Date: 2018-10-11
 * Time: 12:13
 */

namespace controller;


use model\LoginDbModel;

class LoginController
{
    private $view;
    private $dateTimeView;
    private $persistentDataRegistry;
    private $loginModel;

    public function __construct(\view\LayoutView $layoutView, \view\DateTimeView $dateTimeView)
    {
        $this->view = $layoutView; // TODO When should dependencies be injected and when should they be created within the class?
        $this->dateTimeView = $dateTimeView;
        $this->persistentDataRegistry = new LoginDbModel();
        $this->loginModel = new \model\LoginModel();

    }

    public function loginAttempt(): bool
    {
        $content = new \view\LoginFormView();
        $content->setCredentials();
        $this->loginModel->setUpCredentials($content->getUsername(), $content->getPassword(), $content->getKeepLoggedIn());


        try{
            $this->loginModel->userCredentialsLogin();
        } catch (\Exception $exception){
            $content->loginExceptionHandler($exception);
        }

        if($content->getKeepLoggedIn()){
            $content->setCookie();
            $this->persistentDataRegistry->setTokenToDb(
                $content->getUsername(),
                $content->getToken(),
                $content->getExpiration()
            ); // TODO Split into two functions in dbmodel.
        }

        return true;

    }

    public function notLoggedIn(): void
    {
        $content = new \view\LoginFormView();
        $this->view->render($content, $this->dateTimeView );
    }

    public function isSessionSet(): bool {

        return $this->loginModel->isSessionSet();
    }

    public function logOut() {
        $this->loginModel->logOut();

    }

}