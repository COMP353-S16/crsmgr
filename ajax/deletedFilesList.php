<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$gid = $_REQUEST['gid'];

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT del.fid FROM DeletedFiles del, Files f, Groups gr WHERE del.fid = f.fid AND gr.gid = :gid AND del.expiresOn >= NOW()");
$query->bindValue(":gid", $gid);
$query->execute();

$info =array("data" => array());
while($data = $query->fetch()) {

    $Files = new Files($data['fid']);
    $Deliverable = new Deliverable($Files->getDeliverableId());

    $DeletedFiles = new DeletedFiles($data['fid']);

    $info['data'][] = array(
        "fid" => $Files->getId(),
        "filename" => $Files->getFileName() . '.' . $Files->getFileExtension(),
        "deliverable" => $Deliverable->getDName(),
        "revisions" => $Files->getNumberOfRevisions(),
        "size" => round($Files->getSize(),2) . " KB",
        "expires" => date_format(date_create($DeletedFiles->getExpiryDate()), 'M d, Y at H:i:s')

    );
    
}

echo json_encode($info);