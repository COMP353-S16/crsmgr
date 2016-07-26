<?php

/**
 * Created by PhpStorm.
 * User: dimitri
 * Date: 2016-07-25
 * Time: 9:24 PM
 */
class Semester
{

    private $_sid;

    private $_startDate;

    private $_endDate;

    public function __construct($sid)
    {
        $this->_sid = $sid;
        $this->extract();
    }

    private function extract()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Semester WHERE sid=:sid LIMIT 1");
        $query->bindValue(":sid", $this->_sid);
        $query->execute();
        $data = $query->fetch();
        $this->_startDate = $data['startDate'];
        $this->_endDate = $data['endDate'];
    }
    public function getSemesterStartDate()
    {
        return $this->_startDate;
    }

    public function getSemseterEndDate()
    {
        return $this->_endDate;
    }
}