<?php

namespace model;


class CalculatorConstants {
    private static $calculator = 'CalculatorView::Calculator';
    private static $hiddenExpression = 'CalculatorView::HiddenExpression';
    private static $value1 = '1';
    private static $value2 = '2';
    private static $value3 = '3';
    private static $value4 = '4';
    private static $value5 = '5';
    private static $value6 = '6';
    private static $value7 = '7';
    private static $value8 = '8';
    private static $value9 = '9';
    private static $value0 = '0';
    private static $valueAdd = '+';
    private static $valueSubtract = '-';
    private static $valueMultiply = '*';
    private static $valueDivide = '/';
    private static $valueExponent = '^';
    private static $valueReset = 'Reset';
    private static $valueEqual = '=';

    public static function getCalculator(): string {
        return self::$calculator;
    }

    public static function getHiddenExpression(): string {
        return self::$hiddenExpression;
    }

    public static function getValue1(): string {
        return self::$value1;
    }
    public static function getValue2(): string {
        return self::$value2;
    }
    public static function getValue3(): string {
        return self::$value3;
    }
    public static function getValue4(): string {
        return self::$value4;
    }
    public static function getValue5(): string {
        return self::$value5;
    }
    public static function getValue6(): string {
        return self::$value6;
    }
    public static function getValue7(): string {
        return self::$value7;
    }
    public static function getValue8(): string {
        return self::$value8;
    }
    public static function getValue9(): string {
        return self::$value9;
    }
    public static function getValue0(): string {
        return self::$value0;
    }

    public static function getValueAdd(): string {
        return self::$valueAdd;
    }

    public static function getValueSubtract(): string {
        return self::$valueSubtract;
    }

    public static function getValueMultiply(): string {
        return self::$valueMultiply;
    }

    public static function getValueDivide(): string {
        return self::$valueDivide;
    }

    public static function getValueExponent(): string {
        return self::$valueExponent;
    }

    public static function getValueReset(): string {
        return self::$valueReset;
    }

    public static function getValueEqual(): string {
        return self::$valueEqual;
    }


}