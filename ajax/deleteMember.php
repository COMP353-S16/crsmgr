<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

if (!WebUser::getUser()->isProf())
{
    exit("<p class='text-danger text-center'>You do not have enough privileges to delete a member from a group</p>");
}


$DeleteMember = new DeleteMember($_REQUEST['uid'], $_REQUEST['gid']);

if ($DeleteMember->delete())
{
    ?>

    <div class="alert alert-success">
        Group member deleted!
    </div>

    <script>
        $(function ()
        {
            groupMembers.ajax.reload();

            $('#deleteUserAjax').dialog({
                buttons : {
                    "Close" : function ()
                    {
                        $(this).dialog("close");
                    }
                }
            });
        });


        // update table
        var gid = '<?php echo $_REQUEST['gid']; ?>';
        groups.rows().every(function (rowIdx, tableLoop, rowLoop)
        {
            var data = this.data();
            if (data.gid == gid)
            {

                data.totalMembers--;
                this.invalidate();
            }
        });

        groups.draw();
    </script>
    <?php
}
else
{
    $errors = $DeleteMember->getErrors();
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