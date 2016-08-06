<?php

/**
 * Class DeleteDeliverable
 */
class DeleteDeliverable
{

    /**
     * @var array
     */
    private $_dids = array();

    /**
     * @var array
     */
    private $_errors = array();

    /**
     * DeleteDeliverable constructor.
     *
     * @param array $did
     */
    public function __construct($did = array())
    {
        $this->s = $did;
    }

    /**
     * @param array $dids
     */
    public function setDids(array $dids)
    {
        $this->_dids = $dids;
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
            $this->_errors[] = "Nothing to delete";
        }

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

    /**
     * @return array
     */
    public function getDids()
    {
        return $this->_dids;
    }

}