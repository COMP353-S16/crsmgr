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
     * Versions constructor.
     * @param array $versionsData this data comes from database. It is already fetched using fetchAll()
     * @param $id is the id used to fetch row
     */
    public function __construct(array $versionsData, $id)
    {
        if(empty($versionsData) || $id=="")
            throw new Exception("Version data is required");

        $this->_data = $versionsData;

        foreach($this->_data as $i => $d)
        {
            if($this->_data[$i]['vid'] == $id)
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

    public function getUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $_SERVER['HTTP_HOST']. '/fileuploads/' .CoreConfig::settings()['uploads']['upload_dir'] . $this->getSavedName();
    }
}