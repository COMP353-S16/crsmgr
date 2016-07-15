<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/15/2016
 * Time: 1:31 PM
 */
class DVersions
{

    private $_vid;

    private $_version;

    public function __construct($vid)
    {
        $this->_vid = $vid;
        $this->extract();
    }

    private function extract()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Versions WHERE vid = :vid LIMIT 1");
        $query->execute(array(":vid" => $this->_vid));
        $this->_version = $query->fetch();
    }

    public function getFileId()
    {
        return $this->_version['vid'];
    }

    /**
     * @return mixed returns the hash filename. This is the name that was used to save the file in the directory.
     */
    public function getSavedName()
    {
        return $this->_version['physicalName'];
    }

    /**
     * @return float returns the size of the file in bytes
     */
    public function getSize()
    {
        return $this->_vid['size'];
    }
}