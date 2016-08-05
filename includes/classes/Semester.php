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

    private $_data;

    public function __construct(array $data)
    {
        $this->_data = $data;
        $this->_startDate = $data['startDate'];
        $this->_endDate = $data['endDate'];
        $this->_sid = $data['sid'];
    }

    public function getId()
    {
        return $this->_sid;
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