<?php

class Student extends User
{
    protected $_gid;

    private $_sectionName;

    private $_semesters = array();

    private $_groups = array();

    /**
     * Student constructor.
     *
     * @param $uid user id
     */
    public function __construct($uid)
    {
        parent::__construct($uid);

       // $this->extract();
        $this->extractSemesters();
        $this->extractGroups();
    }



    /**
     *
     */
    private function extractSemesters()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM StudentSemester s WHERE  s.uid = :uid");
        $query->bindValue(":uid", $this->_uid);
        $query->execute();
        $this->_semesters = $query->fetchAll();
    }

    /**
     * extracts all the groups this student was ever part of
     */
    private function extractGroups()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT gm.gid, gm.sid, gm.sid FROM GroupMembers gm WHERE  gm.uid = :uid");
        $query->bindValue(":uid", $this->_uid);
        $query->execute();
        $this->_groups = $query->fetchAll();

    }

    /**
     * @return bool returns true if student is in a group not necessarily tied to a semester
     */
    public function isInGroup()
    {
        return count($this->getGroups()) > 0;
    }


    /**
     * @return array returns a list of groups this student was ever part of along with the groups' section id
     */
    public function getGroups()
    {
        return $this->_groups;
    }

    /**
     * @param $sid semester id
     *
     * @return null returns group id depending on the semester given
     */
    public function getGroupIdFromSid($sid)
    {
        foreach ($this->_groups as $Group)
        {
            if ($Group['sid'] == $sid)
            {
                return $Group['gid'];
            }
        }

        return null;

    }


    /**
     * @param $sid
     *
     * @return bool returns true if user is part of group given semester
     */
    public function isInGroupFromSid($sid)
    {
        foreach ($this->_groups as $Group)
        {
            if ($Group['sid'] == $sid)
            {
                return true;
            }
        }

        return false;
    }


    /**
     * @return StudentSemester returns the student's registered semesters
     */
    public function getSemesters()
    {
        return new StudentSemester($this->_semesters);
    }

    /**
     * @return StudentInfo
     */
    public function getStudentInfo()
    {
        return new StudentInfo($this->_uid);
    }


    /**
     * @return bool returns true if the student is registered in any semester
     */
    public function isRegistered()
    {
        return count($this->_semesters) > 0;
    }


}