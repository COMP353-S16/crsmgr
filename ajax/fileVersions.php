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

    $info['data'][] = array(
        "vid" => $Version->getVersionId(),
        "user" => $Version->getUploaderId(),
        "date" => $Version->getUploadDate(),
        "size" => round($Version->getSize(),2) . " KB",

    );
}

echo json_encode($info);