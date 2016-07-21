<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/21/2016
 * Time: 12:34 PM
 */
abstract class NewGroup
{

    private $_name;

    private $_uids = array();

    private $_errors = array();

    private $_leaderId;

    private $_maxBandwidth;

    public function __construct()
    {
    }



    public function setGroupName($name)
    {
        $this->_name = $name;
    }

    public function getGroupName()
    {
        return $this->_name;
    }

    public function addUid($id)
    {
        $this->_uids[] = $id;
    }

    protected function setError($error)
    {
        $this->_errors[] = $error;
    }

    public function removeUid($id)
    {
        $this->_uids = array_diff($this->_uids, array($id));
    }

    public function setUids(array $uids)
    {
        $this->_uids = $uids;
    }

    public function getUids()
    {
        return $this->_uids;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setLeaderId($id)
    {
        $this->_leaderId = $id;
    }

    public function getLeaderId()
    {
        return $this->_leaderId;
    }

    public function setMaxBandwidth($bandwidth)
    {
        $this->_maxBandwidth = $bandwidth;
    }

    public function getMaxBandwidth()
    {
        return $this->_maxBandwidth;
    }


    abstract public function validate();
}