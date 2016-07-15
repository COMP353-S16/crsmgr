<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/15/2016
 * Time: 1:14 PM
 */
class DFile
{

    private $_fid;

    private $_file;

    /**
     * DFile constructor.
     * @param $fid File ID
     */
    public function __construct($fid)
    {
        $this->_fid = $fid;
        $this->extract();
    }

    private function extract()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Files WHERE fid = :fid LIMIT 1");
        $query->execute(array(":fid" => $this->_fid));
        $this->_file = $query->fetch();
    }

    /**
     * @return string returns tbe original file name
     */
    public function getFileName()
    {
        return $this->_file['fName'];
    }

    /**
     * @return string returns the file extension
     */
    public function getFileExtension()
    {
        return $this->_file['fType'];
    }

    /**
     * @return int returns the deliverable id the file belongs to
     */
    public function getDeliverableId()
    {
        return $this->_file['did'];
    }

    /**
     * @return int returns the group id the file belongs to
     */
    public function getGroupId()
    {
        return $this->_file['gid'];
    }


    /**
     * @return array returns all the version ids associated with the file.
     */
    public function getFileVersions()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT vid FROM Versions WHERE fid = :fid");
        $query->execute(array(":fid" => $this->_fid));
        $data = $query->fetchAll();

        $vids = array();
        foreach($data as $fileInfo)
        {
            $vids[] = $fileInfo['vid'];
        }


        return $vids;
    }
}