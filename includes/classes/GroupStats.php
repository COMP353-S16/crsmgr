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
        $this->_GroupStats = array(
            "usedBandwidth"                   => 0,
            "numberOfFiles"                   => 0,
            "numberOfDeletedFiles"            => 0,
            "numberOfPermanentlyDeletedFiles" => 0,
            "numberOfUploads"                 => 0,
            "numberOfDownloads"               => 0,
            "files"                           => array(),
            "members"                         => array()
        );

        $this->getMembers();
        // get general file stats
        $this->getFileStats();
        //get each member's file interaction
        $this->getMemberVersion();

    }

    public function getTotalPermanentDelete()
    {
        return $this->_GroupStats['numberOfPermanentlyDeletedFiles'];
    }

    public function getTotalDeletedFiles()
    {
        return $this->_GroupStats['numberOfDeletedFiles'];
    }

    public function getTotalFiles()
    {
        return $this->_GroupStats['numberOfFiles'];
    }

    public function getUsedBandwidth()
    {
        return $this->_GroupStats['usedBandwidth'];
    }

    public function getNbOfUploadedFiles()
    {
        return $this->_GroupStats['numberOfUploads'];
    }

    public function getNumberOfDownloads()
    {
        return $this->_GroupStats['numberOfDownloads'];
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
            $fileArray[$i] = array(
                "numberOfVersions"     => 0,
                "fileName"             => $Files->getFileName(),
                "totalFileSize"        => 0,
                "isPermanentlyDeleted" => $this->_group->getGroupFiles()->isPermanentDeleted($Files->getId()),
                "isDeleted"            => $this->_group->getGroupFiles()->isDeleted($Files->getId()),
                "versions"             => array(),
            );
            $versions = $Files->getVersions();

            /**
             * @var $Version Version
             */
            foreach ($versions as $v => $Version)
            {
                $vid = $Version->getVersionId();
                $fileArray[$i]["versions"][$v] = array(
                    "vid"       => $vid,
                    "size"      => $Version->getSize(),
                    "uploader"  => $Version->getUploaderId(),
                    "date"      => $Version->getUploadDate(),
                    "downloads" => array()
                );

                // create reference pointer
                $versionShortcut = &$fileArray[$i]["versions"][$v];
                // add version downloads
                $versionShortcut['downloads'] = $this->getVersionDownloads($vid);
                // retrieve member stats for this particular version
                //$this->getMemberVersion($versionShortcut)

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
            else
            {
                $this->_GroupStats['numberOfPermanentlyDeletedFiles']++;
            }

            if ($fileArray[$i]["isDeleted"])
            {
                $this->_GroupStats['numberOfDeletedFiles']++;
            }
            $this->_GroupStats["numberOfUploads"] += $fileArray[$i]["numberOfVersions"];
            $this->_GroupStats["usedBandwidth"] += $fileArray[$i]["totalFileSize"];
        }
    }

    /**
     * @param $vid
     *
     * @return array calculates  information on download for a specific version
     */
    private function getVersionDownloads($vid)
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT uid, downloadDate FROM Downloads WHERE vid=:vid");
        $query->bindValue(":vid", $vid);
        $query->execute();
        $downloads = array();
        while ($data = $query->fetch())
        {
            $downloads[] = array(
                "uid"          => $data['uid'],
                "downloadDate" => $data['downloadDate']

            );
        }

        return $downloads;
    }

    /**
     * retrieves members and sets up template
     */
    private function getMembers()
    {
        $members = $this->_group->getMembers();
        /**
         * @var $Student Student
         */
        foreach ($members as $k => $Student)
        {
            $uid = $Student->getUid();
            $this->_GroupStats['members'][] = array(
                "uid"                => $uid,
                "name"               => $Student->getFullName(),
                "totalUploadsSize"   => 0,
                "totalDownloadsSize" => 0,
                "numberOfUploads"    => 0,
                "numberOfDownloads"  => 0,
                "versionsDownloaded" => array(),
                "versionsUploaded"   => array()
            );
        }
    }


    /**
     * interaction of each member
     */
    private function getMemberVersion()
    {
        foreach ($this->_GroupStats['members'] as $k => $memberData)
        {
            $uid = $memberData['uid'];
            foreach ($this->_GroupStats['files'] as $i => $filedData)
            {
                foreach ($filedData['versions'] as $j => $version)
                {
                    foreach ($version['downloads'] as $downloadData)
                    {
                        // was this person the one who downloaded it?
                        if ($downloadData['uid'] == $uid)
                        {
                            $this->_GroupStats['members'][$k]['numberOfDownloads']++;
                            $this->_GroupStats['members'][$k]['versionsDownloaded'][] = $version['vid'];
                            $this->_GroupStats['members'][$k]['totalDownloadsSize'] += $version['size'];
                        }
                    }
                    // was this person the uploader?
                    if ($version['uploader'] == $uid)
                    {
                        $this->_GroupStats['members'][$k]['numberOfUploads']++;
                        $this->_GroupStats['members'][$k]['versionsUploaded'][] = $version['vid'];
                        $this->_GroupStats['members'][$k]['totalUploadsSize'] += $version['size'];
                    }
                }
            }
        }
    }


    /**
     * @return array returns all stats array
     */
    public function getStats()
    {
        return $this->_GroupStats;
    }
}