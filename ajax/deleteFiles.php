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
else{
    print_r($DeleteFiles->getErrors());
}