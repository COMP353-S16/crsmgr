<?php
if (!isset($_REQUEST))
{
    exit;
}

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');


if (!WebUser::getUser()->isProf())
{
    exit("<p class='text-danger text-center'>You do not have enough privileges to create a group</p>");
}


$formData = array();
parse_str($_REQUEST['form'], $formData);


$leader = $formData['groupLeader'];
$name = $formData['newGroupName'];
$uids = $_REQUEST['uids']; // array


$SEMESTER = $formData['semesterSelect'];


$CreateGroup = new CreateGroup();
$CreateGroup->setGroupName($name);
$CreateGroup->setSemesterId($SEMESTER);
$CreateGroup->setUids($uids);
$CreateGroup->setLeaderId($leader);
$CreateGroup->setCreatorId($_SESSION['uid']);
$CreateGroup->setMaxBandwidth($_REQUEST['maxb']);

if ($CreateGroup->create())
{ ?>
    <div id="responseMessageCreate" class="alert alert-success alert-dismissable">

        You have successfully created group <strong><?php echo $CreateGroup->getGroupName(); ?></strong>!
    </div>
    <script>
        $(function ()
        {
            groups.ajax.reload(function (json)
            {
                // callback

                $('#createGroupAjax').dialog({
                    buttons : {
                        "Close" : function ()
                        {
                            $(this).dialog("destroy");

                        }
                    }
                });


                // close form dialog
                $("#createGroupModal").dialog("close");
            });

            // reset form


            //
            selectedStudentsTable.clear().draw();

            selected = [];

            students = [];

        });
    </script>
    <?php
}
else
{
    $errors = $CreateGroup->getErrors();
    ?>
    <div class="alert alert-danger alert-dismissable">
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