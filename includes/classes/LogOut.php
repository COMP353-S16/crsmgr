<?php

/**
 * Created by PhpStorm.
 * User: syrinekrim
 * Date: 2016-07-16
 * Time: 1:09 PM
 */
class LogOut
{

    public function __construct()
    {

    }

    public function logout()
    {
        session_start();

        session_destroy();

        header("location: ../../index.php");

        exit;
    }


}

?>