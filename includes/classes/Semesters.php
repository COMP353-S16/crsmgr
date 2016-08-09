<?php

/**
 * Class Semesters
 */
class Semesters
{

    /**
     * @var array
     */
    private $_semesters = array();

    /**
     * Semesters constructor.
     */
    public function __construct()
    {
        $this->extract();
    }

    /**
     *
     */
    private function extract()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Semester");
        $query->execute();
        $this->_semesters = $query->fetchAll();
    }

    /**
     * @param $sid
     *
     * @return null|\Semester
     */
    public function getSemesterById($sid)
    {
        $semesters = $this->getSemesters();

        /**
         * @var $Semester Semester
         */
        foreach ($semesters as $Semester)
        {

            if ($Semester->getId() == $sid)
            {

                return $Semester;
            }


        }

        return null;
    }

    /**
     * @return array returns an array of all semesters
     */
    public function getSemesters()
    {
        $t = array();
        foreach ($this->_semesters as $data)
        {
            $t[] = new Semester($data);
        }

        return $t;
    }

    /**
     * @return bool returns true if semester actually exist
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

        if ($sid == null)
        {
            $sid = $this->getLastSemesterId();
        }

        return $sid;
    }

    // TODO this needs to be fixed since the current semester creation allows for semesters to be created in the past. This is not good and might return a false semester id.

    /**
     * @return int|null This method returns the current semester's id assuming that each sid holds a semester later than the previous! (IMPORTANT)
     */
    public function getLastSemesterId()
    {
        $last = null;
        // if no semesters, then return null
        if(!$this->exist())
            return $last;

        // find the first semester whose date starts after today's date. In essence, the next semester
        foreach ($this->_semesters as $Semester)
        {
            $last = $Semester['sid'];
        }

        return $last;
    }

    /**
     * @param $sid semester id
     *
     * @return bool returns true if the semester is open, i.e. if today's date falls within the semester's date bounds.
     */
    public function isOpen($sid)
    {
        if($sid == null)
        {
            return false;
        }
        $date = strtotime(date("Y-m-d H:i:s"));
        /**
         * @var $Semester Semester
         */
        $Semester = $this->getSemesterById($sid);
        return ($date >= strtotime($Semester->getSemesterStartDate()) && $date <= strtotime($Semester->getSemseterEndDate()));

    }

}