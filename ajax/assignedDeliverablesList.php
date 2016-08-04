<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

if (!isset($_REQUEST['gid']))
{
    exit("Error. No group id found.");
}


$gid = $_REQUEST['gid'];
$pdo = Registry::getConnection();

//TODO Need to correct this for current timezone since the server's timezone does not match!!!
$query = $pdo->prepare("SELECT d.did FROM Deliverables d, GroupDeliverables gd
                                WHERE gd.gid=:gid AND gd.did = d.did AND :d BETWEEN d.startDate AND d.endDate ");
$query->bindValue(":gid", $gid);
$query->bindValue(":d", date('Y-m-d H:i:s'));
$query->execute();

$deliv = array();

if ($query->rowCount() > 0)
{
    while ($del = $query->fetch())
    {
        $Deliverable = new Deliverable($del['did']);
        $deliv[] = array(
          "name" => $Deliverable->getDName(),
          "did" => $Deliverable->getDid()
        );
    }
}
echo json_encode($deliv);
?>
