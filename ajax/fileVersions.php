<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
/**
 * Created by PhpStorm.
 * User: Dimitri
 * Date: 7/14/2016
 * Time: 7:49 PM
 */
$Files = new Files($_REQUEST['fid']);

$info =array("data" => array());

$versions = $Files->getVersions();

/**
 * @var $Version Version
 */
foreach($versions as $Version) {

    $info['data'][] = array(
        "vid" => $Version->getVersionId(),
        "user" => $Version->getUploaderId(),
        "date" => $Version->getUploadDate(),
        "size" => $Version->getSize(),
        "url" => $Version->getUrl()

    );
}

echo json_encode($info);