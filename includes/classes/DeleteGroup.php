<?php

/**
 * Created by PhpStorm.
 * User: fatin
 * Date: 2016-07-18
 * Time: 8:11 PM
 */
class DeleteGroup
{

    private $_gid;
    private $_errors = array();


    public function __construct($gid)
    {
        $this->_gid = $gid;
    }

    public function deleteGroup()
    {
        $pdo = Registry::getConnection();
        try
        {
            $pdo->beginTransaction();
            $query = $pdo->prepare("DELETE FROM Groups WHERE gid=:gid");
            $query->bindValue("gid", $this->_gid);
            $query->execute();


            return $pdo->commit();
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
            $this->_errors[] = $e->getCode();
            $pdo->rollBack();
        }

        return false;
    }

    public function getErrors()
    {
        return $this->_errors;
    }
}