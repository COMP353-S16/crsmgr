<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$GroupFiles=  new GroupFiles(1);

print_r($GroupFiles->getFileById(133));