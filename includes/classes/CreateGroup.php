<?php

class CreateGroup extends NewGroup
{

    /**
     * @var
     */
    private $_creatorId;

    /**
     * CreateGroup constructor.
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function validate()
    {
        if ($this->getSemesterId() == null)
        {
            $this->setError("Semester must be set");
        }
        if (empty($this->getGroupName()))
        {
            $this->setError("Group must have a name!");
        }
        if (empty($this->getUids()))
        {
            $this->setError("Group must contain at least one student");
        }
        if ($this->getLeaderId() == null || $this->getErrors() == "")
        {
            $this->setError("Must provide a leader");
        }
        if (!is_numeric($this->getMaxBandwidth()) || $this->getMaxBandwidth() == null)
        {
            $this->setError("Please enter a valid bandwidth number");
        }
        else if ($this->getMaxBandwidth() < 1)
        {
            $this->setError("Bandwidth should be at least 1 MB");
        }
    }

    /**
     * @param $id
     */
    public function setCreatorId($id)
    {
        $this->_creatorId = $id;
    }


    /**
     * @return bool
     */
    public function create()
    {
        $pdo = Registry::getConnection();
        $this->validate();
        if (!empty($this->getErrors()))
        {
            return false;
        }
        try
        {
            $pdo->beginTransaction();

            $query = $pdo->prepare("INSERT INTO Groups (leaderId, gName, creatorId, maxUploadsSize, sid) VALUES (:leaderId, :gname, :creatorId, :max, :sid)");
            $query->bindValue(":leaderId", $this->getLeaderId());
            $query->bindValue(":gname", $this->getGroupName());
            $query->bindValue(":creatorId", $this->_creatorId);
            $query->bindValue(":sid", $this->getSemesterId());
            $query->bindValue(":max", $this->getMaxBandwidth());
            $query->execute();

            $lastInsert = $pdo->lastInsertId();

            $students = $this->getUids();
            foreach ($students as $uid)
            {
                $query2 = $pdo->prepare("INSERT INTO GroupMembers (uid, gid, sid) VALUES (:uid, :gid, :sid)");
                $query2->bindValue(":uid", $uid);
                $query2->bindValue(":gid", $lastInsert);
                $query2->bindValue(":sid", $this->getSemesterId());
                $query2->execute();
            }

            return $pdo->commit();
        }
        catch (Exception $e)
        {

            $pdo->rollBack();
            $this->setError($e->getMessage() . ' in file ' . $e->getFile() . ' line ' . $e->getLine());
        }
    }
}