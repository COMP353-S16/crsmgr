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
        );
    }

    public function getFileStats()
    {

        $GroupFiles = $this->_group->getGroupFiles();


        $files = $GroupFiles->getFiles();

        $fileArray = array();
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
                $fileArray[$i]["numberOfVersions"]++;

                if ($fileArray[$i]["isPermanentlyDeleted"] == false) {
                    $fileArray[$i]["totalFileSize"] += $versionShortcut["size"];
                }
            }




        }

        return $fileArray;
    }

}