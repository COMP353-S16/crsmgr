<?php

/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/20/2016
 * Time: 12:05 PM
 */
class WebUser
{
    /**
     * @var User
     */
    static private $_User;

    private function __construct()
    {
    }

    /**
     * @param User $user
     */
    public static function setUser(User $user) {
        self::$_User = $user;
    }



    /**
     * @return User
     */
    public static function getUser() {
        return self::$_User;
    }

    public static function isLoggedIn($redirect = false)
    {
        $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

        $loggegIn = (!empty($_SESSION) && isset($_SESSION['uid']));
        if($loggegIn)
        {
            return true;
        }
        else
        {
            if($redirect)
            {
                header("location: " . $root . '?l=0&u=' . time());

            }
            return false;
        }

    }
}