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

    private $_blob;

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

        $this->_blob = file_get_contents($this->_fileData['tmp_name']);
    }

    public function getBlob()
    {
        return $this->_blob;
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

    public function getMime($path)
    {

        if(!function_exists('mime_content_type')) {
            return self::mime_content_type($path);
        }
        return "";

    }

    public static function mime_content_type($extension)
    {


        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );



        return $mime_types[$extension];
    }

}