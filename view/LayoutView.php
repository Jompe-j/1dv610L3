<?php

namespace view;

class LayoutView {

    private $loginConstants;
    private $message = '';
    private $content;
    private $isLoggedIn = false;
    private $isRegistering = false;
    private $cookieToken;
    private $cookieExpiration;


    public function __construct() {
        $this->loginConstants = new \model\LoginConstants();
    }

    public function render(IContentView $formView, CalculatorView $calculatorView, DateTimeView $dateTimeView): void {
        $this->content = $formView;
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 3</h1><br>
          ' . $this->renderRegisterLink($this->isRegistering, $this->isLoggedIn) . '

          ' . $this->renderIsLoggedIn($this->isLoggedIn) . '
          
          <div class="container">
              ' . $calculatorView->render($this->isLoggedIn) . '
              ' . $this->renderForm($this->isLoggedIn) . '
              ' . $this->renderLogoutButton() . '
              ' . $dateTimeView->show() . '
          </div>
         </body>
      </html>
    ';
  }

  public function userTryToLogin(): bool
  {
        return isset($_POST[$this->loginConstants::getLogin()]);
  }

  public function setIsLoggedInStatus(bool $status): void {
        $this->isLoggedIn = $status;
  }

    public function setIsRegisteringStatus(bool $status): void {
        $this->isRegistering = $status;
    }

    /*public function isRegistering(): bool {
        return isset($_GET[$this->loginConstants::getToRegister()]);
    }*/

    public function isLoggingOut(): bool {
        return isset($_POST[$this->loginConstants::getLogout()]);
    }

    public function logOut(): void {
        setcookie($this->loginConstants::getCookieName(), null, -1);
        setcookie($this->loginConstants::getCookiePassword(), null, -1);
        unset($_COOKIE[$this->loginConstants::getCookieName()], $_COOKIE[$this->loginConstants::getCookiePassword()]);
        $this->setIsLoggedInStatus(false);
    }

    public function getCookieToken() {
        return $this->cookieToken;
    }

    public function getExpiration() {
        return $this->cookieExpiration;
    }

    public function setMessageCode(int $code): void {
        if($code === 11){
            $this->message = 'Welcome';
        }
        if($code === 12){
            $this->message = 'Welcome back with cookie';
        }
        if($code === 13){
            $this->message = 'Welcome and you will be remembered';
        }
    }

    private function renderRegisterLink($isRegistering, $isLoggedIn): string
    {
        if ($isLoggedIn){
            return '';
        }
        if ($isRegistering){
            return '<a href="?">Back to login</a>';
        }
            return  '<a href="?' . \model\RegisterConstants::getToRegister() . '">Register a new user</a>';
    }

    private function renderIsLoggedIn(bool $isLoggedIn): string {
        if ($isLoggedIn) {
            return '<h2>Logged in</h2>';
        }
        return '<h2>Not logged in</h2>
              <br><br>';
    }

    private function renderForm(bool $isLoggedIn) : string
    {
        if(!$isLoggedIn){
            return $this->content->contentToString();
        }
        return '';
    }

    private function renderLogoutButton(): string {
        if($this->isLoggedIn){
            return '
			<form  method="post" >
				<p id="' . $this->loginConstants::getMessageId() . '">' . $this->getOneTimeMessage() .'</p>
				<input type="submit" name="' . $this->loginConstants::getLogout() . '" value="logout"/>
			</form>';
        }
        return '';
    }

    private function getOneTimeMessage(): string {
        $message = $this->message;
        $this->message = '';
        return $message;
    }
}
