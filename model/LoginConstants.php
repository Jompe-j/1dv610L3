<?php


namespace model;

class LoginConstants
{
    private static $login = 'LoginView::Login';
    private static $logout = 'LoginView::Logout';
    private static $name = 'LoginView::UserName';
    private static $password = 'LoginView::Password';
    private static $cookieName = 'LoginView::CookieName';
    private static $cookiePassword = 'LoginView::CookiePassword';
    private static $keep = 'LoginView::KeepMeLoggedIn';
    private static $messageId = 'LoginView::Message';
    private static $toRegister = 'register';
    private static $Register = 'RegisterView::Register';
    private static $registerName = 'RegisterView::UserName';
    private static $registerPassword = 'RegisterView::Password';
    private static $registerSamePassword = 'RegisterView::PasswordRepeat';
    private static $registerMessage = 'RegisterView::Message';

    public static function getLogin(): string {
        return self::$login;
    }

    public static function getLogout(): string {
        return self::$logout;
    }

    public static function getName(): string {
        return self::$name;
    }

    public static function getPassword(): string {
        return self::$password;
    }

    public static function getCookieName(): string {
        return self::$cookieName;
    }

    public static function getCookiePassword(): string {
        return self::$cookiePassword;
    }

    public static function getKeep(): string {
        return self::$keep;
    }

    public static function getMessageId(): string {
        return self::$messageId;
    }

    public static function getToRegister(): string
    {
        return self::$toRegister;
    }

    public static function getRegister(): string
    {
        return self::$Register;
    }

    public static function getRegisterName(): string
    {
        return self::$registerName;
    }

    public static function getRegisterPassword(): string
    {
        return self::$registerPassword;
    }

    public static function getRegisterSamePassword(): string
    {
        return self::$registerSamePassword;
    }

    public static function getRegisterMessage(): string
    {
        return self::$registerMessage;
    }


}