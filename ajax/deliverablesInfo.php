<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$gid = $_REQUEST['gid'];

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT d.did FROM Deliverables d, GroupDeliverables gd, Groups g 
                        WHERE g.gid=:gid AND gd.gid = g.gid AND gd.did = d.did");
$query->bindValue(":gid", $gid);
$query->execute();

$info =array("data" => array());

while($deliverable_data = $query->fetch()) {

    $deliverable = new Deliverable($deliverable_data['did']);

    $info['data'][] = array(
        "name" => $deliverable->getDName(),
        "datePosted" => $deliverable->getStartDate(),
        "dueDate" => $deliverable->getEndDate(),
        "did" => $deliverable->getDid(),
        "gid" => (int)$gid,
        "sid" => $deliverable->getSemesterId()

    );
}

echo json_encode($info);