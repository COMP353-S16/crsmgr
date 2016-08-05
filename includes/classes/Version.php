<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/15/2016
 * Time: 2:53 PM
 */
class Version
{

    private $_version;

    private $_data;


    /**
     * Version constructor.
     *
     * @param array $versionsData
     * @param $id
     *
     * @throws Exception
     */
    public function __construct(array $versionsData, $id)
    {
        if (empty($versionsData) || $id == null)
        {
            throw new Exception("Version data is required");
        }

        $this->_data = $versionsData;

        foreach ($this->_data as $i => $d)
        {
            if ($this->_data[$i]['vid'] == $id)
            {
                $this->_version = $this->_data[$i];
                break;
            }

        }
    }

    /**
     * @return string the name given when the file was physically saved in a directory
     */
    public function getSavedName()
    {
        return $this->_version['physicalName'];
    }

    /**
     * @return float returns the file size
     */
    public function getSize()
    {
        return $this->_version['size'];
    }

    public function getFileId()
    {
        return $this->_version['fid'];
    }

    /**
     * @return string returns the date the file was uploaded last
     */
    public function getUploadDate()
    {
        return $this->_version['uploadDate'];
    }

    /**
     * @return int returns the id of the user who uploaded the version
     */
    public function getUploaderId()
    {
        return $this->_version['uploaderId'];
    }

    public function getVersionId()
    {
        return $this->_version['vid'];
    }

    public function getUploadDir()
    {
        return $this->_version['upload_dir'];
    }

    public function getData()
    {

        return $this->_version['data'];
    }

    /**
     * @return string returns the location of the file. This returns the latest file.
     */
    public function getBaseUrl()
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/fileuploads/' . $this->getUploadDir() . $this->getSavedName();
    }

    /**
     * @return string returns the location of the file relative to the webroot. This returns the latest file.
     */
    public function getUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        return $protocol . $_SERVER['HTTP_HOST'] . '/fileuploads/' . $this->getUploadDir() . $this->getSavedName();
    }


    public function getIp()
    {
        return $this->_version['ip'];
    }

}