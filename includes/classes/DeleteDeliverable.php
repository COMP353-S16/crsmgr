<?php

class DeleteDeliverable
{

    private $_dids = array();
    private $_errors = array();

    public function __construct($did = array())
    {
        $this->s = $did;
    }

    public function setDids(array $dids)
    {
        $this->_dids = $dids;
    }

    public function addDid($did)
    {
        $this->_dids[] = $did;
    }

    private function validate()
    {
        if (empty($this->_dids))
        {
            $this->_errors[] = "Nothing to delete";
        }

    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function delete()
    {
        $pdo = Registry::getConnection();
        try
        {

            $pdo->beginTransaction();
            foreach ($this->_dids as $did)
            {
                $query = $pdo->prepare("DELETE FROM Deliverables WHERE did=:did");
                $query->bindValue(":did", $did);
                $query->execute();
            }

            return $pdo->commit();

        }
        catch (Exception $e)
        {
            $this->_errors[] = $e->getMessage();
            $pdo->rollBack();
        }

        return false;

    }

    public function getDids()
    {
        return $this->_dids;
    }

}