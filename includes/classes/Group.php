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
    protected $_cid;
    protected $_creatorId;
    protected $_gName;
    
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
        
        $this->_cid = $group['cid'];
        $this->_leaderId = $group['leaderId'];
        $this->_creatorId = $group['creatorId'];
        $this->_gName = $group['gName'];
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
    public function getCid()
    {
        return $this->_cid;
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
}