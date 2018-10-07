<?php


namespace model;


class LoginModel
{
    private $credentials;

    public function cookieAttemptLogin(\model\LoginCredentialsModel $cookieCredentials) : LoginAttempt {
        if($this->isCookieValid($cookieCredentials->getUsername(), $cookieCredentials->getPassword())){
            $_SESSION['username'] = $cookieCredentials->getUsername();
            return new LoginAttempt(true, 'Welcome back with cookie');
        }
        return new LoginAttempt(false, 'Wrong information in cookies');
    }

    public function formAttemptLogin(\model\LoginCredentialsModel $credentialsModel) : LoginAttempt {
        $this->credentials = $credentialsModel;

        if(empty($credentialsModel->getUsername())){
            return new LoginAttempt(false, 'Username is missing');
        }

        if(empty($credentialsModel->getPassword())){
            return new LoginAttempt(false, 'Password is missing');
        }

        $attemptSuccess = $this->matchCredentials();

        if ($attemptSuccess){
            $_SESSION['username'] = $this->credentials->getUsername();
            if ($this->credentials->getWantCookies()) {
                $this->setCookies();

                return new LoginAttempt(true, 'Welcome and you will be remembered');
            }
            return new LoginAttempt(true, 'Welcome');
        }

        return new LoginAttempt(false, 'Wrong name or password');
    }




    public function isLoggedIn(): bool {
        return isset($_SESSION['username']);
    }

    public function logOut(): void {
        setcookie(\model\LoginConstants::getCookieName(), null, -1);
        setcookie(\model\LoginConstants::getCookiePassword(), null, -1);
        session_unset();
        session_destroy();
    }

    private function matchCredentials(): bool {
        $pdo = new \model\LoginDbModel();
        $matchingCredentials = $pdo->providedCredentialsMatchDatabase($this->credentials->getUsername(), $this->credentials->getPassword());
        if (!$matchingCredentials) {
            return false;
        }

        if ($matchingCredentials) {
            return true;
        }

        return false;
    }

    private function setCookies(){
        $username = $this->credentials->getUsername();
        $expiration = time() + 86400;
        $this->setTokenToDb($username, $expiration);
        setcookie(\model\LoginConstants::getCookieName(), $username, $expiration);
        setcookie(\model\LoginConstants::getCookiePassword(), $this->getToken($username), $expiration);
    }

    private function setTokenToDb($username, $expiration)  {
        $pdo = new \model\LoginDbModel();
        $pdo->setTokenToDb($username, $expiration);
    }

    private function getToken($username) : string {
        $pdo = new \model\LoginDbModel();
        return $pdo->getToken($username);
    }

    private function isCookieValid($username, $cookieToken)
    {
        $pdo = new \model\LoginDbModel();
        return $pdo->visitorCookieIsValid($username, $cookieToken);
    }

}