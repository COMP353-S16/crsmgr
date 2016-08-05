<?php

/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/18/2016
 * Time: 7:11 PM
 */
class StudentInfo extends Student
{
    protected $_nb_files_uploaded;

    public function __construct($uid)
    {
        parent::__construct($uid);
    }

    public function getNbOfFilesUploaded()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT COUNT(*) AS TOTAL FROM Versions WHERE uploaderId=:uid");
        $query->bindValue(":uid", $this->_uid);
        $query->execute();
        $data = $query->fetch();

        return $data["TOTAL"];
    }

    public function getNbOfFilesDownloaded()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT COUNT(*) AS TOTAL FROM Downloads WHERE uid=:uid");
        $query->bindValue(":uid", $this->_uid);
        $query->execute();
        $data = $query->fetch();

        return $data["TOTAL"];
    }

    public function getLastUploadedFile()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT f.*, v.uploadDate FROM Versions v LEFT JOIN Files f ON f.fid = v.fid 
                                WHERE uploaderId=:uid ORDER BY v.fid DESC LIMIT 1");
        $query->bindValue(":uid", $this->_uid);
        $query->execute();

        if ($query->rowCount() <= 0)
        {
            return "";
        }

        $data = $query->fetch();


        $File = new Files($data);
        $uploadDate = $File->getLatestVersion()->getUploadDate();
        $fileName = $File->getFileName();
        $fileExtension = $File->getFileExtension();

        return $fileName . '.' . $fileExtension . ' (' . $uploadDate . ')';
    }
}