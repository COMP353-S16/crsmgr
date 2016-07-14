<?php
/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/13/2016
 * Time: 9:44 PM
 */

class User {

    protected $_uid;
    protected $_username;
    protected $_firstName;
    protected $_lastName;
    protected $_email;
    protected $_privilege;

    public function __construct($username)
    {
        $this->_username = $username;
        $this->fetchUserInfo();
    }

    private function fetchUserInfo() {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Users WHERE username=:username");
        $query->bindValue(":username", $this->_username);
        $query->execute();
        $user = $query->fetch();
        $this->_uid = $user['uid'];
        $this->_firstName = $user['firstName'];
        $this->_lastName = $user['lastName'];
        $this->_email = $user['email'];
        $this->_privilege = $user['privilege'];
    }

    public function getUsername() {
        return $this->_username;
    }

    public function getUid() {
        return $this->_uid;
    }

    public function getFirstName() {
        return $this->_firstName;
    }

    public function getLastName() {
        return $this->_lastName;
    }

    public function getEmail() {
        return $this->_email;
    }

    public function getPrivilege() {
        return $this->_privilege;
    }
}