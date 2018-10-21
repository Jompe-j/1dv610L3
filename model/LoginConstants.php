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
}