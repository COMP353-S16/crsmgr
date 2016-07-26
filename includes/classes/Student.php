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

    // semester id
    protected $_sid;

    private $_sectionName;

    public function __construct($uid, $sid)
    {
        parent::__construct($uid);
        $this->_sid = $sid;
        $this->extract();
    }

    private function extract()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM
  Students s
  LEFT JOIN StudentSemester sm ON s.uid = sm.uid
  LEFT JOIN GroupMembers gm ON (gm.uid = sm.uid AND gm.sid = sm.sid) AND s.uid=:uid AND sm.sid = :sid");
        $query->bindValue(":uid", $this->_uid);
        $query->bindValue(":sid", $this->_sid);
        $query->execute();
        $data = $query->fetch();
        $this->_sid = $data['sid'];
        $this->_sectionName = $data['sectionName'];
        $this->_gid = $data['gid'];
    }

    public function setSemester($sid)
    {
        $this->_sid = $sid;
    }

    /**
     * @return StudentInfo
     */
    public function getStudentInfo()
    {
        return new StudentInfo($this->_uid, $this->_sid);
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

    public function getSectionName()
    {
        return $this->_sectionName;
    }

    /**
     * @return mixed
     */
    public function getGid()
    {
        return $this->_gid;
    }
}