<?php

/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/13/2016
 * Time: 9:44 PM
 */
class User
{

    protected $_uid;
    protected $_username;
    protected $_firstName;
    protected $_lastName;
    protected $_email;
    protected $_privilege;

    public function __construct($uid)
    {
        $this->_uid = $uid;
        $this->fetchUserInfo();
    }

    private function fetchUserInfo()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Users WHERE uid=:uid");
        $query->bindValue(":uid", $this->_uid);
        $query->execute();
        $user = $query->fetch();

        $this->_username = $user['username'];
        $this->_firstName = $user['firstName'];
        $this->_lastName = $user['lastName'];
        $this->_email = $user['email'];
        $this->_privilege = $user['privilege'];
    }



    public function getUsername()
    {
        return $this->_username;
    }

    public function getUid()
    {
        return $this->_uid;
    }

    public function getFirstName()
    {
        return $this->_firstName;
    }

    public function getLastName()
    {
        return $this->_lastName;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function getPrivilege()
    {
        return $this->_privilege;
    }

    public function isStudent()
    {
        return $this->_privilege == 0;
    }

    public function isProf()
    {
        return $this->_privilege == 2;
    }

    public function isTa()
    {
        return $this->_privilege == 1;
    }

    public function isSysAdmin()
    {
        return $this->_privilege == 4;
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }


}