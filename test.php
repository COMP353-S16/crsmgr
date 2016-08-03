<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$GroupStats = new GroupStats(new Group(62));

print_r($GroupStats->getFileStats());
?>