<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$fids = $_REQUEST["fids"];

$RecoverFiles = new RecoverFiles($fids);



if($RecoverFiles->recover())
{?>



    <div class="alert alert-success alert-dismissable">
        You have successfully recovered your files!
    </div>
    <script>
        $(function(){

            // refresh both tables
            groupFiles.ajax.reload();


            deletedFilesTable.ajax.reload();

            loadFileSummary();

            $('#recoverFilesContainer').dialog({
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
    $errors = $RecoverFiles->getErrors();
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
