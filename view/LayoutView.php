<?php

namespace view;

use http\Exception\InvalidArgumentException;

class LayoutView {

    private $loginConstants;
    private $message = '';
    private $username = '';
    private $content;
    private $password;
    private $isLoggedIn = false;

    public function __construct() {
        $this->loginConstants = new \model\LoginConstants();
    }


    public function render(IContentView $contentView, DateTimeView $dateTimeView): void {
        $this->content = $contentView;
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 3</h1><br>
          ' . $this->renderRegisterLink(false, $this->isLoggedIn /* TODO Should not be hardcoded.*/) . '

          ' . $this->renderIsLoggedIn($this->isLoggedIn) . '
          
          <div class="container">
              ' . $this->renderContent() . '
              ' . $this->renderLogutButton() . '
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

    private function renderRegisterLink($isRegistering, $isLoggedIn): string
    {
        if ($isLoggedIn){
            return '';
        }
        if ($isRegistering){
            return '<a href="?toLogin">Back to login</a>';
        }

            return  '<a href="?' . \model\LoginConstants::getToRegister() . '">Register a new user</a>'; //'<a href="?registerUser">Register a new user</a>';


    }

    private function renderIsLoggedIn($isLoggedIn): string {
        if ($isLoggedIn) {
            return '<h2>Logged in</h2>';
        }

        return '<h2>Not logged in</h2>
              <br><br>';
    }

    private function renderContent() : string
    {
        return $this->content->contentToString();
    }

    private function renderLogutButton(): string {
        if($this->isLoggedIn){
            return '
			<form  method="post" >
				<p id="' . $this->loginConstants::getMessageId() . '">' . $this->message .'</p>
				<input type="submit" name="' . $this->loginConstants::getLogout() . '" value="logout"/>
			</form>
		';
        }
        return '';

    }

    public function isLoggingOut(): bool {
            return isset($_POST[$this->loginConstants::getLogout()]);
    }

    public function logOut(): void {
        setcookie($this->loginConstants::getCookieName(), null, -1);
        setcookie($this->loginConstants::getCookiePassword(), null, -1);
        $this->setIsLoggedInStatus(false);
    }


}
