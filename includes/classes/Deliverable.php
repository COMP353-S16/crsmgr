<?php

/**
 * Created by PhpStorm.
 * User: dimitri
 * Date: 2016-07-15
 * Time: 11:31 PM
 */
class Deliverable
{
    private $_did;

    private $_del;

    public function __construct($did)
    {
        $this->_did = $did;
        $this->extract();
    }
    
    private function extract()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Deliverables WHERE did = :did LIMIT 1");
        $query->execute(array(":did" => $this->_did));
        $this->_del = $query->fetch();

    }

    public function getName()
    {
        return $this->_del['dName'];
    }

    public function startDate()
    {
        return $this->_del['startDate'];
    }

    public function endDate()
    {
        return $this->_del['endDate'];
    }

    public function getCourseId()
    {
        return $this->_del['cid'];
    }


}