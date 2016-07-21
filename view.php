<?php



if(isset($_REQUEST['fid']))
{

    require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

    $pdo = Registry::getConnection();
    $query = $pdo->prepare("SELECT * FROM Files WHERE fid=:fid LIMIT 1");
    $query->execute(array(":fid" => $_REQUEST['fid']));
    $data = $query->fetch();

    $Files = new Files($data);

    $file = $Files->getBaseUrl();
    $fr = fopen($file, 'a+');
    $filedata = fread($fr, filesize($file));


   // echo $path;
    //exit;

    $mime = File::mime_content_type($Files->getFileExtension());
    echo $Files->getLatestVersion()->getData();

    header("Content-Type:" .$mime);





}