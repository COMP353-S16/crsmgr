<?php

class Semesters
{

    private $_semesters = array();

    public function __construct()
    {
        $this->extract();
    }

    private function extract()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Semester");
        $query->execute();
        $this->_semesters = $query->fetchAll();
    }

    /**
     * @return array returns an array of all semesters
     */
    public function getSemesters()
    {
        $t = array();
        foreach($this->_semesters as $data)
        {
            $t[] = new Semester($data);
        }
        return $t;
    }

    public function getSemesterById($sid)
    {
        $semesters =$this->getSemesters();
        /**
         * @var $Semester Semester
         */
        foreach($semesters as $Semester)
        {
            if($Semester->getId() == $sid)
                return $Semester;

        }
        return null;
    }


    /**
     * @return bool if semester actually exist
     */
    public function exist()
    {
        return count($this->_semesters) > 0;
    }

    /**
     * returns the semester currently on based on today's date
     */
    public function getSid()
    {
        $date = strtotime(date("Y-m-d H:i:s"));

        $sid = null;
        foreach ($this->_semesters as $Semester)
        {


            if ($date >= strtotime($Semester['startDate']) && $date <= strtotime($Semester['endDate']))
            {


                $sid = $Semester['sid'];
            }
        }


        //$sid = $this->getLastSemesterId();

        return $sid;
    }

    public function getLastSemesterId()
    {
        $last = null;
        foreach ($this->_semesters as $Semester)
        {
            $last = $Semester['sid'];

        }
        return $last;
    }


}