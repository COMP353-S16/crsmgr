<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$formData = array();
parse_str($_REQUEST['form'], $formData);


$leader = $formData['groupLeader'];
$name = $formData['newGroupName'];
$uids = $_REQUEST['uids']; // array



$CreateGroup = new CreateGroup();
$CreateGroup->setGroupName($name);
$CreateGroup->setUids($uids);
$CreateGroup->setLeaderId($leader);
$CreateGroup->setCreatorId($_SESSION['uid']);

if($CreateGroup->create())
{ ?>
    <div id="responseMessage" class="alert alert-success alert-dismissable">

        You have successfully created group <strong><?php echo $CreateGroup->getGroupName(); ?></strong>!
    </div>
    <script>
        $(function(){
            groups.ajax.reload(function(json){
                // callback

                $('#createGroupModal').dialog('close');
            });
        });
    </script>
<?php
}
else
{
    $errors = $CreateGroup->getErrors();

    print_r($errors);
}