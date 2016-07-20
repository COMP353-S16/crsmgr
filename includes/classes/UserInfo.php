<?php

/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/18/2016
 * Time: 7:11 PM
 */
class UserInfo
{
    /**
     * @var User
     **/
    protected $_User;
    protected $_nb_files_uploaded;

    public function __construct($user)
    {
        $this->_User = $user;
    }

    public function getNbOfFilesUploaded() {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT COUNT(*) AS TOTAL FROM Versions WHERE uploaderId=:uid");
        $query->bindValue(":uid", $this->_User->getUid());
        $query->execute();
        $data = $query->fetch();

        return $data["TOTAL"];
    }

    public function getNbOfFilesDownloaded() {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT COUNT(*) AS TOTAL FROM Downloads WHERE uid=:uid");
        $query->bindValue(":uid", $this->_User->getUid());
        $query->execute();
        $data = $query->fetch();

        return $data["TOTAL"];
    }
    
    public function getLastUploadedFile() {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT fid,uploadDate FROM Versions WHERE uploaderId=:uid ORDER BY fid DESC LIMIT 1");
        $query->bindValue(":uid", $this->_User->getUid());
        $query->execute();
        $data = $query->fetch();
        $fid = $data['fid'];
        $uploadDate = $data['uploadDate'];

        $File = new Files($fid);
        return $File->getFileName().'.'.$File->getFileExtension().' ('.$uploadDate.')';
    }
}