<?php


namespace model;


class RegisterModel
{
    private $credentials;

    public function formAttemptRegistration(\model\RegisterCredentialsModel $registerCredentials)
    {
        $this->credentials = $registerCredentials;
        $success = false;
        $message = '';
        $username = $this->credentials->getUsername();

        if (strlen($this->credentials->getUsername()) <= 3 || strlen($this->credentials->getPassword()) <= 6 ){
            if(strlen($this->credentials->getUsername()) <= 3){
                $message .= 'Username has too few characters, at least 3 characters.<br>';
            }

            if(strlen($this->credentials->getPassword()) <= 6) {
                $message .= 'Password has too few characters, at least 6 characters.<br>';
            }
            return new \model\RegisterAttempt(false, $username, $message);
        }

        if($this->credentials->getPassword() !== $this->credentials->getSamePassword()){
            $message = 'Passwords do not match.';

            return new \model\RegisterAttempt(false, $username, $message);
        }

        $patter = '/<.*?\>/';
        $count = 0;
        $match = preg_replace($patter, '', $username, -1,$count);

        if($count !== 0){
            $message = 'Username contains invalid characters.';
            return new \model\RegisterAttempt(false, $match, $message);
        }

        if($this->isUsernameAvailable()){
            $this->registerUser();
            unset($_GET[\model\LoginConstants::getToRegister()]);
            $message = 'Registered new user.';
            return new \model\RegisterAttempt(true, $username, $message);
        }
        $message = 'User exists, pick another username.';
        return new \model\RegisterAttempt(false, $username, $message);
    }

    private function isUsernameAvailable(){
        $pdo = new \model\LoginDbModel();
            $userExist = $pdo->userExist($this->credentials->getUsername());
            if(!$userExist){
                return true;
            }
            return false;
    }

    private function registerUser(){
        $pdo = new \model\LoginDbModel();
        $pdo->insertUser($this->credentials->getUsername(), $this->credentials->getPassword());
    }
}