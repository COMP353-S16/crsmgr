<?php
if(!isset($_REQUEST) || empty($_REQUEST))
    exit;

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');


if(!WebUser::getUser()->isSysAdmin())
{
    exit("<p class='text-danger text-center'>You do not have enough privileges to create a new semester</p>");
}



$start = $_REQUEST['newSemesterStartDate'];
$end = $_REQUEST['newSemesterEndDate'];

$CreateSemester = new CreateSemester($start, $end);

if($CreateSemester->create())
{
    ?>
    <div id="responseMessage" class="alert alert-success">
        You have successfully created Semester <strong><?php echo $CreateSemester->getSemesterId(); ?></strong>.
    </div>
    <script>
        $(function(){
            semestersTable.ajax.reload();
        })
    </script>
    <?php
}
else
{


    $errors = ($CreateSemester->getErrors());
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
