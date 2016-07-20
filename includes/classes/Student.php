<?php

/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/20/2016
 * Time: 2:36 PM
 */
class Student extends User
{
    protected $_gid;

    public function __construct($uid)
    {
        parent::__construct($uid);
        $this->fetchGroupId();
    }

    private function fetchGroupId() {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT g.gid FROM Students s, Users u, Groups g
                                WHERE u.uid=:uid AND s.uid = u.uid AND s.gid = g.gid");
        $query->bindValue("uid", $this->_uid);
        $query->execute();
        $data = $query->fetch();

        $this->_gid = $data['gid'];
    }

    public function getGroupId() {
        return $this->_gid;
    }

    public function isInGroup($gid) {
        return $this->getGroupId() == $gid;
    }
}