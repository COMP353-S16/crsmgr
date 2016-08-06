<?php

class DeleteGroupDeliverable
{

    /**
     * @var
     */
    private $_gid;

    /**
     * @var array
     */
    private $_dids = array();

    /**
     * @var array
     */
    private $_errors = array();

    /**
     * DeleteGroupDeliverable constructor.
     *
     * @param $gid
     * @param array $dids
     */
    public function __construct($gid, $dids = array())
    {
        $this->_gid = $gid;
        $this->_dids = $dids;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Validates information
     */
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


    /**
     * @return array returns the array of group ids
     */
    public function getDids()
    {
        return $this->_dids;
    }

    /**
     * @param $dids array sets deliverable ids
     */
    public function setDids(array $dids)
    {
        $this->_dids = $dids;
    }

    /**
     * @param $did int add a single deliverable id to the list
     */
    public function addDid($did)
    {
        $this->_dids[] = $did;
    }

    /**
     * @return bool returns true if the deletion was successful
     */
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