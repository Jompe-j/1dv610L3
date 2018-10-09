<?php

namespace view;

class LayoutView {

  
  public function render(string $contentView, string $dateTimeView) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 3</h1><br>
          ' . $this->renderRegisterLink(false, false /* TODO Should not be hardcoded.*/) . '

          ' . $this->renderIsLoggedIn(false) . '
          
          <div class="container">
              ' . $contentView . '
              
              ' . $dateTimeView . '
          </div>
         </body>
      </html>
    ';
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

    private function renderIsLoggedIn($isLoggedIn) {
        if ($isLoggedIn) {
            return '<h2>Logged in</h2>';
        }

        return '<h2>Not logged in</h2>
              <br><br>';
    }


}
