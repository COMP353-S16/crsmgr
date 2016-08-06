<?php

class User
{

    /**
     * @var
     */
    protected $_uid;

    /**
     * @var
     */
    protected $_username;

    /**
     * @var
     */
    protected $_firstName;

    /**
     * @var
     */
    protected $_lastName;

    /**
     * @var
     */
    protected $_email;

    /**
     * @var
     */
    protected $_privilege;

    /**
     * User constructor.
     *
     * @param $uid
     */
    public function __construct($uid)
    {
        $this->_uid = $uid;
        $this->fetchUserInfo();
    }

    /**
     *
     */
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


    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->_uid;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->_firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->_lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @return mixed
     */
    public function getPrivilege()
    {
        return $this->_privilege;
    }

    /**
     * @return bool
     */
    public function isStudent()
    {
        return $this->_privilege == 0;
    }

    /**
     * @return bool
     */
    public function isProf()
    {
        return $this->_privilege == 2;
    }

    /**
     * @return bool
     */
    public function isTa()
    {
        return $this->_privilege == 1;
    }

    /**
     * @return bool
     */
    public function isSysAdmin()
    {
        return $this->_privilege == 4;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }


}