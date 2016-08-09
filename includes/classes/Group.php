<?php

/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/15/2016
 * Time: 1:19 AM
 */
class Group
{

    /**
     * @var int
     */
    protected $_gid;

    /**
     * @var
     */
    protected $_leaderId;

    /**
     * @var
     */
    protected $_creatorId;

    /**
     * @var
     */
    protected $_gName;

    /**
     * @var
     */
    protected $_maxSize;

    /**
     * @var array
     */
    protected $_groupStudents = array();

    /**
     * @var
     */
    private $_sid;

    /**
     * @var Semester
     */
    private $_Semester;

    /**
     * Group constructor.
     *
     * @param $gid group id
     */

    public function __construct($gid)
    {
        $this->_gid = $gid;
        $this->fetchGroupInfo();
        $this->populateGroupMembers();
    }

    /**
     *
     */
    private function fetchGroupInfo()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Groups WHERE gid=:gid");
        $query->bindValue(":gid", $this->_gid);
        $query->execute();
        $group = $query->fetch();


        $this->_leaderId = $group['leaderId'];
        $this->_creatorId = $group['creatorId'];
        $this->_gName = $group['gName'];
        $this->_sid = $group['sid'];
        $this->_maxSize = $group['maxUploadsSize'];

        $this->extractSemester();
    }

    /**
     * @return int returns the group id
     */
    public function getGid()
    {
        return $this->_gid;
    }


    /**
     * @return int returns the creator id
     */
    public function getCreatorId()
    {
        return $this->_creatorId;
    }

    /**
     * @return string returns group name
     */
    public function getGName()
    {
        return $this->_gName;
    }

    /**
     * @return int returns the leader id
     */
    public function getLeaderId()
    {
        return $this->_leaderId;
    }

    /**
     * @param $uid int
     *
     * @return bool returns true if the user is the leader of group
     */
    public function isLeader($uid)
    {
        return $this->_leaderId == $uid;
    }

    /**
     * @return float returns maximum size of all files for group
     */
    public function getMaxUploadSize()
    {
        return $this->_maxSize;
    }

    /**
     * @return array returns an array of students from the database
     */
    public function getGroupStudents()
    {
        return $this->_groupStudents;
    }

    /**
     *
     */
    private function populateGroupMembers()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT g.uid FROM  GroupMembers g WHERE g.gid=:gid");
        $query->bindValue(":gid", $this->_gid);
        $query->execute();
        $this->_groupStudents = $query->fetchAll();
    }

    /**
     * @return int
     */
    public function getTotalMembers()
    {
        return count($this->_groupStudents);
    }

    /**
     * @param $uid int user id
     *
     * @return bool returns true if the user is in this group
     */
    public function isInGroup($uid)
    {
        foreach ($this->_groupStudents as $student)
        {
            if ($student['uid'] == $uid)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @return GroupFiles returns an instance of GroupFiles
     */
    public function getGroupFiles()
    {
        return new GroupFiles($this->_gid);
    }


    /**
     * @return array returns an array of Student objects
     */
    public function getMembers()
    {
        $students = array();
        foreach ($this->_groupStudents as $i => $student)
        {
            $students[] = new Student($student['uid']);
        }

        return $students;
    }

    /**
     * @return GroupStats
     */
    public function getGroupStats()
    {
        return new GroupStats($this);
    }

    /**
     * @return int returns semester id
     */

    public function getSid()
    {
        return $this->_sid;
    }


    /**
     *
     */
    private function extractSemester()
    {
        $Semesters = new Semesters();

        $this->_Semester = $Semesters->getSemesterById($this->getSid());
    }

    /**
     * @return Semester
     */
    public function getSemester()
    {
        return $this->_Semester;
    }

    /**
     * @return bool returns true if the group can no longer access anything. This is based on today's date and the semester end date.
     */
    public function isGroupClosed()
    {

        return (strtotime(date("Y-m-d H:i:s")) > strtotime($this->getSemester()->getSemseterEndDate()));

    }
}