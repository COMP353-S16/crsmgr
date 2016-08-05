<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$info = array("data" => array());

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT did FROM Deliverables");

$query->execute();

while ($data = $query->fetch())
{
    $Deliverable = new Deliverable($data['did']);
    $info['data'][] = array(
        "did"       => $data['did'],
        "name"      => $Deliverable->getDName(),
        "startDate" => $Deliverable->getStartDate(),
        "endDate"   => $Deliverable->getEndDate(),
        "sid"       => $Deliverable->getSemesterId()
    );
}

echo json_encode($info);