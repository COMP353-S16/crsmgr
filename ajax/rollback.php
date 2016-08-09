<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$fid = $_REQUEST['fid'];
$vid = $_REQUEST['vid'];

$rollback = new Rollback($fid, $vid);

$uid = WebUser::getUser()->getUid();

$Group = new Group($_REQUEST['gid']);

if(!$Group->isLeader($uid))
{
    $User = new User($Group->getLeaderId());
    ?>
    <p class="text-danger text-center">Only <?php echo $User->getFullName();?>, the leader of the group, may rollback files.</p>
    <?php

    exit;
}

if ($rollback->rollback())
{
    ?>
    <div class="alert alert-success">
        You have successfully rolled back to Version ID <strong><?php echo $vid; ?></strong>!
    </div>
    <script>

        $(function ()
        {
            versionsTable.ajax.reload();
            groupFiles.ajax.reload();
            // $("#versionsModal").dialog("destroy");
            $('#rollbackResponse').dialog({
                modal : true,
                title : "Success!",
                resizable : false,
                draggable : false,
                width : 400,
                height : 150

            });
        });
    </script>
    <?php
}
else
{
    ?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        An error has occurred!
    </div>
    <script>

    </script>
    <?php
}
