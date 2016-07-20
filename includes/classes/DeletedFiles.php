<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/19/2016
 * Time: 1:20 PM
 */
class DeletedFiles
{
    
    private $_data;

    public function __construct($data)
    {
        $this->_data = $data;

    }

    public function getDateDeleted()
    {
        return $this->_data['dateDelete'];
    }

    public function getExpiryDate()
    {
        return $this->_data['expiresOn'];
    }

    public function isExpired()
    {
        return (strtotime(time()) >= strtotime($this->getExpiryDate()));
    }
}