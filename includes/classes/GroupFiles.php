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
        $this->extractFiles();
        $this->extractDeletedFiles();

    }


    private function extractFiles()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Files WHERE gid = :gid");
        $query->bindValue(":gid", $this->_gid);
        $query->execute();
        $this->_files = $query->fetchAll();

    }

    private function extractDeletedFiles()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM DeletedFiles d LEFT JOIN Files f ON d.fid = f.fid WHERE f.gid=:gid");
        $query->execute(array(":gid" => $this->_gid));
        $this->_deletedFiles = $query->fetchAll();
    }

    public function getFileById($fid)
    {
        foreach($this->_files as $i => $file)
        {

            if($fid == $file['fid'])
            {

                return new Files($file);
                break;
            }
        }


    }


    /**
     * @param $fid file id
     * @return DeletedFiles returns a DeletedFiles object based on fid
     */
    public function getDeletedFileById($fid)
    {
        foreach($this->_deletedFiles as $i => $fileData)
        {
            if($fileData['fid'] == $fid)
            {
                $DeletedFiles = new DeletedFiles($fileData);
                return $DeletedFiles;
            }
        }
    }


    public function getFiles()
    {
        $files = array();
        foreach($this->_files as $i => $file)
        {
            $files[] = new Files($file);
        }
        return $files;
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

    public function getDeletedFiles()
    {
        $files = array();
        foreach($this->_deletedFiles as $i => $file)
        {
            $files[] = new DeletedFiles($file);
        }
        return $files;
    }

    public function isDeleted($fid)
    {
        foreach($this->_deletedFiles as $i => $fileData)
        {
            if($fileData['fid'] == $fid)
                return true;
        }
        return false;
    }

    /**
     * @return int returns the total number of deleted files
     */
    public function getTotalDeletedFiles()
    {
        return count($this->getDeletedFileIds());
    }

    /**
     * @param $fid file id
     *
     * @return bool returns true if the file is permanently deleted: passed expiry date.
     */
    public function isPermanentDeleted($fid)
    {

        foreach($this->_deletedFiles as $i => $fileData)
        {
            if($fileData['fid'] == $fid)
            {
                $DeletedFiles = new DeletedFiles($fileData);
                return $DeletedFiles->isExpired();
            }
        }
        return false;
    }


    /**
     * @return int returns the number of distinct files. This does not account for versions.
     */
    public function getNumberOfFiles()
    {
        return count($this->_files);
    }

    /**
     * @return int returns the number of uploaded files, including versions.
     */
    public function getNbOfUploadedFiles() {
        $total = 0;
        $files = $this->getFiles();
        /**
         * @var $Files Files
         */
        foreach ($files as $Files) {
            $total += $Files->getNbOfVersions();
        }

        return $total;
    }


    /**
     * @return float|int returns the total number of megabytes the group has used for file storage
     */
    public function getUsedBandwidth()
    {
        $b = 0.0;
        $files = $this->getFiles();
        /**
         * @var $Files Files
         */
        foreach($files as $Files)
        {
            if(!$this->isPermanentDeleted($Files->getId()))
            {
                $b += $Files->getGlobalSize();
            }


        }

        return $b;
    }
}