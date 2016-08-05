<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT gid FROM Groups WHERE sid=:sid");
$query->bindValue(":sid", $_REQUEST['sid']);
$query->execute();


$groups = array();
while ($data = $query->fetch())
{
    $id = $data['gid'];


    $Group = new Group($id);
    $User = new User($Group->getLeaderId());
    $groups[] = array(
        "gid"  => $id,
        "name" => $Group->getGName() . ' :: (' . $User->getFirstName() . ' ' . $User->getLastName() . ')'
    );
}

//print_r($groups);
echo json_encode($groups);