<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

if (!WebUser::getUser()->isProf())
{
    exit("<p class='text-danger text-center'>You do not have enough privileges to delete a deliverable</p>");
}
$DeleteDeliverable = new DeleteDeliverable();


$DeleteDeliverable->addDid($_REQUEST['did']);


if ($DeleteDeliverable->delete())
{
    ?>

    <div class="alert alert-success">
        Deliverable(s) successfully deleted!
    </div>

    <script>
        $(function ()
        {
            deliverablesTable.ajax.reload();

            $('#deleteDeliverableAjaxResponse').dialog({
                height : 200,
                buttons : {
                    "Close" : function ()
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
    $errors = $DeleteDeliverable->getErrors();
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