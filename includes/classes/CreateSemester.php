<?php

class CreateSemester
{


    private $_start;
    private $_end;
    private $_errors = array();

    private $_Semester = array();

    private $_newId;

    public function __construct($start, $end)
    {
        $this->_start = $start;
        $this->_end = $end;
        $this->getSemesters();
    }

    private function getSemesters()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT sid FROM Semester");
        $query->execute();
        while ($data = $query->fetch())
        {
            $this->_Semester[] = new Semester($data['sid']);
        }
    }

    private function isValidDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);

        return $d && $d->format('Y-m-d') === $date;
    }

    //TODO Must add some validation here since we need to check whether the semester start and end dates conflict with others
    private function validate()
    {
        if (!$this->isValidDate($this->_end) || !$this->isValidDate($this->_start))
        {
            $this->_errors[] = "Please enter a valid start date and end date. Format: YYYY-MM-DD";
        }
        else if (strtotime($this->_start) > strtotime($this->_end) || strtotime($this->_end) < strtotime($this->_start))
        {
            $this->_errors[] = "Start date must be before end date";

        }
        else
        {
            $this->conflictCheck();
        }

    }

    private function conflictCheck()
    {
        $msg = "Date conflicts";
        $msg .= "<ul>";
        $msg .= "<li>";
        $msg .= "Semester <strong>%s</strong>";
        $msg .= "<ul>";
        $msg .= "<li>" . "Start Date: %s </li>";
        $msg .= "<li>" . "End Date: %s </li>";
        $msg .= "</ul>";
        $msg .= "</li>";
        $msg .= "</ul>";
        /**
         * @var $Semester Semester
         */
        foreach ($this->_Semester as $Semester)
        {

            $conflict = sprintf($msg,

                $Semester->getId(),
                date("Y-m-d H:i:s a", strtotime($Semester->getSemesterStartDate())),
                date("Y-m-d H:i:s a", strtotime($Semester->getSemseterEndDate())));


            if ($this->_start >= $Semester->getSemesterStartDate() && $this->_start <= $Semester->getSemseterEndDate())
            {
                $this->_errors[] = $conflict;
            }
            if ($this->_end >= $Semester->getSemesterStartDate() && $this->_end <= $Semester->getSemseterEndDate())
            {
                $this->_errors[] = $conflict;
            }
            if ($Semester->getSemesterStartDate() >= $this->_start && $this->_end >= $Semester->getSemseterEndDate())
            {
                $this->_errors[] = $conflict;
            }
        }
    }


    public function getErrors()
    {
        return $this->_errors;
    }

    public function getSemesterId()
    {
        return $this->_newId;
    }

    public function create()
    {

        $this->validate();
        if (!empty($this->_errors))
        {
            return false;
        }


        $pdo = Registry::getConnection();
        $query = $pdo->prepare("INSERT INTO Semester (startDate, endDate) VALUES (:start, :end)");
        $query->bindValue(":start", $this->_start);
        $query->bindValue(":end", $this->_end);

        if ($query->execute())
        {
            $this->_newId = $pdo->lastInsertId();

            return true;
        }

        return false;

    }
}