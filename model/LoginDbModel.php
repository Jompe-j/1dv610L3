<?php

namespace model;

class LoginDbModel
{
    private $dbSetting;

    private function connectToDb(): ?\PDO
    {
        $this->dbSetting = new \settings\DbSettings();
        $host = $this->dbSetting->getHost();
        $username = $this->dbSetting->getUserName();
        $password = $this->dbSetting->getPassword();
        $dbName = $this->dbSetting->getName();
        $charset = $this->dbSetting->getCharset();

        $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
            ];
        try {
           return new \PDO($dsn, $username, $password, $options);

        } catch (\PDOException $exception) {
            throw new \PDOException($exception->getMessage(), (int)$exception->getCode());

        }
    }

    public function providedCredentialsMatchDatabase($username, $passwordFromUser): ?bool
    {

        $pdo = $this->connectToDb();
        $preparedStatement = $pdo->prepare('SELECT password FROM userpass WHERE name = ?');
        $preparedStatement->execute([$username]);
        $foundPassword = $preparedStatement->fetch();

        if(!$foundPassword){
            return false;
        }

         return $this->comparePassword($passwordFromUser, $foundPassword["password"]);
    }

    public function userExist($username){
        $pdo = $this->connectToDb();
        $preparedStatement = $pdo->prepare('SELECT name FROM userpass WHERE name = ?');
        $preparedStatement->execute([$username]);

        $existingUser = $preparedStatement->fetch();
        if(!$existingUser){
            return false;
        }
        return true;

    }

    public function comparePassword($passwordFromUser, $foundPassword): ?bool
    {
        $arePasswordsMatching = password_verify($passwordFromUser, $foundPassword);
        if(!$arePasswordsMatching){
            return false;
        }

        if ($arePasswordsMatching){
            return true;
        }

        return false;
    }

    public function visitorCookieIsValid($username, $cookieToken): bool
    {
        $pdo = $this->connectToDb();
        $preparedStatment = $pdo->prepare('SELECT * FROM userpass WHERE name = ?');
        $preparedStatment->execute([$username]);
        $foundRow = $preparedStatment->fetch();
        $foundToken = $foundRow['token'];
        $foundExpire = $foundRow['expiration'];

        if ($foundToken === $cookieToken && $foundExpire > time()){
            return true;
        }
        return false;
    }

    public function setTokenToDb ($username, $expiration): void
    {
            $token = new \model\TokenModel();
            $pdo = $this->connectToDb();
            $updateToken = $pdo->prepare('UPDATE userpass SET token = ? WHERE userpass . name = ?');
            $updateToken->execute([$token->getToken(), $username]);

            $updateExpiration = $pdo->prepare('UPDATE userpass SET expiration = ? WHERE userpass . name = ?');
            $updateExpiration->execute([$expiration, $username]);

    }

    public function getToken($username)
    {
        $pdo = $this->connectToDb();
        $preparedStatement = $pdo->prepare('SELECT token FROM userpass WHERE name = ?');
        $preparedStatement->execute([$username]);
        $result = $preparedStatement->fetch();
        return $result['token'];
    }

    public function insertUser($username, $password): void
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);;
        $pdo = $this->connectToDb();
        $preparedStatement = $pdo->prepare('INSERT INTO userpass (id, name, password, token, expiration) VALUES (NULL, ?, ?, NULL, NULL)');
        $preparedStatement->execute([$username, $password_hash]);
    }


}