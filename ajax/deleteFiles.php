<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$fids = $_REQUEST["fids"];
print_r($fids);

$DeleteFiles = new DeleteFiles(1, $fids);



if($DeleteFiles->delete())
{?>
    
<?php
    echo "ok";
}
else{
    print_r($DeleteFiles->getErrors());
}