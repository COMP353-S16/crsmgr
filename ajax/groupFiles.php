<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 7/14/2016
 * Time: 7:49 PM
 */

$gid = 1;/// should be sent via ajax

$GroupFiles = new GroupFiles($gid);
$files = $GroupFiles->getFileIds();

$info =array("data" => array());


foreach($files as $i => $fid) {


    $Files = new Files($fid);

    $Deliverable = new Deliverable($Files->getDeliverableId());


    if(!$GroupFiles->isDeleted($fid))
    {
        $info['data'][] = array(
            "fid" => $Files->getId(),
            "filename" => $Files->getFileName() . '.' . $Files->getFileExtension(),
            "ldate" => $Files->getLatestVersion()->getUploadDate(),
            "deliverable" => $Deliverable->getDName(),
            "revisions" => $Files->getNumberOfRevisions(),
            "size" => round($Files->getSize(),2) . " KB",
            "isDeleted" => $GroupFiles->isDeleted($fid),
            "url" => $Files->getUrl()

        );
    }


}

echo json_encode($info);