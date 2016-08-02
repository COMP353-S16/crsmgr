<?php
session_start();
if(isset($_REQUEST['fid']) && !empty($_SESSION))
{


    require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

    $pdo = Registry::getConnection();
    $query = $pdo->prepare("SELECT * FROM Files WHERE fid=:fid LIMIT 1");
    $query->execute(array(":fid" => $_REQUEST['fid']));

    if($query->rowCount()<=0)
    {
        exit("File not found");

    }
    $data = $query->fetch();
    $Files = new Files($data);
    $file = $Files->getLatestVersion()->getBaseUrl();
    $Download = new DownloadFile($Files->getLatestVersion(), WebUser::getUser());

    if($Download->download())
    {

        if(!CoreConfig::settings()['uploads']['storageDB'])
        {

            $fr = fopen($file, 'a+');
            $filedata = fread($fr, filesize($file));

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
        }
        else
        {
            $length = strlen($Files->getLatestVersion()->getData());
            //header('Content-Type: application/octet-stream');
            //header("Content-Transfer-Encoding: Binary");
            header("Content-Length: ".$length);
            header("Content-disposition: attachment; filename=\"" . basename($file) . "\"");
            echo $Files->getLatestVersion()->getData();
        }
    }
    exit();
}
else
{
    exit();
}