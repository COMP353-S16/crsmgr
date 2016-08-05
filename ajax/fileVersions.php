<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 7/14/2016
 * Time: 7:49 PM
 */
$fid = $_REQUEST['fid'];

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT * FROM Files WHERE fid=:fid");
$query->execute(array(":fid" => $fid));
$data = $query->fetch();

$Files = new Files($data);

$info =array("data" => array());

$versions = $Files->getVersions();

/**
 * @var $Version Version
 */
foreach($versions as $Version) {

    $User = new User($Version->getUploaderId());
    $info['data'][] = array(
        "vid" => $Version->getVersionId(),
        "gid" => $Files->getGroupId(),
        "user" => $User->getFullName(),
        "date" => $Version->getUploadDate(),
        "filename" => $Version->getSavedName(),
        "size" => round($Version->getSize(),2) . " MB",

    );
}

echo json_encode($info);