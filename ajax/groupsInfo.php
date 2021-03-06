<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT gid FROM Groups ");

$query->execute();

$info = array("data" => array());


while ($groups = $query->fetch())
{

    $Group = new Group($groups["gid"]);
    $creatorId = $Group->getCreatorId();
    $leaderId = $Group->getLeaderId();

    $leader = new User($leaderId);
    $creator = new User($creatorId);

    // students in group
    $Students = $Group->getGroupStudents();
    // files in group

    $GroupFiles = new GroupFiles($groups['gid']);


    $members = array();
    $files = array();


    $info['data'][] = array(
        "gid"          => $groups["gid"],
        "sid"          => $Group->getSid(),
        "creatorId"    => $creator->getFullName(),
        "leaderId"     => $leader->getUid(),
        "leaderName"   => $leader->getFullName(),
        "isClosed"     => $Group->isGroupClosed(),
        "gName"        => $Group->getGName(),
        "totalMembers" => $Group->getTotalMembers(),
        "bandwidth"    => $Group->getMaxUploadSize(),
        "members"      => $members
    );

}

foreach ($info['data'] as $i => $groupData)
{
    $gid = $groupData['gid'];


}

echo json_encode($info);