<?php

/**
 * Class CreateSemester
 */
class CreateSemester
{


    /**
     * @var
     */
    private $_start;

    /**
     * @var
     */
    private $_end;

    /**
     * @var array
     */
    private $_errors = array();

    /**
     * @var array
     */
    private $_Semester = array();

    /**
     * @var
     */
    private $_newId;

    /**
     * CreateSemester constructor.
     *
     * @param $start string semester start date
     * @param $end   string semester end date
     */
    public function __construct($start, $end)
    {
        $this->_start = $start;
        $this->_end = $end;
        $this->getSemesters();
    }

    /**
     *
     */
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

    /**
     * @param $date string date to evaluate
     *
     * @return bool returns true if date is valid
     */
    private function isValidDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);

        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Validate information
     */
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

    /**
     * This is part of the validation and it checks whether the new semester conflicts within the dates of an existing semester
     */
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


    /**
     * @return array returns an array of errors
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @return int returns the semester id
     */
    public function getSemesterId()
    {
        return $this->_newId;
    }

    /**
     * @return bool returns true if the semester was successully created
     */
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