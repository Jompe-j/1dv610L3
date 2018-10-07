<?php

namespace view;

class LayoutView {

  
  public function render(\model\LoginViewModel $loginViewModel, \view\DateTimeView $dateTimeView) {
      $loginView = new View();

    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1><br>
          ' . $this->renderRegisterLink($loginViewModel->getIsRegistering(), $loginViewModel->getIsLoggedIn()) . '

          ' . $this->renderIsLoggedIn($loginViewModel->getIsLoggedIn()) . '
          
          <div class="container">
              ' . $loginView->render($loginViewModel) . '
              
              ' . $dateTimeView->show() . '
          </div>
         </body>
      </html>
    ';
  }
  
  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }

      return '<h2>Not logged in</h2>
              <br><br>';
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


}
