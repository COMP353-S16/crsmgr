<?php

class Student extends User
{
    protected $_gid;

    private $_sectionName;

    private $_semesters = array();

    private $_groups = array();

    public function __construct($uid)
    {
        parent::__construct($uid);
     
        $this->extract();
        $this->extractSemesters();
        $this->extractGroups();
    }



    private function extract()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Students s WHERE  s.uid = :uid");
        $query->bindValue(":uid", $this->_uid);

        $query->execute();
        $data = $query->fetch();

        $this->_sectionName = $data['sectionName'];

    }
    
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
        $query = $pdo->prepare("SELECT gm.gid, gm.sid FROM GroupMembers gm WHERE  gm.uid = :uid");
        $query->bindValue(":uid", $this->_uid);
        $query->execute();
        $this->_groups =$query->fetchAll();

    }

    /**
     * @return array returns a list of groups this student was ever part of along with the groups' section id
     */
    public function getGroups()
    {
        return $this->_groups;
    }


    /**
     * @param $sid
     * @return bool
     */
    public function isRegisteredForSemester($sid)
    {
        foreach($this->_semesters as $semester)
        {
            if($semester['sid'] == $sid)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * @return StudentSemester
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


    public function isRegistered()
    {
         return count($this->_semesters) > 0;
    }
    

    public function getSectionName()
    {
        return $this->_sectionName;
    }


}