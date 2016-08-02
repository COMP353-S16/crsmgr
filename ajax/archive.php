<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');

if(!WebUser::getUser()->isSysAdmin())
{
    exit("<p class='text-danger text-center'>You do not have enough privileges to archive group files</p>");
}


$gid = $_REQUEST['gid'];

$Archive = new Archive(new Group($gid));

if ($Archive->archive())
{
    ?>

    <div id="responseMessage" class="alert alert-success">

        Archive Success!  <a href="<?php echo $Archive->getZipUrl(); ?>" class="alert-link">View Archive</a>
    </div>


    <script>
        $(function ()
        {
            $('#archiveAjaxResponse').dialog({
                title : 'Success!',

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
    $errors = $Archive->getErrors();
    ?>
    <script>
        $(function ()
        {
            $('#archiveAjaxResponse').dialog({
                title : 'Error',
                height : 300,
                buttons : {
                    "Close" : function ()
                    {
                        $(this).dialog("close");
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

