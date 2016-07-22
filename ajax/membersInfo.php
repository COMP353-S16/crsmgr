<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$gid = $_REQUEST['gid'];

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT gm.uid FROM GroupMembers gm WHERE gm.gid=:gid ");
$query->bindValue(":gid", $gid);
$query->execute();

$info =array("data" => array());

while($member = $query->fetch()) {

    $user = new User($member['uid']);

    $info['data'][] = array(
        "name" => $user->getFirstName() .' ' .$user->getLastName(),
        "username" => $user->getUsername(),
        "email" => $user->getEmail()

    );
}

echo json_encode($info);