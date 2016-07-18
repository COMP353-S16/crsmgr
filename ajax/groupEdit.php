<?php
/**
 * Created by PhpStorm.
 * User: fatin
 * Date: 2016-07-17
 * Time: 9:42 PM
 */
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');


echo json_encode($info);