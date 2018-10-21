<?php

namespace model;

class LoginDbModel
{
    private $dbSetting;
    private $connection;
    private $selectPassword = 'SELECT password FROM userpass WHERE name = ?';
    private $selectName =  'SELECT name FROM userpass WHERE name = ?';
    private $selectAll = 'SELECT * FROM userpass WHERE name = ?';
    private $updateToken = 'UPDATE userpass SET token = ? WHERE userpass . name = ?';
    private $updateExpiration = 'UPDATE userpass SET expiration = ? WHERE userpass . name = ?';
    private $insertNewUser = 'INSERT INTO userpass (id, name, password, token, expiration) VALUES (NULL, ?, ?, NULL, NULL)';
    private $preparedStatement;

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
        $foundPassword = $this->fetchFromSelected($this->selectPassword, [$username]);

        if(!$foundPassword){
            return false;
        }
         return $this->comparePassword($passwordFromUser, $foundPassword["password"]);
    }

    public function userExist($username): void {
        $existingUser = $this->fetchFromSelected($this->selectName, [$username]);

        if($existingUser){
            return;
        }
        throw new \InvalidArgumentException("User Does not exist", 3);
    }

    public function validateUserCookie($username, $cookieToken): void {

        $foundRow = $this->fetchFromSelected($this->selectAll, [$username]);

        $foundToken = $foundRow['token'];
        $foundExpire = $foundRow['expiration'];

        if ($foundToken !== $cookieToken || $foundExpire < time()){
            $code = 5;
            throw new \InvalidArgumentException('Not valid cookie!', $code);
        }
    }

    public function setTokenToDb (string $username, string $token, string $expiration): void
    {
        $this->prepareStatement($this->updateToken);
        $this->preparedStatement->execute([$token, $username]);

        $this->prepareStatement($this->updateExpiration);
        $this->preparedStatement->execute([$expiration, $username]);
    }

    public function insertUser($username, $password): void
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $this->prepareStatement($this->insertNewUser);
        $this->preparedStatement->execute([$username, $password_hash]);
    }

    private function comparePassword($passwordFromUser, $foundPassword): ?bool
    {
        $arePasswordsMatching = password_verify($passwordFromUser, $foundPassword);
        if(!$arePasswordsMatching){
            throw new \InvalidArgumentException('comparePassword() fail', 3);
        }

        if ($arePasswordsMatching){
            return true;
        }

        throw new \InvalidArgumentException('comparePassword() fail', 3);
    }

    private function prepareStatement(string $SQLStatemnte): void {
        $this->preparedStatement = $this->connection->prepare($SQLStatemnte);
    }

    private function fetchFromSelected(string $SQLString, array $array) {
        $this->prepareStatement($SQLString);
        $this->preparedStatement->execute($array);
        return $this->preparedStatement->fetch();
    }
}