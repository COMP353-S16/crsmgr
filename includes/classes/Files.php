<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/15/2016
 * Time: 1:14 PM
 */
class Files
{

    private $_fid;

    private $_file;

    private $_versions;

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

        $this->getFileVersions();
    }

    public function getId()
    {
        return $this->_fid;
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
     * @return array returns an instantiated object of Version for all existing versions
     */
    public function getVersions()
    {
        $a = array();
        foreach($this->_versions as $i => $d)
        {
            $vid = $this->_versions[$i]['vid'];
            $a[] = new Version($this->_versions, $vid);
        }
        return $a;
    }

    /**
     * @param $id
     * @return Version returns a Version object based on the version id given
     */
    public function getVersionById($id)
    {
        return new Version($this->_versions, $id);
    }

    /**
     * @return int returns the earliest version id (first) ever uploaded
     */
    public function getEarliestVersionsId()
    {
        $vids = array();
        foreach($this->_versions as $i => $d)
        {
            $vids[] = $this->_versions[$i]['vid'];
        }

        return min($vids);
    }

    /**
     * @return float returns the file size based on the last version
     */
    public function getSize()
    {
        return $this->getLatestVersion()->getSize();
    }


    /**
     * @return int returns the total size occupied by file and versions of file
     */
    public function getGlobalSize()
    {
        $size = 0;

        $versions = $this->getVersions();
        /**
         * @var $Version Version
        */
        foreach($versions as $Version)
        {
            $size += $Version->getSize();
        }

        return $size;
    }

    /**
     * @return Version returns the earliest Version object
     */
    public function getEarliestVersion()
    {
        return new Version($this->_versions, $this->getEarliestVersionsId());
    }


    public function getLatestVersionId()
    {
        
        $vids = array();
        foreach($this->_versions as $i => $d)
        {
            $vids[] = $this->_versions[$i]['vid'];
        }

        return max($vids);
    }

    /**
     * @return Version returns the latest Version object
     */
    public function getLatestVersion()
    {
        return new Version($this->_versions, $this->getLatestVersionId());
    }

    /**
     * extracts all related versions to file
     */
    private function getFileVersions()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Versions WHERE fid = :fid");
        $query->execute(array(":fid" => $this->_fid));
        $this->_versions = $query->fetchAll();

    }

    /**
     * @return int returns the number of existing revisions
     */
    public function getNumberOfRevisions()
    {
        return count($this->_versions);
    }

    public function getBaseUrl()
    {
       return $_SERVER['DOCUMENT_ROOT']. '/fileuploads/' .CoreConfig::settings()['uploads']['upload_dir'] . $this->getGroupId() .'/'. $this->getLatestVersion()->getSavedName();
    }

    public function getUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $_SERVER['HTTP_HOST']. '/fileuploads/' .CoreConfig::settings()['uploads']['upload_dir'] . $this->getGroupId() .'/'. $this->getLatestVersion()->getSavedName();
    }
}