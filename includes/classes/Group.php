<?php

/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/15/2016
 * Time: 1:19 AM
 */
class Group
{
    protected $_gid;
    protected $_leaderId;
    protected $_sid;
    protected $_creatorId;
    protected $_gName;
    protected $_maxSize;
    protected $_groupStudents = array();
    
    public function __construct($gid)
    {
        $this->_gid = $gid;
        $this->fetchGroupInfo();
        $this->populateGroupMembers();
    }
    
    private function fetchGroupInfo() {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Groups WHERE gid=:gid");
        $query->bindValue("gid", $this->_gid);
        $query->execute();
        $group = $query->fetch();
        
        $this->_sid = $group['sid'];
        $this->_leaderId = $group['leaderId'];
        $this->_creatorId = $group['creatorId'];
        $this->_gName = $group['gName'];
        $this->_maxSize = $group['maxUploadsSize'];
    }

    /**
     * @return mixed
     */
    public function getGid()
    {
        return $this->_gid;
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
    public function getCreatorId()
    {
        return $this->_creatorId;
    }

    /**
     * @return mixed
     */
    public function getGName()
    {
        return $this->_gName;
    }

    /**
     * @return mixed
     */
    public function getLeaderId()
    {
        return $this->_leaderId;
    }

    /**
     * @return float returns maximum size of all files for group
     */
    public function getMaxUploadSize()
    {
        return $this->_maxSize;
    }

    /**
     * @return array
     */
    public function getGroupStudents()
    {
        return $this->_groupStudents;
    }

    private function populateGroupMembers()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT s.uid FROM Students s, Groups g WHERE g.gid=:gid AND s.gid=g.gid");
        $query->bindValue(":gid", $this->_gid);
        $query->execute();
        $this->_groupStudents = $query->fetchAll();
    }

    public function isInGroup($uid)
    {
        foreach ($this->_groupStudents as $student)
        {
            if($student['uid'] == $uid) {
                return true;
            }
        }

        return false;
    }
}