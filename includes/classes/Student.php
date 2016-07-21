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
    protected $_sid;

    public function __construct($uid)
    {
        parent::__construct($uid);
        $this->fetchGroupId();
    }

    private function fetchGroupId()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT s.* FROM Students s, Users u, Groups g, Sections sc
                                WHERE u.uid=:uid AND s.uid = u.uid AND s.gid = g.gid AND s.sid = sc.sid");
        $query->bindValue(":uid", $this->_uid);
        $query->execute();
        $data = $query->fetch();

        $this->_gid = $data['gid'];
        $this->_sid = $data['sid'];
    }

    /**
     * @return StudentInfo
     */
    public function getStudentInfo()
    {
        return new StudentInfo($this->_uid);
    }

    /**
     * @return mixed
     */
    public function getSid()
    {
        return $this->_sid;
    }

    /**
     * @return mixed
     */
    public function getGid()
    {
        return $this->_gid;
    }
}