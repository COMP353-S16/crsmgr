<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/19/2016
 * Time: 1:20 PM
 */
class DeletedFiles
{

    private $_fid;

    private $_data;

    public function __construct($fid)
    {
        $this->_fid = $fid;
        $this->extract();
    }

    private function extract()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM DeletedFiles WHERE fid=:fid LIMIT 1");
        $query->bindValue(":fid", $this->_fid);
        $query->execute();
        $this->_data = $query->fetch();
    }

    public function getDateDeleted()
    {
        return $this->_data['dateDelete'];
    }

    public function getExpiryDate()
    {
        return $this->_data['expiresOn'];
    }

    public function isExpired()
    {
        return (strtotime(time()) >= strtotime($this->getExpiryDate()));
    }
}