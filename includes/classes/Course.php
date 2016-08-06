<?php

class Course
{

    /**
     * @var
     */
    protected $_cid;

    /**
     * @var
     */
    protected $_cName;

    /**
     * @var
     */
    protected $_startDate;

    /**
     * @var
     */
    protected $_endDate;

    /**
     * Course constructor.
     *
     * @param $cid
     */
    public function __construct($cid)
    {
        $this->cid = $cid;
        $this->fetchCourseInfo();
    }

    /**
     *
     */
    private function fetchCourseInfo()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Courses WHERE cid=:cid");
        $query->bindValue(":cid", $this->cid);
        $query->execute();
        $course = $query->fetch();

        $this->_cName = $course['cName'];
        $this->_endDate = $course['startDate'];
        $this->_startDate = $course['endDate'];
    }

    /**
     * @return mixed
     */
    public function getCourseId()
    {
        return $this->cid;
    }

    /**
     * @return mixed
     */
    public function getCourseName()
    {
        return $this->_cName;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->_startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->_endDate;
    }
}