<?php

class DeleteMember
{
    private $_uid;
    private $_gid;
    private $_errors = array();

    public function __construct($uid, $gid)
    {
        $this->_gid = $gid;
        $this->_uid = $uid;
    }

    public function delete()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("DELETE FROM GroupMembers WHERE gid=:gid AND uid=:uid");
        $query->bindValue(":gid", $this->_gid);
        $query->bindValue(":uid", $this->_uid);

        return $query->execute();
    }

    public function getErrors()
    {
        return $this->_errors;
    }
}