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

    public function __construct()
    {
    }

    public function setFiles(array $fids)
    {
        $this->_fids = $fids;
    }

    public function delete()
    {
        foreach($this->_fids as $fid)
        {
            
        }
    }
}