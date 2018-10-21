<?php

namespace view;

use model\CookieSettingsModel;
use model\ICredentialsModel;
use model\LoginConstants;
use model\LoginCredentialsModel;

class LoginFormView implements IContentView
{
    private $constants;
    private $cookieSettings;
    private $message;
    private $credentials;

    public function __construct() {
        $this->constants = new LoginConstants();
        $this->credentials = new LoginCredentialsModel();
    }

    public function contentToString() : string {
        $this->setMessage($this->credentials->getIssueCode());
        return $this->generateLoginFormHTML($this->message, $this->credentials->getUsername());
    }

    public function setCredentials(ICredentialsModel $credentials): void {
        $this->credentials = $credentials;
    }

    public function getCredentials(): ?LoginCredentialsModel {
        try{
            $this->setUpCredentials();
            return $this->credentials;
        } catch (\Exception $exception){
            $this->credentials->setIssueCode($exception->getCode());
            throw new \LengthException('Validation issue in Credentials');
        }
    }

    public function getKeepLoggedIn(): bool {
        return isset($_POST[$this->constants::getKeep()]);
    }

    public function getCookieCredentials(): ?LoginCredentialsModel {
        try{
            $this->setUpCookieCredentials();
            return $this->credentials;
        }catch (\Exception $exception){
            throw new \LengthException('Validation Issue for Cookie Credentials');
        }
    }

    public function getCookieName():string  {

        if (isset($_COOKIE[$this->constants::getCookieName()])){
            return  $_COOKIE[$this->constants::getCookieName()];
        }
        return '';
    }

    public function getCookiePassword():string  {
        if (isset($_COOKIE[$this->constants::getCookiePassword()])){
            return  $_COOKIE[$this->constants::getCookiePassword()];
        }
        return '';
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

    public function setMessage(int $code): void {
        if($code === 0){
            $this->message = '';
        }

        if($code === 1){
            $this->message = 'Username is missing';
        }

        if($code === 2){
            $this->message = 'Password is missing';
        }

        if($code === 3){
            $this->message = 'Wrong name or password';
        }

        if($code === 4){
            $this->message = 'Registered new user.';
        }

        if($code === 5){
            $this->message = 'Wrong information in cookies';
        }

        if($code === 250){
            $this->message = 'Bye bye!';
        }
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

    private function setUpCredentials(): void {
        $this->credentials = new LoginCredentialsModel();
        $this->credentials->setUsername($_POST[$this->constants::getName()]);
        $this->credentials->setPassword($_POST[$this->constants::getPassword()]);
        $this->credentials->setKeepLoggedIn($this->getKeepLoggedIn());
    }

    private function setUpCookieCredentials(): void {
        $this->credentials->setUsername($this->getCookieName());
        $this->credentials->setPassword($this->getCookiePassword());
    }

}