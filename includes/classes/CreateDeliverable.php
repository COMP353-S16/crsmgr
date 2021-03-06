<?php

/**
 * Class CreateDeliverable
 */
class CreateDeliverable
{

    /**
     * @var
     */
    private $_name;

    /**
     * @var array
     */
    private $_errors = array();

    /**
     * @var
     */
    private $_startDate;

    /**
     * @var
     */
    private $_endDate;

    /**
     * @var array
     */
    private $_gids = array();

    /**
     * @var
     */
    private $_sid;

    /**
     * CreateDeliverable constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @param array $gids
     */
    public function setGroupIds(array $gids)
    {
        $this->_gids = $gids;
    }

    /**
     * @param $sid
     */
    public function setSemester($sid)
    {
        $this->_sid = $sid;
    }

    /**
     * @param $start
     */
    public function setStartDate($start)
    {
        $this->_startDate = $start;
    }

    /**
     * @param $end
     */
    public function setEndDate($end)
    {
        $this->_endDate = $end;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->_startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->_endDate;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param $date
     *
     * @return bool
     */
    private function isValidDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);

        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * @return bool
     */
    private function isValidName()
    {
        return Files::isValidFileName($this->_name);
    }

    /**
     *
     */
    private function validate()
    {
        if ($this->_name == "" || $this->_name == null)
        {
            $this->_errors[] = "A deliverable name is required";
        }
        else
        {
            // this needs to be done so that there won't be any archiving problems when putting all this into a zip file
            if (!$this->isValidName())
            {
                $this->_errors[] = "Invalid name for deliverable. Cannot contain special characters \\ / : * ? \" < > |";
            }
        }
        if (!$this->isValidDate($this->getEndDate()) || !$this->isValidDate($this->getStartDate()))
        {
            $this->_errors[] = "Please enter a valid start date and end date. Format: YYYY-MM-DD";
        }
        else if (strtotime($this->getStartDate()) > strtotime($this->getEndDate()) || strtotime($this->getEndDate()) < strtotime($this->getStartDate()))
        {
            $this->_errors[] = "Start date must be before end date";
        }
        else if ($this->_sid == null || $this->_sid == "")
        {
            $this->_errors[] = "A semester is required";
        }
        else if ((strtotime($this->getEndDate()) - strtotime($this->getStartDate())) < 86400)
        {
            $this->_errors[] = "Deliverable must be at least 24 hours";
        }
        else
        {
            $Semesters = new Semesters();
            $Semester = $Semesters->getSemesterById($this->_sid);
            if (!(strtotime($this->getStartDate()) >= strtotime($Semester->getSemesterStartDate()) && strtotime($this->getEndDate()) <= strtotime($Semester->getSemseterEndDate())))
            {
                $this->_errors[] = "Deliverable date must be within the bounds of semester date: " . $Semester->getSemesterStartDate() . ' and ' . $Semester->getSemseterEndDate();

            }


        }

    }

    /**
     * @return mixed
     */
    public function getSemesterId()
    {
        return $this->_sid;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }


    /**
     * @param $sid semester id
     *
     * @return array returns an array of group ids based on semester
     */
    private function getAllGroupIds($sid)
    {
        $groupIds = array();
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT gid FROM Groups WHERE sid=:sid");
        $query->execute(array(":sid" => $this->_sid));
        while ($data = $query->fetch())
        {
            $groupIds[] = $data['gid'];
        }

        return $groupIds;
    }

    /**
     * @return bool
     */
    public function create()
    {
        $this->validate();
        if (!empty($this->getErrors()))
        {
            return false;
        }

        $pdo = Registry::getConnection();
        try
        {


            $query = $pdo->prepare("INSERT INTO Deliverables (dName, startDate, endDate, sid) VALUES (:name, :start, :end, :sid) ");
            $query->bindValue(":name", $this->getName());
            $query->bindValue(":start", $this->getStartDate());
            $query->bindValue(":end", $this->getEndDate());
            $query->bindValue(":sid", $this->getSemesterId());
            $query->execute();

            $lastId = $pdo->lastInsertId();

            $groupIds = (empty($this->_gids) ? $this->getAllGroupIds($this->_sid) : $this->_gids);

            if (empty($groupIds))
            {
                throw new Exception("No groups found for this semester");
            }


            foreach ($groupIds as $gid)
            {
                $AssignDeliverables = new AssignDeliverables($gid, $pdo);
                $AssignDeliverables->addDid($lastId);
                if (!$AssignDeliverables->assign())
                {

                    foreach ($AssignDeliverables->getErrors() as $error)
                    {
                        $this->_errors[] = $error;
                    }
                    throw new Exception("Could not assign deliverable {$lastId} to group {$gid}");
                }
            }

            return true;

        }
        catch (Exception $e)
        {
            $this->_errors[] = $e->getMessage();
        }

        return false;
    }
}