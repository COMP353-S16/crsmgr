<?php

/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/14/2016
 * Time: 10:08 PM
 */
class Course
{
    protected $_cid;
    protected $_cName;
    protected $_startDate;
    protected $_endDate;

    public function __construct($cid)
    {
        $this->cid = $cid;
        $this->fetchCourseInfo();
    }

    private function fetchCourseInfo() {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Courses c WHERE c.cid=:cid");
        $query->bindValue(":cid", $this->cid);
        $query->execute();
        $course = $query->fetch();

        $this->_cName = $course['cName'];
        $this->_endDate = $course['startDate'];
        $this->_startDate = $course['endDate'];
    }

    public function getCourseId() {
        return $this->cid;
    }

    public function getCourseName() {
        return $this->_cName;
    }

    public function getStartDate() {
        return $this->_startDate;
    }

    public function getEndDate() {
        return $this->_endDate;
    }
}