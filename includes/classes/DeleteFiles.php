<?php

class DeleteFiles
{

    /**
     * @var array
     */
    private $_fids;

    /**
     * @var \user
     */
    private $_uid;

    /**
     * @var array
     */
    private $_errors = array();


    /**
     * DeleteFiles constructor.
     *
     * @param $uid        user id
     * @param array $fids a list of file ids to be deleted
     */
    public function __construct($uid, array $fids)
    {
        $this->_uid = $uid;
        $this->_fids = $fids;
    }

    /**
     * @param array $fids
     */
    public function setFiles(array $fids)
    {
        $this->_fids = $fids;
    }

    /**
     * @return bool returns true if the given files were successfully deleted
     */
    public function delete()
    {
        $pdo = Registry::getConnection();
        try
        {
            $pdo->beginTransaction();

            foreach ($this->_fids as $fid)
            {
                $query = $pdo->prepare("INSERT INTO DeletedFiles (fid, uid, dateDelete) VALUES (:fid, :uid, NOW())");
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

    /**
     * @return array returns a list of errors
     */
    public function getErrors()
    {
        return $this->_errors;
    }
}