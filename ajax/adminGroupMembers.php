<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$gid = $_REQUEST['gid'];

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT gm.uid FROM GroupMembers gm WHERE gm.gid=:gid  ");
$query->bindValue(":gid", $gid);
$query->execute();

$info =array("data" => array());

$Group = new Group($gid);
while($member = $query->fetch()) {

    $Student = new Student($member['uid']);

    $info['data'][] = array(
        "uid" => $Student->getUid(),
        "name" => $Student->getFirstName() .' ' .$Student->getLastName(),
        "section" => $Student->getSemesters()->getSectionName($_REQUEST['sid']),
        "email" => $Student->getEmail(),
        "isLeader" => $Group->isLeader($Student->getUid())

    );
}

echo json_encode($info);