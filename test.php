<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');


$Archive = new Archive(new Group(62));

$Archive->archive();

print_r($Archive->getErrors());
exit;
?>