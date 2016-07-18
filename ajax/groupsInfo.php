<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT gid FROM Groups");

$query->execute();

$info =array("data" => array());


while($groups = $query->fetch()) {

    $Group = new Group($groups["gid"]);
    $creatorId = $Group->getCreatorId();
    $leaderId = $Group->getLeaderId();

    $leader = new User($leaderId);
    $creator = new User($creatorId);

    $info['data'][] = array(
        "gid" => $groups["gid"],
        "sid" => $Group->GetSid(),
        "creatorId" => $creator->getFirstName() . " "  . $creator->getLastName(),
        "leaderId" => $leader->getFirstName() . " "  . $leader->getLastName(),
        "gName" => $Group->getGName()
    );
}

echo json_encode($info);