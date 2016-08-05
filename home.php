<?php
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/dbc.php');
//check if user is logged in
WebUser::isLoggedIn(true);


$Student = WebUser::getUser();
if(!WebUser::getUser()->isStudent())
{
    exit("You are not a student.");
}

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo CoreConfig::settings()['appname']; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <?php
            require_once ($_SERVER['DOCUMENT_ROOT'].'/layout/header.php');
            require_once ($_SERVER['DOCUMENT_ROOT'].'/layout/navbar-right.php');
            require_once ($_SERVER['DOCUMENT_ROOT'].'/layout/navbar-side.php');
            ?>

        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Home</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                <div class="row">
                    <div class="col-lg-7">
                        <div class="well well-sm">
                            <h3><?php echo 'Hello '.$Student->getFullName().',';?></h3>
                            <br>
                            <?php


                            $Semesters = new Semesters();

                            if(!$Semesters->exist())
                            {
                                ?>
                                <h5>There are no existing semesters.</h5>
                                <?php
                            }
                            else if($Student->isRegistered())
                            {
                                $sid = $Semesters->getSid();
                                // is he in a group in this semester?
                                if($Student->isInGroupFromSid($sid))
                                {
                                    $Student_Info = $Student->getStudentInfo();
                                    $gid = $Student->getGroupIdFromSid($sid);
                                    $group = new Group($gid);
                                    $isGroupClosed = $group->isGroupClosed();


                                    ?>
                                    <blockquote>
                                        <p>Email: <strong><?php echo $Student->getEmail() ?></strong></p>
                                        <p>Section:
                                            <strong><?php echo $Student->getSemesters()->getSectionName($sid); ?></strong>
                                        </p>
                                        <p>Course ends on:
                                            <strong><?php echo $group->getSemester()->getSemseterEndDate(); ?></strong></p>
                                        <p>Group ID: <strong><?php echo $group->getGid() ?></strong></p>
                                        <p>Group name: <strong><?php echo $group->getGName() ?></strong></p>
                                        <p>Number of files
                                            uploaded:
                                            <strong><?php echo $Student_Info->getNbOfFilesUploaded() ?></strong></p>
                                        <p>Number of files
                                            downloaded:
                                            <strong><?php echo $Student_Info->getNbOfFilesDownloaded() ?></strong>
                                        </p>
                                        <p>Last uploaded file:
                                            <strong><?php echo $Student_Info->getLastUploadedFile(); ?></strong></p>
                                        <p class="<?php echo ($isGroupClosed ? 'text-danger' : 'text-success' ); ?>">
                                            Status: <?php echo ($isGroupClosed ? "[CLOSED]" : "[OPEN]"); ?>
                                        </p>
                                    </blockquote>
                                    <div class="col-md-10"></div>
                                    <button type="button" id="view" class="btn btn-primary">View Group</button>
                                    <?php

                                }
                                else
                                {
                                    $Semesters = new Semesters();
                                    $Semester = $Semesters->getSemesterById($sid);

                                    ?>
                                    <h4>You are not part of any groups this semester</h4>
                                    <p class="text-primary">
                                        Current: Semester <strong><?php echo $Semester->getId();?></strong> <br>
                                        From: <strong><?php echo $Semester->getSemesterStartDate(); ?></strong> to <strong><?php echo $Semester->getSemseterEndDate();?></strong> <br>
                                    </p>
                                    <?php
                                }
                            }
                            else
                            { ?>
                                <h4>You are not yet registered.</h4>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

    <script>
        $(function(){

            $('#view').click(function(){
                window.location.replace("group.php");
            });
        });
    </script>
</body>

</html>
