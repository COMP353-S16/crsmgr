<?php

/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 7/28/2016
 * Time: 2:27 AM
 */
class DownloadFile
{
    /**
     * @var Version
     */
    private $_Version;
    /**
     * @var User
     */
    private $_User;

    public function __construct(Version $version, User $user)
    {
        $this->_Version = $version;
        $this->_User = $user;
    }

    public function download()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("INSERT INTO Downloads (vid, uid, downloadDate) VALUES (:vid, :uid, NOW())");
        $query->bindValue(":vid", $this->_Version->getVersionId());
        $query->bindValue(":uid", $this->_User->getUid());
        return $query->execute();
    }
}