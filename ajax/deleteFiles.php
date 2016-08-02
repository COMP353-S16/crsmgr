<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$fids = $_REQUEST["fids"];

$DeleteFiles = new DeleteFiles($_SESSION['uid'], $fids);



if($DeleteFiles->delete())
{
    ?>



    <div id="responseMessage" class="alert alert-success">
        You have successfully deleted <strong><?php echo count($fids); ?></strong> files!
    </div>


    <script>
        $(function(){
            groupFiles.ajax.reload();
            deletedFilesTable.ajax.reload(function(json){
                // add the close button


                loadFileSummary();


            },false);


            $('#deleteProgress').dialog({
                buttons : {
                    "Close" : function()
                    {
                        $(this).dialog("destroy");
                    }
                }
            });

            $('#responseMessage').show();


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
