<?php

namespace view;


class View {

    private static $login = 'LoginView::Login';
    private static $logout = 'LoginView::Logout';
    private static $name = 'LoginView::UserName';
    private static $password = 'LoginView::Password';
    private static $cookieName = 'LoginView::CookieName';
    private static $cookiePassword = 'LoginView::CookiePassword';
    private static $keep = 'LoginView::KeepMeLoggedIn';
    private static $messageId = 'LoginView::Message';
    private static $toRegister = 'register';
    private static $Register = 'RegisterView::Register';
    private static $registerName = 'RegisterView::UserName';
    private static $registerPassword = 'RegisterView::Password';
    private static $registerSamePassword = 'RegisterView::PasswordRepeat';
    private static $registerMessage = 'RegisterView::Message';

    /**
     * Create HTTP response
     *
     * Should be called after a login attempt has been determined
     *
     * @param \model\LoginViewModel $viewModel
     * @return  void BUT writes to standard output and cookies!
     */

    private $layout;


    public function __construct(LayoutView $layoutView)
    {
        $this->layout = $layoutView;



    }

    public function render($contentView, $dateTimeView): void
    {
        $this->layout->render($contentView, $dateTimeView);
    }
        /*if(isset($_GET[\model\LoginConstants::getToRegister()])){
            return $this->generateRegisterUser(
                $viewModel->getMessage(),
                $viewModel->getUsername()
            );
        }

        if($viewModel->getIsLoggedIn()) {
            return $this->generateLogoutButtonHTML($viewModel->getMessage());
        }

        return $this->generateLoginFormHTML(
            $viewModel->getMessage(),
            $viewModel->getUsername());

    }*/




}
