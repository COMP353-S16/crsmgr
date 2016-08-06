<?php

class WebUser
{

    /**
     * @var User
     */
    static private $_User;

    /**
     * WebUser constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param User $user
     */
    public static function setUser(User $user)
    {
        self::$_User = $user;
    }


    /**
     * @return User returns the user
     */
    public static function getUser()
    {
        return self::$_User;
    }

    /**
     * @param bool $redirect if redirect is set to true, the user will be redirected to the home page. To be used on HTML pages.
     *
     * @return bool returns true if the user is still logged in
     */
    public static function isLoggedIn($redirect = false)
    {
        $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

        $loggegIn = (!empty($_SESSION) && isset($_SESSION['uid']));
        if ($loggegIn)
        {
            return true;
        }
        else
        {
            if ($redirect)
            {
                header("location: " . $root . '?l=0&u=' . time());

            }

            return false;
        }

    }
}