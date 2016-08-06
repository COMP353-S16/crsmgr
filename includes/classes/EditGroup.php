<?php

/**
 * Created by PhpStorm.
 * User: dimitri
 * Date: 2016-07-27
 * Time: 3:32 PM
 */
class EditGroup extends NewGroup
{

    /**
     * @var
     */
    private $_gid;

    /**
     * EditGroup constructor.
     *
     * @param $gid
     */
    public function __construct($gid)
    {
        $this->_gid = $gid;
    }

    /**
     *
     */
    public function validate()
    {
        if (empty($this->getGroupName()))
        {
            $this->setError("Group must have a name!");
        }
        if (!is_numeric($this->getMaxBandwidth()) || $this->getMaxBandwidth() == null)
        {
            $this->setError("Please enter a valid bandwidth number");
        }
        else if ($this->getMaxBandwidth() < 1)
        {
            $this->setError("Bandwidth should be at least 1 MB");
        }
    }

    /**
     * @return bool
     */
    public function edit()
    {
        $this->validate();
        if (!empty($this->getErrors()))
        {
            return false;
        }

        $pdo = Registry::getConnection();
        $query = $pdo->prepare("UPDATE Groups SET gName=:gname, maxUploadsSize=:upload WHERE gid=:gid");
        $query->bindValue(":gname", $this->getGroupName());
        $query->bindValue(":upload", $this->getMaxBandwidth());
        $query->bindValue(":gid", $this->_gid);

        return $query->execute();
    }


}