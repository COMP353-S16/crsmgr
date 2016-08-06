<?php

class DeleteGroup
{

    /**
     * @var
     */
    private $_gid;

    /**
     * @var array
     */
    private $_errors = array();


    /**
     * DeleteGroup constructor.
     *
     * @param $gid group id
     */
    public function __construct($gid)
    {
        $this->_gid = $gid;
    }

    /**
     * @return bool returns true if the group deletion was successful
     */
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

    /**
     * @return array returns an array of errors
     */
    public function getErrors()
    {
        return $this->_errors;
    }
}