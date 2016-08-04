<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$gid = $_REQUEST['gid'];
$info =array("data" => array());


$GroupFiles = new GroupFiles($gid);
$files = $GroupFiles->getFiles();


/**
 * @var $Files Files
 */
foreach($files as $i => $Files)
{

    $fid = $Files->getId();
    /**
     * @var $DeletedFiles DeletedFiles
     */
    $DeletedFiles = $GroupFiles->getDeletedFileById($fid);
    if($GroupFiles->isDeleted($fid) && !$DeletedFiles->isExpired())
    {
        $Deliverable = new Deliverable($Files->getDeliverableId());


        $info['data'][] = array(
            "fid" => $Files->getId(),
            "filename" => $Files->getFileName() . '.' . $Files->getFileExtension(),
            "deliverable" => $Deliverable->getDName(),
            "revisions" => $Files->getNumberOfRevisions(),
            "size" => round($Files->getSize(), 2) . " MB",
            "expires" => date_format(date_create($DeletedFiles->getExpiryDate()), 'M d, Y H:i:s')

        );
    }

}

echo json_encode($info);