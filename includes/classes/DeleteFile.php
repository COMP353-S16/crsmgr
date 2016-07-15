<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/15/2016
 * Time: 1:10 PM
 */
class DeleteFile
{
    private $_fid;

    /**
     * @var DFile
     */
    private $_DFile;

    public function __construct($fid)
    {
        $this->_fid = $fid;

        $this->_DFile = new DFile($fid);
    }

    private function deleteDirVersions()
    {
        
    }

    private function getAllVersionFileIds()
    {
        return $this->_DFile->getFileVersions();
    }

    public function delete()
    {
        $pdo = Registry::getConnection();

    }

}