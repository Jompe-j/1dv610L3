<?php

namespace view;

use model\LoginConstants;
use model\LoginCredentialsModel;
use model\RegisterCredentialsModel;

class RegisterFormView implements IContentView

{
    private $message;
    private $constants;
    private $username = '';
    private $validator;
    private $credentials;


    public function __construct() {
    $this->constants = new LoginConstants();
    $this->validator = new RegisterCredentialsValidator();
    $this->credentials = new LoginCredentialsModel();
}

    private function generateRegisterUser($message, $username)
    {
        return ' 
			<form method="post" action="?register"> 
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="'.\model\LoginConstants::getRegisterMessage().'">' . $message . '</p>
					
					<label for="' . \model\LoginConstants::getRegisterName() . '">Username :</label>
					<input type="text" id="'.\model\LoginConstants::getRegisterName().'" name="' . \model\LoginConstants::getRegisterName() . '" value="' . $this->getOneTimeUsername() . '" />

					<label for="' . \model\LoginConstants::getRegisterPassword() . '">Password :</label>
					<input type="password" id="'.\model\LoginConstants::getRegisterPassword().'" name="' . \model\LoginConstants::getRegisterPassword() . '" />
					
					<label for="' . \model\LoginConstants::getRegisterSamePassword() . '">Repeat password :</label>
					<input type="password" id="'.\model\LoginConstants::getRegisterSamePassword().'" name="' . \model\LoginConstants::getRegisterSamePassword() . '" />
					
					<input type="submit" name="' . \model\LoginConstants::getRegister() . '" value="register" />
				</fieldset>
			</form>
		';
    }

    private function getOneTimeUsername(){
        $username = $this->username;
        $this->username = '';
        return $username;
    }

    public function getCredentials(): RegisterCredentialsModel {
        $this->username = $this->getUsername();
        $password = $this->getPassword();
        $validUsername = false;
        $validPassword = false;
        $matchingPasswords = false;
        $validCharacters = false;

        try{
            $this->validator->validateUserName($this->username);
            $validUsername = true;
        }catch (\Exception $exception){
            $this->setMessage($exception->getCode());
        }

        try{
            $this->validator->validatePassword($password);
            $validPassword = true;
        }catch (\Exception $exception){
            $this->setMessage($exception->getCode());
        }

        try{
            $this->validator->matchPassword($password, $this->getIdenticalPassword());
            $matchingPasswords = true;
        } catch (\Exception $exception) {
            $this->setMessage($exception->getCode());
        }

        try{
            $this->validator->checkForInvalidCharacters($this->username);
            $validCharacters = true;
        } catch (\Exception $exception){
            $this->message = $exception->getMessage();
        }

        $this->username = $this->validator->getCleanedUsername();

        if(!$validUsername || !$validPassword  || !$matchingPasswords || !$validCharacters) {
            throw new \RuntimeException('Bad credentials');
        }
        $this->credentials = new RegisterCredentialsModel($this->username, $password);

        return $this->credentials;
    }

    public function contentToString(): string {
        $this->setMessage($this->credentials->getIssueCode());
        return $this->generateRegisterUser($this->message, $this->credentials->getUsername());
    }

    private function getUsername() {
        return $_POST[$this->constants::getRegisterName()];
    }

    public function userTryToRegister(): bool {
        if(isset($_POST[$this->constants::getRegister()])){
            return true;
        }
        return false;
    }

    private function getPassword() {
        return $_POST[$this->constants::getRegisterPassword()];
    }

    private function getIdenticalPassword() {
        return $_POST[$this->constants::getRegisterSamePassword()];

    }

    public function setMessage(int $code): void {
        if($code === 11){
            $this->message = 'Username has too few characters, at least 3 characters.<br>';
        }
        if($code === 12){
            $this->message .= 'Password has too few characters, at least 6 characters.<br>';
        }
        if($code === 13){
            $this->message = 'Passwords do not match.';
        }
        if($code === 14){
            $this->message = 'Username contains invalid characters.';
        }
        if($code === 3){
            $this->message = 'User exists, pick another username.';
        }
    }

}