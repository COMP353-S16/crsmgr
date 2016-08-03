<?php

/**
 * Created by PhpStorm.
 * User: fatin
 * Date: 2016-08-02
 * Time: 7:57 PM
 */
class GroupStats
{
    /**
     * @var Group
     */
    private $_group;
    protected $_GroupStats;


    public function __construct(Group $group)
    {
        $this->_group = $group;
        $this->_GroupStats = array (
            "usedBandwidth" => 0,
            "numberOfFiles" => 0,
            "numberOfDeletedFiles" => 0,
            "numberOfPermanentlyDeletedFiles" => 0,
            "numberOfUploads" => 0,
            "numberOfDownloads" => 0,
            "files" => array()
        );
        $this->getFileStats();
    }

    public function getTotalDeletedFiles()
    {
        return $this->_GroupStats['numberOfDeletedFiles'];
    }

    public function getUsedBandwidth()
    {
        return $this->_GroupStats['usedBandwidth'];
    }

    public function getNbOfUploadedFiles()
    {
        return $this->_GroupStats['numberOfUploads'];
    }

    private function getFileStats()
    {

        $GroupFiles = $this->_group->getGroupFiles();


        $files = $GroupFiles->getFiles();

        $fileArray = &$this->_GroupStats["files"];
        /**
         * @var $Files Files
         */
        foreach ($files as $i => $Files)
        {
            $fileArray[$i] = array (
                "numberOfVersions" => 0,
                "fileName" => $Files->getFileName(),
                "totalFileSize" => 0,
                "isPermanentlyDeleted" => $this->_group->getGroupFiles()->isPermanentDeleted($Files->getId()),
                "isDeleted" => $this->_group->getGroupFiles()->isDeleted($Files->getId()),
                "versions" => array(),
            );



            $versions = $Files->getVersions();

            /**
             * @var $Version Version
             */
            foreach ($versions as $v => $Version)
            {

                $fileArray[$i]["versions"][$v] = array (
                    "size" => $Version->getSize(),
                    "uploader" => $Version->getUploaderId(),
                    "date" => $Version->getUploadDate(),
                );
                $versionShortcut = &$fileArray[$i]["versions"][$v];

                // if a file has been permanently deleted, it should not count towards active stats
                if ($fileArray[$i]["isPermanentlyDeleted"] == false)
                {
                    $fileArray[$i]["numberOfVersions"]++;
                    $fileArray[$i]["totalFileSize"] += $versionShortcut["size"];
                }
            }

            if ($fileArray[$i]["isPermanentlyDeleted"] == false)
            {
                $this->_GroupStats["numberOfFiles"]++;
            }

            if($fileArray[$i]["isDeleted"])
            {
                $this->_GroupStats['numberOfDeletedFiles']++;
            }


            $this->_GroupStats["numberOfUploads"] += $fileArray[$i]["numberOfVersions"];
            $this->_GroupStats["usedBandwidth"] += $fileArray[$i]["totalFileSize"];


        }


    }


    public function getStats()
    {
        return $this->_GroupStats;
    }
}