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

    /**
     * Group constructor.
     *
     * @param $gid group id
     */
    public function __construct($gid)
    {
        $this->_gid = $gid;
        $this->fetchGroupInfo();
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
     * @return int
     */
    public function getSid()
    {
        return $this->_sid;
    }

    /**
     * @return int
     */
    public function getCreatorId()
    {
        return $this->_creatorId;
    }

    /**
     * @return string
     */
    public function getGName()
    {
        return $this->_gName;
    }

    /**
     * @return int
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
}