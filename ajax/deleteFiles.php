<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$fids = $_REQUEST["fids"];

$DeleteFiles = new DeleteFiles($_SESSION['uid'], $fids);



if($DeleteFiles->delete())
{?>



    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        You have successfully deleted <strong><?php echo count($fids); ?></strong> files!
    </div>


    <script>
        $(function(){

            // refresh both tables
            groupFiles.ajax.reload();
            deletedFilesTable.ajax.reload();
            // add the close button
            $('#deleteProgress').dialog({
                title : "Success!",
                buttons : {
                    "Close" : function()
                    {
                        $(this).dialog("destroy");
                    }
                }
            });

        });
    </script>
<?php

}
else
{
    $errors = $DeleteFiles->getErrors();;
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
