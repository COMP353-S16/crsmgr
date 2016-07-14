<?php

/**
 * Created by PhpStorm.
 * User: Server
 * Date: 7/14/2016
 * Time: 1:51 PM
 */
class File
{

    private $_file;



    private $_fileData = array (
        "name" => "",
        "basename" => "",
        "type" => "",
        "tmp_name" => "",
        "error" => "",
        "size" => "",
        "extension" => ""
    );

    public function __construct($file)
    {
   
        $this->_file = $file;
        $this->extract();
    }

    private function extract()
    {
        $name     = $this->_file['name'];
        $type     = $this->_file['type'];
        $tmp_name = $this->_file['tmp_name'];
        $error    = $this->_file['error'];
        $size     = $this->_file['size'];

        $parts = pathinfo($name);

        $this->_fileData['name'] = $name;
        $this->_fileData['basename'] = $parts['filename'];
        $this->_fileData['type'] = $type;
        $this->_fileData['tmp_name'] = $tmp_name;
        $this->_fileData['error'] = $error;
        $this->_fileData['size'] = $size;
        $this->_fileData['extension'] = $parts['extension'];
    }


    public function getFileError()
    {
        return $this->_file['error'];
    }


    public function getFileName()
    {
        return $this->_fileData['name'];
    }

    public function getBaseName()
    {
        return $this->_fileData['basename'];
    }

    public function getFileType()
    {
        return $this->_fileData['type'];
    }

    public function getFileExtension()
    {
        return $this->_fileData['extension'];
    }

    public function getSavedAsName()
    {
        return $this->_fileData['save_as'];
    }

    public function getTempName()
    {
        return $this->_fileData['tmp_name'];
    }

    public function getFileSize()
    {
        return $this->_fileData['size'];
    }



}