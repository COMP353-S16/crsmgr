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
        $this->extract();
    }

    private function extract()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Students s LEFT JOIN GroupMembers gm ON gm.uid = s.uid WHERE s.uid=:uid ");
        $query->bindValue(":uid", $this->_uid);
        $query->execute();
        $data = $query->fetch();
        $this->_sid = $data['sid'];
        $this->_gid = $data['gid'];
    }

    /**
     * @return StudentInfo
     */
    public function getStudentInfo()
    {
        return new StudentInfo($this->_uid);
    }

    public function isInGroup()
    {
        return $this->_gid != NULL || $this->_gid != "" || is_numeric($this->_gid);
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