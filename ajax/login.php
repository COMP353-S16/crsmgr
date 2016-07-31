<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
/**
 * Created by PhpStorm.
 * User: dimitri
 * Date: 2016-07-09
 * Time: 1:53 PM
 */

$Login = new Login($_POST);

if($Login->login())
{
    WebUser::setUser(new User($_SESSION['uid']));
    $student = WebUser::getUser()->isStudent();
    if($student)
    {
        ?>
        <script>window.location.replace("home.php");</script>

        <?php
    }
    else
    {
        ?>
        <script>window.location.replace("admin.php");</script>

        <?php
    }

}
else
{
    $errors = $Login->getErrors();
    ?>
    <script>
        $(function () {
            $('#form').reset();
        });
    </script>
    <br>
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
?>