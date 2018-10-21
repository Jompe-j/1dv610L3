<?php

namespace model;


class RegisterConstants {
    private static $toRegister = 'register';
    private static $Register = 'RegisterView::Register';
    private static $registerName = 'RegisterView::UserName';
    private static $registerPassword = 'RegisterView::Password';
    private static $registerSamePassword = 'RegisterView::PasswordRepeat';
    private static $registerMessage = 'RegisterView::Message';

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