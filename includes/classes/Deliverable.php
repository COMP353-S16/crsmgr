<?php

class Deliverable
{

    /**
     * @var
     */
    protected $_did;

    /**
     * @var
     */
    protected $_dName;

    /**
     * @var
     */
    protected $_startDate;

    /**
     * @var
     */
    protected $_endDate;

    /**
     * @var
     */
    protected $_sid;

    /**
     * Deliverable constructor.
     *
     * @param $did
     */
    public function __construct($did)
    {
        $this->_did = $did;
        $this->extractDeliverableInfo();
    }

    /**
     * Extracts deliverable information
     */
    private function extractDeliverableInfo()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Deliverables WHERE did=:did LIMIT 1");
        $query->bindValue("did", $this->_did);
        $query->execute();
        $deliverable = $query->fetch();

        // $this->_cid = $deliverable['cid'];
        $this->_dName = $deliverable['dName'];
        $this->_startDate = $deliverable['startDate'];
        $this->_endDate = $deliverable['endDate'];
        $this->_sid = $deliverable['sid'];
    }

    /**
     * @return int returns the semester id
     */
    public function getSemesterId()
    {
        return $this->_sid;
    }

    /**
     * @return int returns the deliverable id
     */
    public function getDid()
    {

        return $this->_did;
    }


    /**
     * @return string returns deliverable name
     */
    public function getDName()
    {
        return $this->_dName;
    }

    /**
     * @return mixed returns the start date deliverable
     */
    public function getStartDate()
    {
        return $this->_startDate;
    }

    /**
     * @return mixed returns the end date of the deliverable
     */
    public function getEndDate()
    {
        return $this->_endDate;
    }

    /**
     * @return bool returns true if the deliverable is open; i.e. users can still upload files to it
     */
    public function isOpen()
    {
        return strtotime(date("Y-m-d H:i:s")) <= strtotime($this->getEndDate());
    }
}