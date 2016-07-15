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
$query = $pdo->prepare("SELECT * FROM Files f WHERE f.did=:did AND f.gid=:gid");
$query->bindValue(":did", 1);
$query->bindValue(":gid", 1);
$query->execute();

$info =array("data" => array());

while($files = $query->fetch()) {


    $info['data'][] = array(
        "fid" => $files['fid'],
        "filename" => $files['fName'],
        "ldate" => "",
        "version" => ""

    );
}

echo json_encode($info);