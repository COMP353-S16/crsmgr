<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$gid = $_REQUEST['gid'];
$sid = $_REQUEST['sid'];

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT d.did FROM Deliverables d WHERE d.did NOT IN (
  SELECT g.did FROM GroupDeliverables g WHERE g.gid = :gid
) AND d.sid = :sid") ;
$query->bindValue(":gid", $gid);
$query->bindValue(":sid", $sid);
$query->execute();

$info =array("data" => array());

while($deliverable_data = $query->fetch()) {

    $deliverable = new Deliverable($deliverable_data['did']);

    $info['data'][] = array(
        "did" => $deliverable->getDid(),
        "name" => $deliverable->getDName(),
        "datePosted" => $deliverable->getStartDate(),
        "dueDate" => $deliverable->getEndDate()

    );
}

echo json_encode($info);