<?php

/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 7/19/2016
 * Time: 8:42 PM
 */
class RecoverFiles
{

    private $_fids;

    public function __construct(array $fids)
    {
        $this->_fids = $fids;
    }

    public function recover()
    {
        $pdo = Registry::getConnection();
        try
        {
            $pdo->beginTransaction();

            foreach($this->_fids as $fid)
            {
                $query = $pdo->prepare("DELETE FROM DeletedFiles WHERE fid=:fid");
                $query->execute(array(
                    ":fid" => $fid
                ));

            }

            return $pdo->commit();
        }
        catch(Exception $e)
        {
            $pdo->rollBack();
        }
        return false;
    }

}