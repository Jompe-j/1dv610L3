<?php

namespace view;


use http\Exception;
use model\CookieSettingsModel;
use model\LoginConstants;
use model\LoginCredentialsModel;
use model\LoginStateModel;
use model\PasswordModel;
use model\UsernameModel;

class LoginFormView implements IContentView
{
    private $constants;
    private $loginCredentials;
    private $cookieSettings;
    private $message;
    private $credentials;

    public function __construct() {
        $this->constants = new LoginConstants(); //TODO How should String dependence be handled?
        $this->credentials = new LoginCredentialsModel();
    }


    public function contentToString() : string {
        $this->setMessage($this->credentials->getIssueCode());
        return $this->generateLoginFormHTML($this->message, $this->credentials->getUsername());
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

    public function setMessage($code): void {
        if($code === 1){
            $this->message = 'Username is missing';
        }

        if($code === 2){
            $this->message = 'Password is missing';
        }

        if($code === 3){
            $this->message = 'Wrong name or password';
        }
        if($code === 250){
            $this->message = 'Bye Bye';
        }
    }

    public function getLoginCredentials(): LoginCredentialsModel {
        return $this->credentials;
    }

    public function setCredentials($credentials): void {
        $this->credentials = $credentials;
    }

    public function getUsername():string  {
        if ($_POST[$this->constants::getName()] !== ''){
           return  $_POST[$this->constants::getName()];
        }
        $code = 1;
        throw new \UnexpectedValueException('No username provided', $code);
    }

    public function getPassword() : string  {
        if($_POST[$this->constants::getPassword()] !== ''){
            return $_POST[$this->constants::getPassword()];
        }
        $code = 2;
        throw new \UnexpectedValueException('No password provided', $code);

    }

    public function getKeepLoggedIn(): bool {
        if (isset($_POST[$this->constants::getKeep()])){
            return true;
        }
        return false;
    }

    public function getCookieCrentials(){
        if(isset($_COOKIE[$this->constants::getCookieName()], $_COOKIE[$this->constants::getCookiePassword()])){
            $credentials = new LoginCredentialsModel();
            $credentials->setUsername($_COOKIE[$this->constants::getCookieName()]);
            $credentials->setPassword($_COOKIE[$this->constants::getCookiePassword()]);
            return $credentials;
        }
        throw new \Exception('No cookies');
    }

    public function setCookie(): void {
        $tokenModel = new \model\TokenModel();
        $cookieExpiration = time() + 86400;
        $this->cookieSettings = new CookieSettingsModel($this->credentials->getUsername(), $tokenModel->getToken(), $cookieExpiration);

        setcookie($this->constants::getCookieName(), $this->cookieSettings->getUsername(), $this->cookieSettings->getExpiration());
        setcookie($this->constants::getCookiePassword(), $this->cookieSettings->getToken(), $this->cookieSettings->getExpiration());
    }

    public function getCookieSettings() : CookieSettingsModel{
        return $this->cookieSettings;
    }


}