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

    public static function isLoggedIn() {
        return (isset($_SESSION['username']) && isset($_SESSION['uid']) || !empty($_SESSION));
    }

    /**
     * @return User
     */
    public static function getUser() {
        return self::$_User;
    }
}