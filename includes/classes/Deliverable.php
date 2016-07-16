<?php

/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/15/2016
 * Time: 12:36 AM
 */
class Deliverable
{
    protected $_did;
    protected $_cid;
    protected $_dName;
    protected $_startDate;
    protected $_endDate;
    
    public function __construct($did)
    {
        $this->_did = $did;
        $this->fetchDeliverableInfo();
    }
    
    private function fetchDeliverableInfo() {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Deliverables WHERE did=:did");
        $query->bindValue("did", $this->_did);
        $query->execute();
        $deliverable = $query->fetch();

        $this->_cid = $deliverable['cid'];
        $this->_dName = $deliverable['dName'];
        $this->_startDate = $deliverable['startDate'];
        $this->_endDate = $deliverable['endDate'];
    }

    /**
     * @return mixed
     */
    public function getDid()
    {
        return $this->_did;
    }

    /**
     * @return mixed
     */
    public function getCid()
    {
        return $this->_cid;
    }

    /**
     * @return mixed
     */
    public function getDName()
    {
        return $this->_dName;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->_startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->_endDate;
    }
}