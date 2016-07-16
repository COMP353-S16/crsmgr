<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');


$Logout = new LogOut();
$Logout->logout(); 
