<?php

namespace view;


use http\Exception;
use model\CookieSettingsModel;
use model\LoginConstants;
use model\LoginCredentialsModel;
use model\PasswordModel;
use model\UsernameModel;

class LoginFormView implements IContentView
{
    private $constants;
    private $loginCredentials;
    private $message = '';
    private $password;
    private $username;
    private $keepLoggedIn = false;
    private $cookieSettings;

    public function __construct() {
        $this->constants = new LoginConstants(); //TODO How should String dependence be handled?
        $this->username = new UsernameModel();
        $this->password = new PasswordModel();
    }

    public function contentToString() : string {
        return $this->generateLoginFormHTML($this->message, $this->username->getUsername());
    }

    /**
     * Generate HTML code on the output buffer for the logout button
     * @param $message , String output message
     * @param $username
     * @return string
     */
    private function generateLoginFormHTML($message, $username) : string {
        return '
			<form method="post"> 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . $this->constants::getMessageId() . '">' . $message . '</p>
					
					<label for="' . $this->constants::getName() . '">Username :</label>
					<input type="text" id="' . $this->constants::getName() . '" name="' . $this->constants::getName() . '" value="' . $username . '" />

					<label for="' . $this->constants::getPassword() . '">Password :</label>
					<input type="password" id="' . $this->constants::getPassword() . '" name="' . $this->constants::getPassword() . '" />

					<label for="' . $this->constants::getKeep() . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . $this->constants::getKeep() . '" name="' . $this->constants::getKeep() . '" />
					
					<input type="submit" name="' . $this->constants::getLogin() . '" value="login" />
				</fieldset>
			</form>
		';
    }

    public function areCredentialsValid(): void {
        try {
            $this->username->validateUsername($_POST[$this->constants::getName()]);
            $this->password->validatePassword($_POST[$this->constants::getPassword()]);
        } catch (\Exception $exception){
            $this->setMessage($exception);
            throw new \InvalidArgumentException('InvalidCredentials', 100, $exception);
        }
    }

    public function getCredentials(): LoginCredentialsModel {
        $this->loginCredentials =  new LoginCredentialsModel($this->getUsername(), $this->getPassword(), $this->getKeepLoggedIn());
        return $this->loginCredentials;
    }

    public function setMessage(\Exception $exception): void {
        if($exception->getCode() === 1){
            $this->message = 'Username is missing';
        }

        if($exception->getCode() === 2){
            $this->message = 'Password is missing';
        }
    }


    private function getUsername():UsernameModel  {
        return $this->username;
    }

    private function getPassword() : PasswordModel  {
        return $this->password;
    }

    public function getKeepLoggedIn(): bool {
        if (isset($_POST[$this->constants::getKeep()])){
            return true;
        } else {
            return false;
        }
    }

    public function loginExceptionHandler(\Exception $exception): void {
        if($exception->getCode() === 3){
           $this->message = 'Wrong name or password';
        }
    }

    private function setKeepLoggedIn(): void {
        if (isset($_POST[$this->constants::getKeep()])){
            $this->keepLoggedIn = true;
        } else {
            $this->keepLoggedIn = false;
        }
    }

    public function setCookie(): void {
        $tokenModel = new \model\TokenModel();
        $cookieExpiration = time() + 86400;
        $this->cookieSettings = new CookieSettingsModel($this->loginCredentials->getUsername(), $tokenModel->getToken(), $cookieExpiration);

        setcookie($this->constants::getCookieName(), $this->cookieSettings->getUsername(), $this->cookieSettings->getExpiration());
        setcookie($this->constants::getCookiePassword(), $this->cookieSettings->getToken(), $this->cookieSettings->getExpiration());
    }

    public function getCookieSettings() : CookieSettingsModel{
        return $this->cookieSettings;
    }

}