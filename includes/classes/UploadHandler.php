<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/14/2016
 * Time: 12:29 PM
 */
class UploadHandler
{


    private $_gid = null;

    private $_did = null;

    private $_uid;

    private $_errors = array();

    protected $_directory = 'uploads/';

    protected $_allowed = array();

    /**
     * @type GroupFiles
     */
    private $_GroupFiles;

    /**
     * @type Group
     */
    private $_Group;



    private $_file = array(
        "save_as" => ""
    );

    /**
     * @var File
     */
    private $_File;


    private $_fid; // if the file already exists, store its id

    /**
     * UploadHandler constructor.
     *
     * @param $gid group id
     * @param $did deliverable id
     * @param $uid user id
     * @param $file file
     */
    public function __construct($gid, $did, $uid, $file)
    {
        $this->_gid = $gid;

        $this->_did = $did;
        $this->_uid = $uid;


        $this->setFile($file);

        $this->_allowed = CoreConfig::settings()['uploads']['allowed_files'] + $this->_allowed;

        $this->_Group = new Group($this->_gid);
        $this->_GroupFiles = $this->_Group->getGroupFiles();


        // check to see if this file exists
        $this->extractFileId();
    }

    /**
     * @param $dir sets the main directory in which all group folders will be located
     */
    public function setUploadDirectory($dir)
    {
        if ($dir != "")
        {
            $this->_directory = $dir;
        }
    }

    public function getUploadDirectory()
    {
        return $this->_directory;
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

        if(is_null($this->_did) || $this->_did == "" || !is_numeric($this->_did))
        {
            $this->_errors[] = "No deliverable found";
            return;
        }
        if(is_null($this->_gid) || $this->_gid =="" || !is_numeric($this->_gid))
        {
            $this->_errors[] = "No group found";
            return;
        }
        if(!Files::isValidFileName($this->_File->getFileName()))
        {
            $this->_errors[] = "Filename invalid. Cannot contain special characters \\ / : * ? \" < > |";
        }
        if (!in_array($this->_File->getFileExtension(), $this->_allowed))
        {
            $this->_errors[] = "File type not allowed. Allow files: " . implode(", ", $this->_allowed);
        }
        
        if ($postUploadSize > $this->_Group->getMaxUploadSize())
        {
            $er = "Maximum bandwidth allotted exceeded.";
            $er .= "<ul>";
            $er .= "<li>Maximum bandwidth: " . $this->_Group->getMaxUploadSize() . "MB</li>";
            $er .= "<li>Current upload: " . number_format($fileSize, 2) . "MB</li>";
            $er .= "<li>Post upload size: " . number_format($postUploadSize, 2) . "MB</li>";
            $er .= "</ul>";

            $this->_errors[] = $er;

        }

    }



    /**
     * @return mixed returns null if no FID exists for certain file. This should be in its own class.
     */
    private function extractFileId()
    {
        $pdo = Registry::getConnection();
        // does this file name already exist?
        $query = $pdo->prepare("SELECT fid FROM Files WHERE gid=:gid AND did=:did AND fName = :name AND fType=:ftype AND fid NOT IN (SELECT fid FROM DeletedFiles) LIMIT 1");
        $params = array(
            ":did"   => $this->_did,
            ":gid"   => $this->_gid,
            ":name"  => $this->_File->getBaseName(),
            ":ftype" => $this->_File->getFileExtension()
        );

        $query->execute($params);
        $data  = $query->fetch();
        $this->_fid = $data['fid'];

        return $this->_fid;
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
        switch ($this->_File->getFileError())
        {
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

    public static function makeSafe($file)
    {
        // Remove any trailing dots, as those aren't ever valid file names.
        $file = rtrim($file, '.');

        $regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');

        return trim(preg_replace($regex, '', $file));
    }

    public function getSavedAsName()
    {
        return $this->_file['save_as'];
    }


    private function makeUnique()
    {

        $timeparts = explode(" ", microtime());
        $unique = bcadd(($timeparts[0] * 1000), bcmul($timeparts[1], 1000));

        $this->_file['save_as'] = self::makeSafe($this->_File->getBaseName()) . "_" . $unique . '.' . $this->_File->getFileExtension();
    }

    /**
     * @return string returns the upload directory of the file which is located in a group folder given by the group id
     */
    public function getBuildDirectory()
    {
        return $this->getUploadDirectory() . $this->_gid . '/';  // Directory to upload file;
    }

    /**
     * @param $dir directory name
     * @param int $permissions directory permissions
     */
    protected function createDirectory($dir, $permissions = 0777)
    {
        if (!file_exists($dir))
        {
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
     * @return bool returns true if filename already exists for particular deliverable of group
     */
    private function isRevision()
    {

        // if this file already exists and it's not permanently deleted, the user needs to recover that file before uploading revision
        return $this->_fid  != NULL ;
    }




    private function insertRevision($fid)
    {

        $pdo = Registry::getConnection();
        $fileSize = ($this->_File->getFileSize() / 1024 / 1024); // in KB
        // file data. this is only activated based on application settings
        $blob = null;
        if(CoreConfig::settings()['uploads']['storageDB'])
        {
            $blob = $this->_File->getBlob();
        }
        try
        {
            $query = $pdo->prepare("INSERT INTO Versions (uploaderId, physicalName, size, uploadDate, fid, ip, data, upload_dir) VALUES (:uploaderId, :name, :size, NOW(), :fid, :ip, :data, :dir)");

            $query->bindValue(":uploaderId", $this->_uid);
            $query->bindValue(":size", $fileSize);
            $query->bindValue(":name", $this->getSavedAsName());
            $query->bindValue(":dir", $this->getBuildDirectory());  // we want to store the directory where the file is located. This is the parent directory
            $query->bindValue(":fid", $fid);
            $query->bindValue(":ip", $this->get_client_ip());
            $query->bindValue(":data", $blob, PDO::PARAM_LOB);

            return $query->execute();
        }
        catch (PDOException $e)
        {
            $this->_errors[] = $e->getMessage();
        }


    }


    public function getFileId()
    {
        return $this->_fid;
    }



    /**
     * Inserts the record in the database
     */
    private function insert()
    {

        // if it's a revision, skip the rest
        if ($this->isRevision())
        {
            return $this->insertRevision($this->getFileId());
        }

        $pdo = Registry::getConnection();
        try
        {
            $query = $pdo->prepare("INSERT INTO Files (gid, did, fName, fType, mime) VALUES (:gid, :did, :fName, :fType, :mime)");
            $params = array(
                ":gid"   => $this->_gid,
                ":did"   => $this->_did,
                ":fName" => $this->_File->getBaseName(),
                ":fType" => $this->_File->getFileExtension(),
                ":mime"  => $this->_File->getMime()
            );
            if ($query->execute($params))
            {
                $lastInsert = $pdo->lastInsertId();
                return $this->insertRevision($lastInsert);
            }
        }
        catch(Exception $e)
        {

            $this->_errors[] =$e->getMessage();
        }


        return false;
    }

    /**
     * @return bool returns true if file successu'ly uploaded in directory
     */
    public function upload()
    {
        $this->validate();
        if (empty($this->_errors) && UPLOAD_ERR_OK === $this->_File->getFileError())
        {

            // give a unique filename
            $this->makeUnique();

            $fileMoveSuccess = true; // this is used to check if the temp directory move was successful

            if(!CoreConfig::settings()['uploads']['storageDB'])
            {
                $uploadDirectory = $this->getBuildDirectory();
                $this->createDirectory($uploadDirectory);
                $fileMoveSuccess = move_uploaded_file($this->_File->getTempName(), $uploadDirectory . $this->getSavedAsName());
                chmod($uploadDirectory . $this->getSavedAsName(), 0644);
            }
            // if the file moved succesfully and record inserted into db
            if ($fileMoveSuccess && $this->insert())
            {
                return true;
            }
            else
            {
                $this->_errors[] = "Could not upload file into database";
            }
        }
        else
        {
            $this->getFileErrors();
        }

        return false;
    }

    private function get_client_ip()
    {
        $ip = getenv('HTTP_CLIENT_IP') ?:
            getenv('HTTP_X_FORWARDED_FOR') ?:
                getenv('HTTP_X_FORWARDED') ?:
                    getenv('HTTP_FORWARDED_FOR') ?:
                        getenv('HTTP_FORWARDED') ?:
                            getenv('REMOTE_ADDR');

        return $ip;
    }

}