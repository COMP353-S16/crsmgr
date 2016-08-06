<?php


class DeletedFiles
{

    /**
     * @var array
     */
    private $_data = array();

    /**
     * DeletedFiles constructor.
     *
     * @param $data array file data (as taken from row of database)
     */
    public function __construct(array $data)
    {
        $this->_data = $data;

    }

    /**
     * @return string returns the deleted date
     */
    public function getDateDeleted()
    {
        return $this->_data['dateDelete'];
    }

    /**
     * @return string returns the date the file will expire on
     */
    public function getExpiryDate()
    {
        return $this->_data['expiresOn'];
    }

    /**
     * @return bool returns true if the file is expired (i.e. no longer accessible)
     */
    public function isExpired()
    {
        return (strtotime(date("Y-m-d H:i:s")) >= strtotime($this->getExpiryDate()));
    }

    /**
     * @return int returns deleter id
     */
    public function getDeleterId()
    {
        return $this->_data['uid'];
    }
}