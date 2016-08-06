<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/21/2016
 * Time: 12:34 PM
 */
abstract class NewGroup
{

    /**
     * @var
     */
    private $_name;

    /**
     * @var array
     */
    private $_uids = array();

    /**
     * @var array
     */
    private $_errors = array();

    /**
     * @var
     */
    private $_leaderId;

    /**
     * @var
     */
    private $_maxBandwidth;

    /**
     * @var
     */
    private $_sid;

    /**
     * NewGroup constructor.
     */
    public function __construct()
    {
    }


    /**
     * @param $name
     */
    public function setGroupName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getGroupName()
    {
        return $this->_name;
    }

    /**
     * @param $id
     */
    public function addUid($id)
    {
        $this->_uids[] = $id;
    }

    /**
     * @param $error
     */
    protected function setError($error)
    {
        $this->_errors[] = $error;
    }

    /**
     * @param $id
     */
    public function removeUid($id)
    {
        $this->_uids = array_diff($this->_uids, array($id));
    }

    /**
     * @param array $uids
     */
    public function setUids(array $uids)
    {
        $this->_uids = $uids;
    }

    /**
     * @return array
     */
    public function getUids()
    {
        return $this->_uids;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @param $id
     */
    public function setLeaderId($id)
    {
        $this->_leaderId = $id;
    }

    /**
     * @return mixed
     */
    public function getLeaderId()
    {
        return $this->_leaderId;
    }

    /**
     * @param $bandwidth
     */
    public function setMaxBandwidth($bandwidth)
    {
        $this->_maxBandwidth = $bandwidth;
    }

    /**
     * @return mixed
     */
    public function getMaxBandwidth()
    {
        return $this->_maxBandwidth;
    }

    /**
     * @param $sid
     */
    public function setSemesterId($sid)
    {
        $this->_sid = $sid;
    }

    /**
     * @return mixed
     */
    public function getSemesterId()
    {
        return $this->_sid;
    }

    /**
     * @return mixed
     */
    abstract public function validate();
}