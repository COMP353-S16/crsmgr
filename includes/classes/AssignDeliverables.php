<?php

class AssignDeliverables
{

    private $_dids = array();
    private $_gid;
    private $_errors = array();
    public function __construct($gid)
    {

        $this->_gid = $gid;
    }

    public function addDid($did)
    {
        $this->_dids[] = $did;
    }

    private function validate()
    {
        if(empty($this->_dids))
        {
            $this->_errors[] = "No deliverables selected";

        }

        if($this->_gid == null || $this->_gid == "")
        {
            $this->_errors[] = "No group found";
        }
    }

    public function setDids(array $dids)
    {
        $this->_dids = $dids;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function assign()
    {
        $pdo = Registry::getConnection();
        $this->validate();
        if(!empty($this->_errors))
            return false;
        try
        {
            $pdo->beginTransaction();

            foreach($this->_dids as $did)
            {
                $query = $pdo->prepare("INSERT INTO GroupDeliverables VALUES (:gid, :did) ");
                $query->bindValue(":gid", $this->_gid);
                $query->bindValue(":did", $did);
                $query->execute();
            }

            return $pdo->commit();
        }
        catch(Exception $e)
        {
            $pdo->rollBack();
            $this->_errors[] = $e->getMessage();
        }

        return false;
    }
}