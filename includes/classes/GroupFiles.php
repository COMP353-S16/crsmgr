<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/17/2016
 * Time: 1:10 PM
 */
class GroupFiles
{

    private $_gid;

    private $_files;
    
    private $_deletedFiles;

    public function __construct($gid)
    {
        $this->_gid = $gid;
        $this->extract();
        $this->getDeletedFiles();

    }


    private function extract()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT fid FROM Files WHERE gid = :gid");
        $query->bindValue(":gid", $this->_gid);
        $query->execute();
        $this->_files = $query->fetchAll();

    }

    private function getDeletedFiles()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM DeletedFiles d LEFT JOIN Files f ON d.fid = f.fid WHERE f.gid=:gid");
        $query->execute(array(":gid" => $this->_gid));
        $this->_deletedFiles = $query->fetchAll();
    }

    public function getFileById($fid)
    {
        return new Files($fid);
    }

    public function getFileIds()
    {
        $fids = array();
        foreach($this->_files as $i => $file)
        {
            
            $fids[] = $file['fid'];
        }
        return $fids;
    }

    public function getDeletedFileIds()
    {
        $fids = array();
        foreach($this->_deletedFiles as $i => $file)
        {

            $fids[] = $file['fid'];
        }
        return $fids;
    }

    public function isDeleted($fid)
    {
        foreach($this->_deletedFiles as $fileData)
        {
            if($fileData['fid'] == $fid)
                return true;
        }
        return false;
    }


    

    public function getNumberOfFiles()
    {
        return count($this->_files);
    }
}