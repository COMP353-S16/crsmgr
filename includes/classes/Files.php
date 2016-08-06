<?php

/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 7/19/2016
 * Time: 7:29 PM
 */
class Files
{

    /**
     * @var mixed
     */
    private $_fid;

    /**
     * @type array contains the file's data information
     */
    private $_file;

    /**
     * @type array contains the version information
     */
    private $_versions;


    /**
     * FFiles constructor.
     *
     * @param $file
     */
    public function __construct($file)
    {
        $this->_file = $file;
        $this->_fid = $this->_file['fid'];
        $this->getFileVersions();
    }


    /**
     * extracts all related versions to file
     */
    private function getFileVersions()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Versions WHERE fid = :fid");
        $query->bindValue(":fid", $this->_fid);
        $query->execute();
        $this->_versions = $query->fetchAll();
    }

    /**
     * @return int returns the file id
     */
    public function getId()
    {
        return $this->_file['fid'];
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

        foreach ($this->_versions as $i => $d)
        {
            $vid = $this->_versions[$i]['vid'];
            $a[] = new Version($this->_versions, $vid);
        }

        return $a;
    }

    /**
     * @return int returns the total number of revisions for the file
     */
    public function getNbOfVersions()
    {
        return count($this->_versions);
    }

    /**
     * @param $id
     *
     * @return Version returns a Version object based on the version id given
     */
    public function getVersionById($id)
    {
        return new Version($this->_versions, $id);
    }

    /**
     * @return int returns the earliest version id (first) ever uploaded
     */
    public function getEarliestVersionId()
    {
        $vids = array();

        foreach ($this->_versions as $i => $d)
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
        foreach ($versions as $Version)
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
        return new Version($this->_versions, $this->getEarliestVersionId());
    }


    /**
     * @return int returns the last upload version's id
     */
    public function getLatestVersionId()
    {

        $vids = array();
        foreach ($this->_versions as $i => $d)
        {

            //echo $d['vid'] . '  ' . $this->_fid .'<br>';
            $vids[] = $d['vid'];
        }


        return max($vids);
    }


    /**
     * @param $name
     *
     * @return bool
     */
    public static function isValidFileName($name)
    {
        return (strpbrk($name, "\\/?*:|\"<>") === FALSE);
    }

    /**
     * @return Version returns the latest Version object
     */
    public function getLatestVersion()
    {
        return new Version($this->_versions, $this->getLatestVersionId());
    }


    /**
     * @return int returns the number of existing revisions
     */
    public function getNumberOfRevisions()
    {
        return count($this->_versions);
    }

    public function getMime()
    {
        return $this->_file['mime'];
    }
}