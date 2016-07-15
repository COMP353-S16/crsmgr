<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/14/2016
 * Time: 12:29 PM
 */
class UploadHandler
{
    private $_cid;

    private $_gid;

    private $_did;

    private $_uid;

    private $_errors = array();

    protected $_directory = 'uploads/';

    protected $_allowed = array ( );

    private $_file = array (
        "save_as" => ""
    );

    /**
     * @var File
     */
    private $_File;

    public function __construct($cid, $gid, $did, $uid,  $file)
    {
        $this->_gid = $gid;
        $this->_cid = $cid;
        $this->_did = $did;
        $this->_uid = $uid;



        $this->setFile($file);

        $this->_allowed = CoreConfig::settings()['uploads']['allowed_files'] + $this->_allowed ;
    }

    private function setFile($file)
    {
        $this->_File = new File($file);


        $this->_file['save_as'] = $this->_File->getFileName();

    }

    /**
     *
     */
    protected function validate()
    {
        if(!in_array($this->_File->getFileExtension(), $this->_allowed))
        {
            $this->_errors[] = "File type not allowed. Allow";
        }
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->_File;
    }

    private function getFileErrors()
    {
        switch ($this->_File->getFileError()) {
            case UPLOAD_ERR_INI_SIZE:
                $this->_errors[] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $this->_errors[] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $this->_errors[] = 'The uploaded file was only partially uploaded.';
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->_errors[] = 'No file was uploaded.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $this->_errors[] = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $this->_errors[] = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
                break;
            case UPLOAD_ERR_EXTENSION:
                $this->_errors[] = 'File upload stopped by extension. Introduced in PHP 5.2.0.';
                break;
            default:
                $this->_errors[] = 'Unknown upload error';
                break;
        }
    }



    public function getSavedAsName()
    {
        return $this->_file['save_as'];
    }



    private function makeUnique()
    {

        $timeparts = explode(" ",microtime());
        $unique = bcadd(($timeparts[0]*1000),bcmul($timeparts[1],1000));

        $this->_file['save_as'] = $this->_File->getBaseName() . "_" . $unique . '.' . $this->_File->getFileExtension();
    }

    public function getBuildDirectory()
    {
        return $this->_directory . $this->_cid . '/' . $this->_did . '/' . $this->_did . '/';  // Directory to upload file;
    }

    protected function createDirectory($dir, $permissions = 0777)
    {
        if (!file_exists($dir)) {
            mkdir($dir, $permissions, true);
        }
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Inserts the record in the database
     */
    protected function insert()
    {
        $pdo = Registry::getConnection();

        $query = $pdo->prepare("INSERT INTO Files (gid, did, fName, fType) VALUES (:gid, :did, :fName, :fType)");

        $params = array (
            ":gid" => $this->_gid,
            ":did" => $this->_did,
            ":fName" => $this->_File->getBaseName(),
            ":fType" => $this->_File->getFileExtension()
        );

        if($query->execute($params)) {
            $fid = $pdo->lastInsertId();
            $fileSize = filesize($this->getBuildDirectory() . $this->getSavedAsName()) / 1024; // in KB
            $userID = $this->_uid;

            $query = $pdo->prepare("INSERT INTO Versions (uploaderId, size, uploadDate, fid) VALUES (:uploaderId, :size, NOW(), :fid)");

            $params = array(
                ":uploaderId" => $userID,
                ":size" => $fileSize,
                ":fid" => $fid
            );

            return $query->execute($params);
        } else
        {
            return false;
        }
    }

    public function upload()
    {
        $this->validate();
        if(empty($this->_errors) && UPLOAD_ERR_OK === $this->_File->getFileError())
        {
            $uploadDirectory =  $this->getBuildDirectory();
            $this->createDirectory($uploadDirectory);
            $this->makeUnique();

            $success = move_uploaded_file($this->_File->getTempName(), $uploadDirectory  . $this->getSavedAsName());
            chmod($uploadDirectory  . $this->getSavedAsName(), 0644);

            if($success)
            {
                if(!$this->insert())
                {
                    $this->_errors[] = "Could not upload file into database";
                }
                else
                    return true;
            }
        }
        else
        {
            $this->getFileErrors();
        }

        return false;
    }

}