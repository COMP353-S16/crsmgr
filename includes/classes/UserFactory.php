<?php

class UserFactory
{

    /**
     * UserFactory constructor.
     */
    private function __construct()
    {

    }

    /**
     * @param $uid
     *
     * @return \Student|\User returns the proper user type based on privilege
     */
    public static function create($uid)
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT privilege FROM Users WHERE uid=:uid LIMIT 1");
        $query->bindValue(":uid", $uid);
        $data = $query->fetch();
        if ($data['privilege'] == 0)
        {
            return new Student($uid);

        }

        return new User($uid);
    }
}