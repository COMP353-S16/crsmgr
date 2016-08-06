<?php

/**
 * Class DeleteMember
 */
class DeleteMember
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
     * DeleteMember constructor.
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
     * @return bool
     */
    public function delete()
    {
        $pdo = Registry::getConnection();
        $query = $pdo->prepare("DELETE FROM GroupMembers WHERE gid=:gid AND uid=:uid");
        $query->bindValue(":gid", $this->_gid);
        $query->bindValue(":uid", $this->_uid);

        return $query->execute();
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }
}