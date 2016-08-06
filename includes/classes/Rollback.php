<?php

/**
 * Created by PhpStorm.
 * User: dimitri
 * Date: 2016-07-20
 * Time: 7:45 PM
 */
class Rollback
{

    /**
     * @var \file
     */
    private $_fid;

    /**
     * @var \to
     */
    private $_vid;

    /**
     * Rollback constructor.
     *
     * @param $fid file id
     * @param $vid to version id
     */
    public function __construct($fid, $vid)
    {
        $this->_fid = $fid;
        $this->_vid = $vid;
    }

    /**
     * @return bool
     */
    public function rollback()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("DELETE FROM Versions WHERE fid =:fid AND vid > :vid");
        $query->bindValue(":fid", $this->_fid);
        $query->bindValue(":vid", $this->_vid);

        return $query->execute();


    }

}