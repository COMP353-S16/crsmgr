<?php

/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 7/29/2016
 * Time: 1:21 AM
 */
class DeleteGroupDeliverable
{

    private $_gid;
    private $_dids = array();
    private $_errors = array();

    public function __construct($gid, $dids = array())
    {
        $this->_gid = $gid;
        $this->_dids = $dids;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    private function validate()
    {
        if ($this->_gid == null || $this->_gid == "")
        {
            $this->_errors[] = "No group found";
        }
        if (empty($this->_dids))
        {
            $this->_errors[] = "No deliverables found";
        }

    }


    public function getDids()
    {
        return $this->_dids;
    }

    public function setDids(array $dids)
    {
        $this->_dids = $dids;
    }

    public function addDid($did)
    {
        $this->_dids[] = $did;
    }

    public function delete()
    {
        $this->validate();
        if (!empty($this->getErrors()))
        {
            return false;
        }

        $pdo = Registry::getConnection();

        try
        {
            $pdo->beginTransaction();

            foreach ($this->_dids as $did)
            {
                $query = $pdo->prepare("DELETE FROM GroupDeliverables WHERE did=:did AND gid=:gid");
                $query->bindValue(":did", $did);
                $query->bindValue(":gid", $this->_gid);
                $query->execute();
            }

            return $pdo->commit();
        }
        catch (Exception $e)
        {
            $pdo->rollBack();
            $this->_errors[] = $e->getMessage();
        }

    }
}