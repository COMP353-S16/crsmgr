<?php

/**
 * Created by PhpStorm.
 * User: dimitri
 * Date: 2016-07-25
 * Time: 9:24 PM
 */
class Semester
{

    /**
     * @var mixed
     */
    private $_sid;

    /**
     * @var mixed
     */
    private $_startDate;

    /**
     * @var mixed
     */
    private $_endDate;

    /**
     * @var array
     */
    private $_data;

    /**
     * Semester constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->_data = $data;
        $this->_startDate = $data['startDate'];
        $this->_endDate = $data['endDate'];
        $this->_sid = $data['sid'];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_sid;
    }


    /**
     * @return mixed
     */
    public function getSemesterStartDate()
    {
        return $this->_startDate;
    }

    /**
     * @return mixed
     */
    public function getSemseterEndDate()
    {
        return $this->_endDate;
    }
}