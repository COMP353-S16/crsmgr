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

    private $_errors = array();

    public function __construct(array $fids)
    {
        $this->_fids = $fids;
    }

    public function recover()
    {
        $pdo = Registry::getConnection();
        try
        {


            foreach($this->_fids as $fid)
            {
                $query = $pdo->prepare("SELECT * FROM Files WHERE fid=:fid");
                $query->execute(array(
                    ":fid" => $fid
                ));

                $this->moveFile($query->fetch());

            }


            return true;
        }
        catch(Exception $e)
        {
            $this->_errors = $e->getMessage();
        }
        return false;
    }


    private function moveFile($fileData)
    {
        $Files = new Files($fileData);
        $did = $Files->getDeliverableId();
        $fid = $Files->getId();
        $gid = $Files->getGroupId();

        
        $pdo = Registry::getConnection();
        try
        {
            $pdo->beginTransaction();
            /* get the latest FID for the file with the same name and type and deliverable. If other files were uploaded during the time files were in deleted
            then we'll need to get the FID of that file in order to transfer the deleted files in there.
    
            */
            $query = $pdo->prepare("SELECT fid FROM Files WHERE fName=:fName AND fType=:fType AND did=:did AND gid=:gid AND fid ORDER BY  fid DESC LIMIT 1");
            $params = array(
                ":did"   => $did,
                ":gid"   => $gid,
                ":fName"  => $Files->getFileName(),
                ":fType" => $Files->getFileExtension()
            );

            $query->execute($params);
            $data = $query->fetch();
            $Nfid = $data['fid'];

            if($Nfid==NULL)
                throw new Exception("Could not find last FID");
            //if it's the same one
            if($Nfid != $fid)
            {
                $versions = $Files->getVersions();
                /**
                 * @var $Version Version
                 */
                foreach($versions as $Version)
                {
                    $query1 = $pdo->prepare("UPDATE Versions SET fid=:fid WHERE vid=:vid");
                    $query1->bindValue(":fid", $Nfid);
                    $query1->bindValue(":vid", $Version->getVersionId());
                    $query1->execute();
                }

                $query3 = $pdo->prepare("DELETE FROM Files WHERE fid=:fid");
                $query3->bindValue(":fid", $fid);
                $query3->execute();
            }

            $query2 = $pdo->prepare("DELETE FROM DeletedFiles WHERE fid=:fid");
            $query2->bindValue(":fid", $fid);
            $query2->execute();

            return $pdo->commit();
        }
        catch(Exception $e)
        {

            $pdo->rollBack();
            $this->_errors[] = $e->getMessage();
            
        }

    }

    public function getErrors()
    {
        return $this->_errors;
    }
}