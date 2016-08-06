<?php


/**
 * Class CoreConfig
 */
class CoreConfig
{

    /**
     * @var null
     */
    private static $confArray = null;

    /**
     * @param $settings
     */
    public static function applySettings($settings)
    {
        self::$confArray = $settings;

    }

    /**
     * @return null
     */
    public static function settings()
    {
        return self::$confArray;
    }

}


