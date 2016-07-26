<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
$data['data']['groups'] = array();

$groups = array(1,2,3);

$data['data']['groups'] = array();

foreach($groups as $id)
{
    $groups[] = new Group($id);
}

/**
 * @var $Group Group
 */

foreach($groups as $Group)
{


    $data['data']['groups'][] = array(
        "max" => 1,
        "gid" => 1,
        "users" => array()
    );


}


foreach($data['data']['groups'] as &$GroupData)
{


    $GroupData['files'] = array();

    $files = array(1,2,3);

    foreach($files as $file)
    {
        $GroupData['files'][] = array (
            "filename"
        );
    }


}
$a  =  &$data['groups']['users']['files']['versions'];

$a[0]['size'] = 0;

print_r($data['data']['groups']);