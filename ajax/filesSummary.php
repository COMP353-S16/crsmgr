<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$gid = $_REQUEST['gid'];/// should be sent via ajax

$data = array(
  "totalFiles" => 0,
  "totalDeletedFiles" => 0,
  "bandwidth" => 0,
  "usedBandwidth" => 0,
  "uploads" => 0
);




$Group = new Group($gid);
$GroupFiles = $Group->getGroupFiles();



$usedPer = ($Group->getMaxUploadSize()>0 ?  $GroupFiles->getUsedBandwidth() / $Group->getMaxUploadSize() : 0);


$data["totalFiles"] = $GroupFiles->getNumberOfFiles();
$data["bandwidth"] = number_format($GroupFiles->getUsedBandwidth(),2)  . " / " . number_format($Group->getMaxUploadSize(),2) .  "MB (".number_format($usedPer,1)."%)";
$data["usedBandwidth"] = number_format($GroupFiles->getUsedBandwidth() ,2) . "MB";
$data["totalDeletedFiles"] = $GroupFiles->getTotalDeletedFiles();
$data["uploads"] = $GroupFiles->getNbOfUploadedFiles();


echo json_encode($data);