<?php

/**
 * Created by PhpStorm.
 * User: dimitri
 * Date: 2016-07-21
 * Time: 9:52 PM
 */
class CreateDeliverable
{
    private $_name;

    private $_errors = array();

    public function __construct()
    {
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function setStartDate($start)
    {

    }

    public function setEndDate($end)
    {

    }

    private function validate()
    {
        if($this->_name=="" || $this->_name == null)
        {
            $this->_errors[] = "A deliverable name is required";
        }
    }
}