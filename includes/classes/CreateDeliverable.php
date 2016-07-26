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

    private $_startDate;

    private $_endDate;

    private $_gids = array();

    private $_sid;

    public function __construct()
    {
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function setGroupIds(array $gids)
    {
        $this->_gids = $gids;
    }

    public function setSemester($sid)
    {
        $this->_sid = $sid;
    }

    public function setStartDate($start)
    {
        $this->_startDate = $start;
    }

    public function setEndDate($end)
    {
        $this->_endDate = $end;
    }

    public function getStartDate()
    {
        return $this->_startDate;
    }

    public function getEndDate()
    {
        return $this->_endDate;
    }

    public function getName()
    {
        return $this->_name;
    }

    private function isValidDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    private function validate()
    {
        if($this->_name=="" || $this->_name == null)
        {
            $this->_errors[] = "A deliverable name is required";
        }
        if(!$this->isValidDate($this->getEndDate()) || !$this->isValidDate($this->getStartDate()))
        {
            $this->_errors[] = "Please enter a valid start date and end date. Format: YYYY-MM-DD";
        }
        else if( strtotime($this->getStartDate())  > strtotime($this->getEndDate()) ||  strtotime($this->getEndDate())  < strtotime($this->getStartDate()) )
        {
            $this->_errors[] = "Start date must be before end date";
        }
        if($this->_sid == "" || $this->_sid == null)
        {
            $this->_errors[] = "A valid semester is required";
        }

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
     * @return array returns an array of group ids based on semester
     */
    private function getAllGroupIds($sid)
    {
        $groupIds = array();
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT gid FROM Groups WHERE sid=:sid");
        $query->execute(array(":sid" => $this->_sid));
        while($data = $query->fetch())
        {
            $groupIds[] = $data['gid'];
        }

        return $groupIds;
    }

    public function create()
    {
        $this->validate();
        if(!empty($this->getErrors()))
            return false;
        
        $pdo = Registry::getConnection();
        try
        {
            $pdo->beginTransaction();

            $query = $pdo->prepare("INSERT INTO Deliverables (dName, startDate, endDate) VALUES (:name, :start, :end) ");
            $query->bindValue(":name", $this->getName());
            $query->bindValue(":start", $this->getStartDate());
            $query->bindValue(":end", $this->getEndDate());
            $query->execute();

            $lastId = $pdo->lastInsertId();

            $groupIds = (empty($this->_gids) ? $this->getAllGroupIds($this->_sid) : $this->_gids);

            if(empty($groupIds))
                throw new Exception("No groups found for this semester");

            foreach($groupIds as $gid)
            {
                $query2 = $pdo->prepare("INSERT INTO GroupDeliverables VALUES (:gid, :did)");
                $query2->bindValue(":gid", $gid);
                $query2->bindValue(":did", $lastId);
                $query2->execute();
            }

            return $pdo->commit();

        }
        catch(Exception $e)
        {
            $pdo->rollBack();
            $this->_errors[] = $e->getMessage();
        }
    }
}