<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$gid = $_REQUEST['gid'];/// should be sent via ajax

$GroupFiles = new GroupFiles($gid);
$files = $GroupFiles->getFiles();

$info =array("data" => array());
/**
 * @var $Files Files
 */
foreach($files as $i => $Files)
{


    $Deliverable = new Deliverable($Files->getDeliverableId());


    if(!$GroupFiles->isDeleted($Files->getId()))
    {
        $info['data'][] = array(
            "fid" => $Files->getId(),
            "filename" => $Files->getFileName() . '.' . $Files->getFileExtension(),
            "ldate" => $Files->getLatestVersion()->getUploadDate(),
            "deliverable" => $Deliverable->getDName(),
            "revisions" => $Files->getNumberOfRevisions(),
            "size" => round($Files->getSize(),2) . " KB",
            "isDeleted" => $GroupFiles->isDeleted($Files->getId()),
            "url" => $Files->getUrl()

        );
    }


}

echo json_encode($info);