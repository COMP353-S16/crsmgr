<?php

class StudentSemester
{

    private $_data = array();

    public function __construct(array $data)
    {
        $this->_data = $data;
    }

    public function getSemesterName($sid)
    {
        foreach($this->_data as $semesterData)
        {
            if($semesterData['sid'] == $sid)
            {
                return $semesterData['sectionName'];
            }
        }
        return "";
    }
}