<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$gid = $_REQUEST['gid'];

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT u.uid FROM Groups g, Users u, GroupMembers m
                                WHERE g.gid=:gid AND g.gid = m.gid AND m.uid = u.uid");
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