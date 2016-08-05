<?php

/**
 * Created by PhpStorm.
 * User: dimitri
 * Date: 2016-07-17
 * Time: 10:24 PM
 */
class DeleteFiles
{

    private $_fids;

    private $_uid;

    private $_errors;

    /**
     * DeleteFiles constructor.
     *
     * @param $uid user id deleting files
     */
    public function __construct($uid, array $fids)
    {
        $this->_uid = $uid;
        $this->_fids = $fids;
    }

    public function setFiles(array $fids)
    {
        $this->_fids = $fids;
    }

    public function delete()
    {
        $pdo = Registry::getConnection();
        try
        {
            $pdo->beginTransaction();

            foreach ($this->_fids as $fid)
            {
                $query = $pdo->prepare("INSERT INTO DeletedFiles VALUES (:fid, :uid, NOW(), NOW() + INTERVAL 1 DAY)");
                $query->execute(array(
                    ":fid" => $fid,
                    ":uid" => $this->_uid
                ));

            }

            return $pdo->commit();
        }
        catch (Exception $e)
        {
            $this->_errors[] = $e->getMessage();
            $pdo->rollBack();
        }

        return false;
    }

    public function getErrors()
    {
        return $this->_errors;
    }
}