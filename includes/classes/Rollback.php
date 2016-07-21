<?php

/**
 * Created by PhpStorm.
 * User: dimitri
 * Date: 2016-07-20
 * Time: 7:45 PM
 */
class Rollback
{

    private $_fid;

    private $_vid;

    /**
     * Rollback constructor.
     * @param $fid file id
     * @param $vid to version id
     */
    public function __construct($fid, $vid)
    {
        $this->_fid = $fid;
        $this->_vid = $vid;
    }

    public function rollback()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("DELETE FROM Versions WHERE fid =:fid AND vid > :vid");
        $query->bindValue(":fid", $this->_fid);
        $query->bindValue(":vid", $this->_vid);
        return $query->execute();


    }

}