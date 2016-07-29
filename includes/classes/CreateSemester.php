<?php

class CreateSemester
{


    private $_start;
    private $_end;
    private $_errors = array();

    public function __construct($start, $end)
    {
        $this->_start = $start;
        $this->_end = $end;
    }

    //TODO Must add some validation here since we need to check whether the semester start and end dates conflict with others
    private function validate()
    {

    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function create()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("INSERT INTO Semester (startDate, endDate) VALUES (:start, :end)");
        $query->bindValue(":start", $this->_start);
        $query->bindValue(":end", $this->_end);
        return $query->execute();
    }
}