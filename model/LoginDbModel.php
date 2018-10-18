<?php

namespace model;

class LoginDbModel
{
    private $dbSetting;
    private $connection;

    public function connectToDb()
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
           $this->connection = new \PDO($dsn, $username, $password, $options);

        } catch (\PDOException $exception) {
            throw new \PDOException($exception->getMessage(), (int)$exception->getCode());

        }


    }

    public function matchCredentials($username, $passwordFromUser): ?bool
    {
        $preparedStatement = $this->connection->prepare('SELECT password FROM userpass WHERE name = ?');
        $preparedStatement->execute([$username]);
        $foundPassword = $preparedStatement->fetch();

        if(!$foundPassword){
            return false;
        }

         return $this->comparePassword($passwordFromUser, $foundPassword["password"]);
    }

    public function userExist($username): void {
        $preparedStatement = $this->connection->prepare('SELECT name FROM userpass WHERE name = ?');
        $preparedStatement->execute([$username]);

        $existingUser = $preparedStatement->fetch();
        if($existingUser){
            return;
        }
        throw new \InvalidArgumentException("User Does not exist", 3);
    }

    public function comparePassword($passwordFromUser, $foundPassword): ?bool
    {
        $arePasswordsMatching = password_verify($passwordFromUser, $foundPassword);
        if(!$arePasswordsMatching){
            throw new \InvalidArgumentException('comparePassword() fail', 3);
        }

        if ($arePasswordsMatching){
            return true;
        }

        throw new \InvalidArgumentException('comparePassword() fail', 3);// TODO how to handle errorcodes?
    }

    public function validateUserCookie($username, $cookieToken): void {
        $preparedStatement = $this->connection->prepare('SELECT * FROM userpass WHERE name = ?');
        $preparedStatement->execute([$username]);
        $foundRow = $preparedStatement->fetch();
        $foundToken = $foundRow['token'];
        $foundExpire = $foundRow['expiration'];

        if ($foundToken !== $cookieToken || $foundExpire < time()){
            $code = 5;
            throw new \InvalidArgumentException('Not valid cookie!', $code);
        }

    }

    public function setTokenToDb ($username, $token, $expiration): void
    {

            $updateToken = $this->connection->prepare('UPDATE userpass SET token = ? WHERE userpass . name = ?');
            $updateToken->execute([$token, $username]);

            $updateExpiration = $this->connection->prepare('UPDATE userpass SET expiration = ? WHERE userpass . name = ?');
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
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $preparedStatement = $this->connection->prepare('INSERT INTO userpass (id, name, password, token, expiration) VALUES (NULL, ?, ?, NULL, NULL)');
        $preparedStatement->execute([$username, $password_hash]);
    }



}