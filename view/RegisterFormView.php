<?php

namespace view;

use model\LoginCredentialsModel;
use model\RegisterConstants;
use model\RegisterCredentialsModel;

class RegisterFormView implements IContentView

{
    private $message = '';
    private $constants;
    private $username = '';
    private $validator;
    private $credentials;

    public function __construct() {
    $this->constants = new RegisterConstants();
    $this->validator = new RegisterCredentialsValidator();
    $this->credentials = new LoginCredentialsModel();
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
        return $this->generateRegisterUser($this->message);
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

    private function getOneTimeUsername(): string {
        $username = $this->username;
        $this->username = '';
        return $username;
    }

    private function getUsername(): string {
        return $_POST[$this->constants::getRegisterName()];
    }

    public function userTryToRegister(): bool {
        return isset($_POST[$this->constants::getRegister()]);
    }

    public function isRegistering(): bool {
        return isset($_GET[$this->constants::getToRegister()]);
    }

    private function getPassword(): string {
        return $_POST[$this->constants::getRegisterPassword()];
    }

    private function getIdenticalPassword(): string {
        return $_POST[$this->constants::getRegisterSamePassword()];
    }

    private function generateRegisterUser(string $message): string {
        return ' 
			<form method="post" action="?register"> 
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="'. $this->constants::getRegisterMessage().'">' . $message . '</p>
					
					<label for="' . $this->constants::getRegisterName() . '">Username :</label>
					<input type="text" id="'.$this->constants::getRegisterName().'" name="' . $this->constants::getRegisterName() . '" value="' . $this->getOneTimeUsername() . '" />

					<label for="' . $this->constants::getRegisterPassword() . '">Password :</label>
					<input type="password" id="'.$this->constants::getRegisterPassword().'" name="' . $this->constants::getRegisterPassword() . '" />
					
					<label for="' . $this->constants::getRegisterSamePassword() . '">Repeat password :</label>
					<input type="password" id="'.$this->constants::getRegisterSamePassword().'" name="' . $this->constants::getRegisterSamePassword() . '" />
					
					<input type="submit" name="' . $this->constants::getRegister() . '" value="' . $this->constants::getToRegister() . '" />
				</fieldset>
			</form>
		';
    }
}