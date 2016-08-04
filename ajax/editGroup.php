<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

if(!WebUser::getUser()->isProf())
{
    exit("<p class='text-danger text-center'>You do not have enough privileges to modify group information</p>");
}


$formData = array();
parse_str($_REQUEST['form'], $formData);

$EditGroup = new EditGroup($_REQUEST['gid']);
$EditGroup->setGroupName($formData['groupNameEdit']);
$EditGroup->setMaxBandwidth($formData['groupBandwidthEdit']);



if($EditGroup->edit())
{
    ?>
    <br>
    <div id="responseMessageCreate" class="alert alert-success">

        Saved!
    </div>
    <script>
        $(function(){

            var newName = '<?php echo $EditGroup->getGroupName(); ?>';

            $('#editGroupModal').dialog({
                title : newName
            });



            var gid = '<?php echo $_REQUEST['gid'];?>';
            groups.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                var data = this.data();

                if(data.gid == gid)
                {

                    data.gName = newName;
                    this.invalidate();

                }
            });

            groups.draw();
        });


    </script>
    <?php

}
else
{
    $errors = $EditGroup->getErrors();
    ?>
    <div class="alert alert-danger">
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