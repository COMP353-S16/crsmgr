<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$gid = $_REQUEST['gid'];/// should be sent via ajax

$data = array(
  "totalFiles" => 0,
  "totalDeletedFiles" => 0,
  "bandwidth" => 0,
  "usedBandwidth" => 0,
  "downloads" => 0,
  "revisions" => 0,
  "uploads" => 0
);


$GroupFiles = new GroupFiles($gid);
$Group = new Group($gid);

$data["totalFiles"] = $GroupFiles->getNumberOfFiles();
$data["bandwidth"] = $Group->getMaxUploadSize();
$data["usedBandwidth"] = number_format($GroupFiles->getUsedBandwidth(),2) . "MB";
$data["totalDeletedFiles"] = $GroupFiles->getTotalDeletedFiles();
$data["uploads"] = $GroupFiles->getNbOfUploadedFiles();

echo json_encode($data);