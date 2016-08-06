<?php

class File
{

    /**
     * @var
     */
    private $_file;

    /**
     * @var
     */
    private $_blob;

    /**
     * @var array
     */
    private $_fileData = array(
        "name" => "",
        "basename" => "",
        "type" => "",
        "tmp_name" => "",
        "error" => "",
        "size" => "",
        "extension" => ""
    );

    /**
     * File constructor.
     *
     * @param $file
     */
    public function __construct($file)
    {

        $this->_file = $file;
        $this->extract();
    }

    /**
     *
     */
    private function extract()
    {
        $name = $this->_file['name'];
        $type = $this->_file['type'];
        $tmp_name = $this->_file['tmp_name'];
        $error = $this->_file['error'];
        $size = $this->_file['size'];

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

    /**
     * @return mixed
     */
    public function getBlob()
    {
        return $this->_blob;
    }


    /**
     * @return mixed
     */
    public function getFileError()
    {
        return $this->_file['error'];
    }


    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->_fileData['name'];
    }

    /**
     * @return mixed
     */
    public function getBaseName()
    {
        return $this->_fileData['basename'];
    }

    /**
     * @return mixed
     */
    public function getFileType()
    {
        return $this->_fileData['type'];
    }

    /**
     * @return mixed
     */
    public function getFileExtension()
    {
        return $this->_fileData['extension'];
    }

    /**
     * @return mixed
     */
    public function getSavedAsName()
    {
        return $this->_fileData['save_as'];
    }

    /**
     * @return mixed
     */
    public function getTempName()
    {
        return $this->_fileData['tmp_name'];
    }

    /**
     * @return mixed
     */
    public function getFileSize()
    {
        return $this->_fileData['size'];
    }

    /**
     * @return mixed|string
     */
    public function getMime()
    {
        return self::getMimeContent($this->getFileExtension());
    }

    /**
     * @param $extension
     *
     * @return mixed
     */
    public static function getMimeContent($extension)
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