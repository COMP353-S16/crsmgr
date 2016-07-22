<?php
/**
 * Created by PhpStorm.
 * User: fatin
 * Date: 2016-07-18
 * Time: 7:25 PM
 */

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

$gid = $_POST["gid"];

$groupDelete = new DeleteGroup($gid);

if($groupDelete->deleteGroup())
{?>



    <div id="responseMessageDelete" class="alert alert-success alert-dismissable" style="display: none;">
        You have successfully deleted the group!
    </div>


    <script>
        $(function(){

            $('#responseMessageDelete').fadeIn();

            // refresh table
            groups.ajax.reload(function(json){

                // add the close button
                $('#deleteGroupModal').dialog({
                    title: "Delete Successful",
                    buttons : {
                        "Close" : function()
                        {
                            $(this).dialog("destroy");
                        }
                    }
                });

            },false);


        });
    </script>
    <?php

}
else
{
    $errors = $groupDelete->getErrors();;
    ?>
    <script>
        $(function(){

            $('#deleteGroupModal').dialog({
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

