<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
//header("Content-type: text/json");
$k = array();

$Group = new Group($_REQUEST['gid']);
$GroupStats = new GroupStats($Group);
$stats = $GroupStats->getStats();



$usedBandwith = $GroupStats->getUsedBandwidth();
$allowedBandwidth = $Group->getMaxUploadSize();


$used = ($usedBandwith / $allowedBandwidth) * 100;
$free = 100 - $used;



$data = array(
  "used" => $used,
  "free" => $free,
  "total" => $allowedBandwidth,
  "_totalFiles" =>$GroupStats->getTotalFiles(),
  "_totalUploadedFiles" => $GroupStats->getNbOfUploadedFiles(),
  "_totalDownloadedFiles" =>$GroupStats->getNumberOfDownloads(),
  "_totalDeletedFiles" => $GroupStats->getTotalDeletedFiles(),
  "_totalPermanentDeletedFiles" => $GroupStats->getTotalPermanentDelete()
);

echo json_encode($data);

exit;

?>









    
