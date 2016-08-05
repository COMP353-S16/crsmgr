<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');


if (!WebUser::getUser()->isProf())
{
    exit("<p class='text-danger text-center'>You do not have enough privileges to create deliverables</p>");
}


$formData = array();
parse_str($_REQUEST['form'], $formData);

$gids = (!empty($_REQUEST['gids']) ? $_REQUEST['gids'] : array());


$CreateDeliverable = new CreateDeliverable();

$CreateDeliverable->setStartDate($formData['newDeliverableStartDate']);
$CreateDeliverable->setEndDate($formData['newDeliverableEndDate']);
$CreateDeliverable->setSemester($formData['selectSemesterNewDeliverable']);
$CreateDeliverable->setName($formData['newDeliverableName']);
$CreateDeliverable->setGroupIds($gids);

if ($CreateDeliverable->create())
{
    ?>
    <div id="responseMessageCreate" class="alert alert-success">

        You have successfully created deliverable <strong><?php echo $CreateDeliverable->getName(); ?></strong>!
    </div>
    <script>
        $(function ()
        {

            deliverablesTable.ajax.reload();

            $('#createDeliverableAjax').dialog({
                buttons : {
                    "Close" : function ()
                    {
                        $(this).dialog("destroy");

                    }
                }
            });
            // reset new deliverable form
            $('form#newDeliverableForm')[0].reset();

            // close modal
            $('#newDeliverableModal').dialog("close");
        });
    </script>
    <?php
}
else
{
    $errors = $CreateDeliverable->getErrors();
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