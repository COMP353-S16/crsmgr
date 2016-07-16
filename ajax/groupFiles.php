<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 7/14/2016
 * Time: 7:49 PM
 */
$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT * FROM Files f WHERE f.did = :did AND f.gid = :gid");

$query->bindValue(":did", 1);
$query->bindValue(":gid", 1);
$query->execute();

$info =array("data" => array());


while($files = $query->fetch()) {

    $DFile = new DFile($files['fid']);



    $info['data'][] = array(
        "fid" => $files['fid'],
        "filename" => $DFile->getFileName(),
        "ldate" => $DFile->getLatestVersion()->getUploadDate(),
        "deliverable" => $DFile->getDeliverableId(),
        "revisions" => $DFile->getNumberOfRevisions(),
        "size" => round($DFile->getSize(),3) . " KB"

    );
}

echo json_encode($info);