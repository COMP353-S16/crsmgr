<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/14/2016
 * Time: 12:29 PM
 */
class UploadHandler
{


    private $_gid;

    private $_did;

    private $_uid;

    private $_errors = array();

    protected $_directory = 'uploads/';

    protected $_allowed = array ( );

    private $_GroupFiles;

    private $_Group;

    private $_file = array (
        "save_as" => ""
    );

    /**
     * @var File
     */
    private $_File;

    /**
     * UploadHandler constructor.

     * @param $gid group id
     * @param $did deliverable id
     * @param $uid user id
     * @param $file file
     */
    public function __construct($gid, $did, $uid,  $file)
    {
        $this->_gid = $gid;

        $this->_did = $did;
        $this->_uid = $uid;



        $this->setFile($file);

        $this->_allowed = CoreConfig::settings()['uploads']['allowed_files'] + $this->_allowed ;

        $this->_Group = new Group($this->_gid);
        $this->_GroupFiles = new GroupFiles($this->_gid);
    }

    public function setUploadDirectory($dir)
    {
        if($dir!="")
            $this->_directory = $dir;



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
        $usedBandwidth = $this->_GroupFiles->getUsedBandwidth();


        $fileSize = ($this->_File->getFileSize() / 1024 / 1024); // conver to MB

        $postUploadSize = $usedBandwidth + $fileSize;


        if(!in_array($this->_File->getFileExtension(), $this->_allowed))
        {
            $this->_errors[] = "File type not allowed. Allow";
        }


        if($postUploadSize > $this->_Group->getMaxUploadSize())
        {
            $er = "Maximum bandwidth allotted exceeded.";
            $er .= "<ul>";
            $er .= "<li>Maximum bandwidth: " . $this->_Group->getMaxUploadSize() . "MB</li>";
            $er .= "<li>Current upload: " . number_format($fileSize ,2) . "MB</li>";
            $er .= "<li>Post upload size: " . number_format($postUploadSize ,2) . "MB</li>";
            $er .= "</ul>";

            $this->_errors[] =  $er;

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
        return $this->_directory . $this->_gid .  '/';  // Directory to upload file;
    }

    /**
     * @param $dir directory name
     * @param int $permissions directory permissions
     */
    protected function createDirectory($dir, $permissions = 0777)
    {
        if (!file_exists($dir)) {
            mkdir($dir, $permissions, true);
        }
    }


    /**
     * @return array returns an array of various errors that might have occurred
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Inserts the record in the database
     */
    private function insert()
    {

        // if it's a revision, skip the rest
        if($this->isRevision())
            return $this->insertRevision();


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

            $query = $pdo->prepare("INSERT INTO Versions (uploaderId, physicalName, size, uploadDate, fid) VALUES (:uploaderId, :name, :size, NOW(), :fid)");

            $params = array(
                ":uploaderId" => $userID,
                ":size" => $fileSize,
                ":name" => $this->getSavedAsName(),
                ":fid" => $fid
            );

            return $query->execute($params);
        }

        return false;
    }

    /**
     * @return bool returns true if filename already exists for particular deliverable of group
     */
    private function isRevision()
    {
        $id = $this->getFileId();
        return $id   != NULL || $id != "";
    }


    /**
     * @return mixed returns null if no FID exists for certain file. This should be in its own class.
     */
    private function getFileId()
    {
        $pdo = Registry::getConnection();
        // does this file name already exist?
        $query = $pdo->prepare("SELECT fid FROM Files WHERE gid=:gid AND did=:did AND fName = :name LIMIT 1");
        $params = array(
            ":did" => $this->_did,
            ":gid" => $this->_gid,
            ":name" => $this->_File->getBaseName()
        );

        $query->execute($params);
        $data = $query->fetch();
        return $data['fid'];
    }


    private function insertRevision()
    {

        $pdo = Registry::getConnection();

        $fileSize = filesize($this->getBuildDirectory() . $this->getSavedAsName()) / 1024; // in KB

        $query = $pdo->prepare("INSERT INTO Versions (uploaderId, physicalName, size, uploadDate, fid, ip) VALUES (:uploaderId, :name, :size, NOW(), :fid, :ip)");

        $params = array(
            ":uploaderId" => $this->_uid,
            ":size" => $fileSize,
            ":name" => $this->getSavedAsName(),
            ":fid" => $this->getFileId(),
            ":ip" => $this->get_client_ip()
        );

        return $query->execute($params);

    }


    /**
     * @return bool returns true if file successu'ly uploaded in directory
     */
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

    private function get_client_ip() {
        $ip = getenv('HTTP_CLIENT_IP')?:
            getenv('HTTP_X_FORWARDED_FOR')?:
                getenv('HTTP_X_FORWARDED')?:
                    getenv('HTTP_FORWARDED_FOR')?:
                        getenv('HTTP_FORWARDED')?:
                            getenv('REMOTE_ADDR');
        return $ip;
    }

}