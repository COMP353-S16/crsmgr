<?php

/**
 * Created by PhpStorm.
 * User: dimitri
 * Date: 2016-08-03
 * Time: 1:09 AM
 */
class UserFactory
{

    private function __construct()
    {

    }

    public static function create($uid)
    {
        $pdo = Registry::getConnection();
        $query=$pdo->prepare("SELECT privilege FROM Users WHERE uid=:uid LIMIT 1");
        $query->bindValue(":uid", $uid);
        $data= $query->fetch();
        if($data['privilege'] == 0)
        {
            return new Student($uid);

        }
        return new User($uid);
    }
}