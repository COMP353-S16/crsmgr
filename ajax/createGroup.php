<?php
if(!isset($_REQUEST))
    exit;

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$formData = array();
parse_str($_REQUEST['form'], $formData);


$leader = $formData['groupLeader'];
$name = $formData['newGroupName'];
$uids = $_REQUEST['uids']; // array


$SEMESTER = 1;
$CreateGroup = new CreateGroup();
$CreateGroup->setGroupName($name);
$CreateGroup->setSemesterId($SEMESTER);
$CreateGroup->setUids($uids);
$CreateGroup->setLeaderId($leader);
$CreateGroup->setCreatorId($_SESSION['uid']);
$CreateGroup->setMaxBandwidth($_REQUEST['maxb']);

if($CreateGroup->create())
{ ?>
    <div id="responseMessageCreate" class="alert alert-success alert-dismissable">

        You have successfully created group <strong><?php echo $CreateGroup->getGroupName(); ?></strong>!
    </div>
    <script>
        $(function(){
            groups.ajax.reload(function(json){
                // callback

                $('#createGroupAjax').dialog({
                    buttons : {
                        "Close" : function()
                        {
                            $(this).dialog("destroy");
                        }
                    }
                });
            });
        });
    </script>
<?php
}
else
{
    $errors = $CreateGroup->getErrors();
    ?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php
        $msg .= "<ul>";
        foreach ($errors as $error)
        {
            $msg .= '<li>' . $error . '</li>';
        }
        $msg .= "</ul>";
        echo $msg;
        ?>
    </div>
    <?php
}