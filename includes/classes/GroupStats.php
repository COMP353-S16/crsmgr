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
            "files" => array(),
            "members" => array()
        );

        $this->getMembers();


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

                $vid = $Version->getVersionId();

                $fileArray[$i]["versions"][$v] = array (
                    "vid" => $vid,
                    "size" => $Version->getSize(),
                    "uploader" => $Version->getUploaderId(),
                    "date" => $Version->getUploadDate(),
                    "downloads" => array()
                );


                $versionShortcut = &$fileArray[$i]["versions"][$v];


                // add version downloads
                $versionShortcut['downloads'] = $this->getVersionDownloads($vid);


                // retrieve member stats for this particular version
                $this->getMemberVersion($versionShortcut);


                $this->_GroupStats['numberOfDownloads'] += count($versionShortcut['downloads']);

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

    private function getVersionDownloads($vid)
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT uid, downloadDate FROM Downloads WHERE vid=:vid");
        $query->bindValue(":vid", $vid);
        $query->execute();
        $downloads = array();

        while($data = $query->fetch())
        {
            $downloads[] = array (
                "uid" => $data['uid'],
                "downloadDate" => $data['downloadDate']

            );
        }



        return $downloads;

    }

    private function getMembers()
    {
        $members = $this->_group->getMembers();
        /**
         * @var $Student Student
         */
        foreach($members as $k => $Student)
        {
            $uid = $Student->getUid();
            $this->_GroupStats['members'][] = array(
                "uid" => $uid,
                "name" => $Student->getFullName(),
                "totalUploadsSize" => 0,
                "totalDownloadsSize" =>0,
                "numberOfUploads" => 0,
                "numberOfDownloads"=> 0,
                "versionsDownloaded" => array(),
                "versionsUploaded" => array()
            );
        }
    }

    private function getMemberVersion(&$version)
    {

        foreach($this->_GroupStats['members'] as $k => $memberData)
        {

            $uid = $memberData['uid'];

            foreach($version['downloads'] as $downloadData)
            {
               // echo $downloadData['uid'];

                if($downloadData['uid'] == $uid)
                {
                    $this->_GroupStats['members'][$k]['numberOfDownloads']++;
                    $this->_GroupStats['members'][$k]['versionsDownloaded'][] = $version['vid'];


                    $this->_GroupStats['members'][$k]['totalDownloadsSize']+= $version['size'];

                }
            }


            if($version['uploader'] == $uid)
            {
                $this->_GroupStats['members'][$k]['numberOfUploads']++;
                $this->_GroupStats['members'][$k]['versionsUploaded'][] = $version['vid'];
                $this->_GroupStats['members'][$k]['totalUploadsSize']+= $version['size'];
            }

        }
    }



    public function getStats()
    {
        return $this->_GroupStats;
    }
}