<?php

/**
 * Class PromoteMember
 */
class PromoteMember
{

    /**
     * @var
     */
    private $_uid;

    /**
     * @var
     */
    private $_gid;

    /**
     * @var array
     */
    private $_errors = array();

    /**
     * PromoteMember constructor.
     *
     * @param $uid
     * @param $gid
     */
    public function __construct($uid, $gid)
    {
        $this->_gid = $gid;
        $this->_uid = $uid;
    }

    /**
     *
     */
    private function validate()
    {
        if ($this->_gid == "" || $this->_uid == "")
        {
            $this->_errors[] = "Group and User required";
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
     * @return bool
     */
    public function promote()
    {
        $this->validate();
        if (!empty($this->_errors))
        {
            return false;
        }
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("UPDATE Groups SET leaderId=:leaderId WHERE gid=:gid");
        $query->bindValue(":gid", $this->_gid);
        $query->bindValue(":leaderId", $this->_uid);

        return $query->execute();
    }

}