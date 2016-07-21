<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$fids = $_REQUEST["fids"];

$DeleteFiles = new DeleteFiles($_SESSION['uid'], $fids);



if($DeleteFiles->delete())
{
    ?>



    <div id="responseMessage" class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        You have successfully deleted <strong><?php echo count($fids); ?></strong> files!
    </div>


    <script>
        $(function(){

            // refresh both tables



            groupFiles.ajax.reload();
            deletedFilesTable.ajax.reload(function(json){
                // add the close button
                $('#deleteProgress').dialog({
                    buttons : {
                        "Close" : function()
                        {
                            $(this).dialog("destroy");
                        }
                    }
                });

                loadFileSummary();

                $('#responseMessage').show();
            },false);


        });
    </script>
<?php

}
else
{
    $errors = $DeleteFiles->getErrors();;
    ?>
    <script>
        $(function(){

            $('#deleteProgress').dialog({
                buttons : {
                    "Close" : function()
                    {
                        $(this).dialog("destroy");
                    }
                }
            });

        });
    </script>

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
