<?php

namespace view;


use model\LoginConstants;

class LoginFormView implements IContentView
{
    private $constants;
    private $message = '';
    private $password = '';
    private $username = '';
    private $keepLoggedIn = false;
    private $cookieToken;
    private $cookieExpiration;

    public function __construct() {
        $this->constants = new LoginConstants(); //TODO How should String dependence be handled?
    }

    public function contentToString() : string {
        return $this->generateLoginFormHTML($this->message, $this->username);
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



    public function setCredentials(): void {
        $this->setUsername();
        $this->setPassword();
        $this->setKeepLoggedIn();
    }

    public function setUsername(): void {
        try {
            $this->validUsername();
        } catch (\Exception $exception){
            $this->message  = $exception->getMessage();
        }
         $this->username = $_POST[$this->constants::getName()];
    }

    public function setPassword(): void {
        try{
            $this->validPassword();
        } catch (\Exception $exception){
            $this->message = $exception->getMessage();
        }
        $this->password = $_POST[$this->constants::getPassword()];
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getPassword() : string {
        return $this->password;
    }

    public function getKeepLoggedIn(): bool {
        return $this->keepLoggedIn;
    }

    public function loginExceptionHandler(\Exception $exception): void {
        if($exception->getCode() === 0){
           $this->message = 'Wrong name or password';
        }
    }

    public function setCookie() {
        $token = new \model\TokenModel();
        $this->cookieToken = $token->getToken();
        $this->cookieExpiration = time() + 86400;
        setcookie($this->constants::getCookieName(), $this->getUsername(), $this->cookieExpiration);
        setcookie($this->constants::getCookiePassword(), $this->cookieToken, $this->cookieExpiration);
    }

    public function getToken() {
        return $this->cookieToken;
    }

    public function getExpiration() {
        return $this->cookieExpiration;
    }

    private function validUsername(): bool
    {
        if(!empty($_POST[$this->constants::getName()])){
            return true;
        }
        throw new \InvalidArgumentException('Username is missing');
    }

    private function validPassword() : bool{
        if(!empty($_POST[$this->constants::getPassword()])){
            return true;
        }
        throw new \InvalidArgumentException('Password is missing');
    }

    private function setKeepLoggedIn(): void {
        if (isset($_POST[$this->constants::getKeep()])){
            $this->keepLoggedIn = true;
        } else {
            $this->keepLoggedIn = false;
        }
    }
}