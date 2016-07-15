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
$query = $pdo->prepare("SELECT f.*,
  v.uploaderId AS UPLOADER_ID,
  v.physicalName AS FILE_SAVED_NAME,
  v.size AS FILE_SIZE,
  v.uploadDate as LAST_DATE,
  (SELECT COUNT(*) FROM Versions ver WHERE f.fid = ver.fid AND ver.vid < v.vid) AS REVISIONS
  FROM Files f, Versions v
    WHERE f.fid = v.fid AND v.vid =
                            (SELECT MAX(v.vid)
                              FROM Versions v
                                WHERE v.fid = f.fid)
  AND f.did = :did AND f.gid = :gid");

$query->bindValue(":did", 1);
$query->bindValue(":gid", 1);
$query->execute();

$info =array("data" => array());

while($files = $query->fetch()) {


    $info['data'][] = array(
        "fid" => $files['fid'],
        "filename" => $files['fName'],
        "ldate" => $files['LAST_DATE'],
        "version" => $files['REVISIONS']

    );
}

echo json_encode($info);