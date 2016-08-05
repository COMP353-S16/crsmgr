<?php
if (!isset($_REQUEST) || empty($_REQUEST))
{
    exit;
}

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

if (!WebUser::getUser()->isProf())
{
    exit("<p class='text-danger text-center'>You do not have enough privileges to create assign deliverables to a group</p>");
}


$dids = $_REQUEST['dids'];
$gid = $_REQUEST['gid'];
$sid = $_REQUEST['sid'];

$AssignDeliverables = new AssignDeliverables($gid);
$AssignDeliverables->setDids($dids);

if ($AssignDeliverables->assign())
{
    ?>
    <script>
        $(function ()
        {

            //TODO Instead of refreshing tables, we should just add/remove data from tables directly instead doing another ajax request
            unassignedGroupDeliverables.ajax.reload();
            groupDeliverables.ajax.reload();
        });
    </script>
    <?php
}
else
{
    $errors = ($AssignDeliverables->getErrors());

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