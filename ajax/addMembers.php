<?php
if (!isset($_REQUEST) || empty($_REQUEST))
{
    exit;
}

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');


if (!WebUser::getUser()->isProf())
{
    exit("<p class='text-danger text-center'>You do not have enough privileges to add members to a group</p>");
}


$uids = (empty($_REQUEST['uids']) || !isset($_REQUEST['uids']) ? array() : $_REQUEST['uids']); // array
$gid = $_REQUEST['gid'];
$sid = $_REQUEST['sid'];

$AddMembers = new AddMembers($gid);
$AddMembers->setUids($uids);
$AddMembers->setSemesterId($sid);


if ($AddMembers->add())
{
    ?>

    <script>
        $(function ()
        {
            groupMembers.ajax.reload(function (json)
            {
                selectedStudentsTableEditGroup.clear().draw();
                selectedEdit = [];
                studentsEdit = [];
            });

            // update table
            var gid = '<?php echo $gid; ?>';
            groups.rows().every(function (rowIdx, tableLoop, rowLoop)
            {
                var data = this.data();
                if (data.gid == gid)
                {

                    data.totalMembers++;
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
    $errors = $AddMembers->getErrors();
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