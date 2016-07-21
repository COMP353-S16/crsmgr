<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/21/2016
 * Time: 12:46 PM
 */
class CreateGroup extends NewGroup
{

    private $_creatorId;

    public function __construct()
    {
    }

    public function validate()
    {
        if(empty($this->getGroupName()))
        {
            $this->setError("Group must have a name!");
        }
        if(empty($this->getUids()))
        {
            $this->setError( "Group must contain at least one student" );
        }
        if($this->getLeaderId() == null)
        {
            $this->setError( "Must provide a leader" );
        }
        if(!is_numeric($this->getMaxBandwidth()))
        {
            $this->setError("Please enter a valid bandwidth number");
        }
        else if($this->getMaxBandwidth() < 1)
        {
            $this->setError("Bandwidth should be at least 1 MB");
        }
    }

    public function setCreatorId($id)
    {
        $this->_creatorId = $id;
    }

    public function create()
    {
        $pdo = Registry::getConnection();
        try
        {
            $pdo->beginTransaction();

            $query = $pdo->prepare("INSERT INTO Groups (leaderId, gName, creatorId, maxUploadsSize) VALUES (:leaderId, :gname, :creatorId, :max)");
            $query->bindValue(":leaderId", $this->getLeaderId());
            $query->bindValue(":gname", $this->getGroupName());
            $query->bindValue(":creatorId", $this->_creatorId);
            $query->bindValue(":max", $this->getMaxBandwidth());
            $query->execute();

            $lastInsert = $pdo->lastInsertId();

            $students = $this->getUids();
            foreach($students as $uid)
            {
                $query2 = $pdo->prepare("INSERT INTO GroupMembers (uid, gid) VALUES (:uid, :gid)");
                $query2->bindValue(":uid", $uid);
                $query2->bindValue(":gid", $lastInsert);
                $query2->execute();
            }

            return $pdo->commit();
        }
        catch (Exception $e)
        {

            $pdo->rollBack();
            $this->setError($e->getMessage() . ' in file ' . $e->getFile() . ' line ' .$e->getLine());
        }
    }
}