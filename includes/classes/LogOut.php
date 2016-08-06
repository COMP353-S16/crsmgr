<?php

class LogOut
{

    /**
     * LogOut constructor.
     */
    public function __construct()
    {

    }

    /**
     *
     */
    public function logout()
    {
        session_start();

        session_destroy();

        header("location: ../../index.php");

        exit;
    }


}

?>