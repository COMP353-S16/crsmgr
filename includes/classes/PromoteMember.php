<?php

/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 7/27/2016
 * Time: 12:14 AM
 */
class PromoteMember
{
    private $_uid;
    private $_gid;
    private $_errors =array();
    public function __construct($uid, $gid)
    {
        $this->_gid = $gid;
        $this->_uid = $uid;
    }

    private function validate()
    {
        if($this->_gid == "" || $this->_uid =="")
        {
            $this->_errors[] = "Group and User required";
        }
    }


    public function getErrors()
    {
        return $this->_errors;
    }

    public function promote()
    {
        $this->validate();
        if(!empty($this->_errors))
            return false;
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("UPDATE Groups SET leaderId=:leaderId WHERE gid=:gid");
        $query->bindValue(":gid", $this->_gid);
        $query->bindValue(":leaderId", $this->_uid);
        return $query->execute();
    }

}