<?php

/**
 * Created by PhpStorm.
 * User: josep
 * Date: 7/20/2016
 * Time: 4:51 PM
 */
class Section
{

    /**
     * @var
     */
    protected $_sid;

    /**
     * @var
     */
    protected $_sectionName;

    /**
     * @var
     */
    protected $_startDate;

    /**
     * @var
     */
    protected $_endDate;

    /**
     * Section constructor.
     *
     * @param $sid
     */
    public function __construct($sid)
    {
        $this->_sid = $sid;
        $this->fetchSectionInfo();
    }

    /**
     *
     */
    private function fetchSectionInfo()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("SELECT * FROM Sections WHERE sid=:sid");
        $query->bindValue(":sid", $this->_sid);
        $query->execute();
        $data = $query->fetch();

        $this->_sectionName = $data['sName'];
        $this->_startDate = $data['startDate'];
        $this->_endDate = $data['endDate'];
    }

    /**
     * @return mixed
     */
    public function getSectionName()
    {
        return $this->_sectionName;
    }

    /**
     * @return mixed
     */
    public function getSid()
    {
        return $this->_sid;
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
}