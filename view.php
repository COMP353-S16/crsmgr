<?php
session_start();
if(isset($_REQUEST['vid']) && is_numeric($_REQUEST['vid']) && !empty($_SESSION))
{

    require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');


    if(WebUser::isLoggedIn() && WebUser::getUser()->isStudent() && isset($_REQUEST['gid']))
    {
        $Group = new Group($_REQUEST['gid']);
        if(!$Group->isInGroup(WebUser::getUser()->getUid()))
        {
            exit("You cannot view this file");
        }
    }


    $pdo = Registry::getConnection();
    $query = $pdo->prepare("SELECT f.* FROM Versions v, Files f WHERE v.vid=:vid AND f.fid=v.fid LIMIT 1");
    $query->execute(array(":vid" => $_REQUEST['vid']));


    if($query->rowCount()<=0)
    {
        exit("File not found");

    }

    $Files = new Files($query->fetch());
    /**
     * @var $Version Version
     *
     */
    $Version = $Files->getVersionById($_REQUEST['vid']);
    $file = $Version->getBaseUrl();
    $Download = new DownloadFile($Version, WebUser::getUser());

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