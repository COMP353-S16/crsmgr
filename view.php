<?php



if(isset($_REQUEST['fid']))
{

    require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

    $Files = new Files($_REQUEST['fid']);

    $file = $Files->getBaseUrl();
    $fr = fopen($file, 'a+');
    $filedata = fread($fr, filesize($file));


   // echo $path;
    //exit;

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));

    ob_clean();
    flush();
    readfile($file);
    exit;




}