<?php

/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 7/27/2016
 * Time: 10:27 PM
 */
class AddMembers extends NewGroup
{

    private $_gid;

    public function __construct($gid)
    {
        $this->_gid = $gid;
    }

    public function validate()
    {
        if(empty($this->getUids()))
        {
            $this->setError( "No new students found" );
        }
    }

    public function add()
    {
        $this->validate();
        if(!empty($this->getErrors()))
            return false;

        $pdo = Registry::getConnection();
        $uids = $this->getUids();
        try
        {
            $pdo->beginTransaction();
            foreach($uids as $uid)
            {
                $query = $pdo->prepare("INSERT INTO GroupMembers (uid, gid, sid) VALUES (:uid, :gid, :sid)");
                $query->bindValue(":uid", $uid);
                $query->bindValue(":gid", $this->_gid);
                $query->bindValue(":sid", $this->getSemesterId());
                $query->execute();
            }
            return $pdo->commit();
        }
        catch(Exception $e)
        {
            $pdo->rollBack();
            $this->setError($e->getMessage());
        }

        return false;

    }
}