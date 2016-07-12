<?php


class CoreConfig
{
    private static $confArray = null;

    public static function applySettings($settings)
    {
        self::$confArray = $settings;

    }

    public static function settings()
    {
        return self::$confArray;
    }

}


