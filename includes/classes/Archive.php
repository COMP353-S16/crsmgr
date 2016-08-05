<?php

class Archive
{

    /**
     * @var Group
     */
    private $_Group;

    /**
     * @var ZipArchive
     */
    private $_ZipArchive;

    /**
     * @var GroupFiles
     */
    private $_GroupFiles;

    private $_errors = array();

    private $_notices = array();

    private $_dir = 'archives/';


    private $_unique;

    /**
     * Archive constructor.
     *
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        $this->_Group = $group;
        $this->_ZipArchive = new ZipArchive();
        $this->_GroupFiles = $this->_Group->getGroupFiles();

        $this->_unique = time();
    }

    public function getGroup()
    {
        return $this->_Group;
    }

    public function setUploadDirectory($dir)
    {
        $this->_dir = $dir;
    }

    public function getUploadDirectory()
    {
        return $this->_dir;
    }


    private function validate()
    {
        if($this->_GroupFiles->getNumberOfFiles() == 0)
        {
            $this->_errors[] = "No files to archive";
        }
    }

    private function getZipName()
    {
        // can add a microtime at the end of this to make it unique every time we archive, though that wastes some space.
        return   "group_" . $this->_Group->getGid() . '_' . $this->_unique;
    }

    private function createArchive()
    {
        $totalArchive = 0;

        $totalNotFound = 0;
        /**
         * @var $Files Files
         */
        foreach($this->_GroupFiles->getFiles() as $Files)
        {
            $versions = $Files->getVersions();

            $did = $Files->getDeliverableId();

            $Deliverable = new Deliverable($did);

            $path = $Deliverable->getDName() . '/' . $Files->getFileName();
            /**
             * @var $Version Version
             */
            foreach($versions as $Version)
            {
                $fileName = $Version->getSavedName();
                $fileData = null;
                // here, we check if the file was upload directly into DB or in the File system. In any case, it grabs all of the file whether or not in db or in fs.
                if($Version->getData() == null)
                {
                    if(file_exists($Version->getBaseUrl()))
                    {
                        $fileData = file_get_contents($Version->getBaseUrl());
                    }
                    else
                    {
                        // the file was uploaded but not found in the directory. The file essentially doesn't exist.
                        $this->_notices[] = "Could not find file " . $Version->getSavedName()  . " in directory";
                    }
                }
                else
                {
                    $fileData = $Version->getData();
                }


                // if file contents exist, add to zip folder.
                if($fileData!=null)
                {
                    $this->_ZipArchive->addFromString($path . '/'. $fileName, $fileData);
                    $totalArchive++;
                }
                else
                {
                    $totalNotFound++;
                }

            }
        }

        $this->_notices[] = "Total archived file: " . $totalArchive;
        $this->_notices[] = "Total files that could not be archived: " . $totalNotFound;


    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function getNotices()
    {
        return $this->_notices;
    }

    public function getZipLocation()
    {
        return $this->getUploadDirectory(). $this->getZipName() . '.zip';
    }

    private function addNotices()
    {
        // add notices
        $this->_ZipArchive->addFromString("notices.txt", implode( $this->_notices, "\r\n" ));
    }

    public function archive()
    {
        $this->validate();
        if(!empty($this->_errors))
            return false;
        try
        {

            //echo $_SERVER['DOCUMENT_ROOT']. '/'. $this->getZipLocation();
            // give where the folder will be store and what the zip file will be called.

            $location  = $_SERVER['DOCUMENT_ROOT']. '/'.$this->getZipLocation();

            $res = $this->_ZipArchive->open($location , ZipArchive::CREATE);
            if($res !== TRUE)
                throw new Exception("Could not archive");

            $this->createArchive();

            $this->addNotices();

            $this->_ZipArchive->close();

            if(!file_exists($location))
                throw new Exception("File does not exist or could not write to destination. Check folder permissions.");

            return true;

        }
        catch(Exception $e)
        {
            $this->_errors[] = $e->getMessage();
        }
        return false;
    }


    /**
     * @return string returns the location of the file. This returns the latest file.
     */
    public function getZipBaseUrl()
    {
        return $_SERVER['DOCUMENT_ROOT']. '/'. $this->getZipLocation();
    }

    /**
     * @return string returns the location of the file relative to the webroot. This returns the latest file.
     */
    public function getZipUrl()
    {
        $protocol = CoreConfig::settings()['protocol'];
        return $protocol . $_SERVER['HTTP_HOST']. '/'. $this->getZipLocation();
    }
}