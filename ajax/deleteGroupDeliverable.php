<?php
if(!isset($_REQUEST))
    exit;

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');


if(!WebUser::getUser()->isProf())
{
    exit("<p class='text-danger text-center'>You do not have enough privileges to create a group</p>");
}



$gid = $_REQUEST['gid'];
$did = $_REQUEST['did'];

$DeleteGroupDeliverable = new DeleteGroupDeliverable($gid);
$DeleteGroupDeliverable->addDid($did);


if($DeleteGroupDeliverable->delete())
{
    $dids = $DeleteGroupDeliverable->getDids();

    $dels = "<ul>";

    foreach($dids as $did)
    {
        $Deliverable = new Deliverable($did);
        $dels .= "<li>" . $Deliverable->getDName() . "</li>";
    }
    $dels .= "</ul>";
    ?>

    <div class="alert alert-success">
        You have successfully deleted the following deliverable(s)!
        <p>
            <strong><?php echo $dels; ?></strong>
        </p>
    </div>

    <script>
        $(function(){
            groupDeliverables.ajax.reload();
            unassignedGroupDeliverables.ajax.reload();
            $('#deleteGroupDeliverableAjaxResponse').dialog({
                height: 350,
                buttons :
                {
                    "Close" : function()
                    {
                        $(this).dialog("close");
                    }
                }
            });
        });
    </script>
    <?php

}
else
{
    $errors = $DeleteGroupDeliverable->getErrors();
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