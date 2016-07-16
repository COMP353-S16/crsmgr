<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$cid = $_REQUEST['cid'];

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT d.did FROM Deliverables d, Courses c WHERE c.cid=:cid AND c.cid=d.cid");
$query->bindValue(":cid", $cid);
$query->execute();

$info =array("data" => array());

while($deliverable_data = $query->fetch()) {

    $deliverable = new Deliverable($deliverable_data['did']);

    $info['data'][] = array(
        "name" => $deliverable->getDName(),
        "datePosted" => $deliverable->getStartDate(),
        "dueDate" => $deliverable->getEndDate()

    );
}

echo json_encode($info);