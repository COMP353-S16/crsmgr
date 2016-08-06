<?php

/**
 * Class StudentSemester
 */
class StudentSemester
{

    /**
     * @var array
     */
    private $_data = array();

    /**
     * StudentSemester constructor.
     *
     * @param array $data data given by query. This will depend on the query given for a particular student
     */
    public function __construct(array $data)
    {
        $this->_data = $data;
    }

    /**
     * @param $sid semester id
     *
     * @return string returns student's section name depending on semester given
     */
    public function getSectionName($sid)
    {
        foreach ($this->_data as $semesterData)
        {
            if ($semesterData['sid'] == $sid)
            {
                return $semesterData['sectionName'];
            }
        }

        return "";
    }

    /**
     * @param $sid semester id
     *
     * @return bool returns true if the student is registered for given semester
     */
    public function isRegisteredForSemester($sid)
    {

        foreach ($this->_data as $i => $semester)
        {

            if ($semester['sid'] == $sid)
            {

                return true;
            }
        }

        return false;
    }
}