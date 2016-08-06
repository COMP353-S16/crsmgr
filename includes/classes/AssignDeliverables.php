<?php

/**
 * Class AssignDeliverables
 */
class AssignDeliverables
{

    /**
     * @var array
     */
    private $_dids = array();

    /**
     * @var
     */
    private $_gid;

    /**
     * @var array
     */
    private $_errors = array();

    /**
     * AssignDeliverables constructor.
     *
     * @param $gid
     */
    public function __construct($gid)
    {

        $this->_gid = $gid;
    }

    /**
     * @param $did
     */
    public function addDid($did)
    {
        $this->_dids[] = $did;
    }

    /**
     *
     */
    private function validate()
    {
        if (empty($this->_dids))
        {
            $this->_errors[] = "No deliverables selected";

        }

        if ($this->_gid == null || $this->_gid == "")
        {
            $this->_errors[] = "No group found";
        }
    }

    /**
     * @param array $dids
     */
    public function setDids(array $dids)
    {
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
     * @return bool
     */
    public function assign()
    {
        $pdo = Registry::getConnection();
        $this->validate();
        if (!empty($this->_errors))
        {
            return false;
        }
        try
        {
            $pdo->beginTransaction();

            foreach ($this->_dids as $did)
            {
                $query = $pdo->prepare("INSERT INTO GroupDeliverables VALUES (:gid, :did) ");
                $query->bindValue(":gid", $this->_gid);
                $query->bindValue(":did", $did);
                $query->execute();
            }

            return $pdo->commit();
        }
        catch (Exception $e)
        {
            $pdo->rollBack();
            $this->_errors[] = $e->getMessage();
        }

        return false;
    }
}