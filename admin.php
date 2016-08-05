<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
//check if user is logged in
WebUser::isLoggedIn(true);

if (WebUser::getUser()->isStudent())
{
    exit("Not an administrator");
}

$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT * FROM Semester");
$query->execute();
$semesters = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo CoreConfig::settings()['appname']; ?></title>

    <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">


    <!-- DataTables CSS -->
    <link href="bower_components/datatables/media/css/dataTables.bootstrap.min.css" rel="stylesheet">

    <!-- DataTables Buttons Extension -->
    <link href="bower_components/datatables/extensions/Buttons/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="bower_components/datatables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet">

    <!-- DataTable Select Extension -->
    <link href="bower_components/datatables/extensions/Select/css/select.dataTables.min.css" rel="stylesheet">
    <link href="bower_components/datatables/extensions/Select/css/select.bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- jQuery UI -->
    <link href="bower_components/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// --><!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script><![endif]-->


    <style>
        /*fixes modal window issue */
        .ui-widget-overlay {
            position: fixed;
            z-index: 10000
        }

        .ui-autocomplete-loading {
            background: url('images/ajax.gif') no-repeat right center
        }

        th.dt-center, td.dt-center { text-align: center; }

        .sk-cube-grid {
            width: 100px;
            height: 100px;
            margin: 160px auto;
        }

        .sk-cube-grid .sk-cube {
            width: 33%;
            height: 33%;
            background-color: #333;
            float: left;
            -webkit-animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out;
            animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out;
        }
        .sk-cube-grid .sk-cube1 {
            -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s; }
        .sk-cube-grid .sk-cube2 {
            -webkit-animation-delay: 0.3s;
            animation-delay: 0.3s; }
        .sk-cube-grid .sk-cube3 {
            -webkit-animation-delay: 0.4s;
            animation-delay: 0.4s; }
        .sk-cube-grid .sk-cube4 {
            -webkit-animation-delay: 0.1s;
            animation-delay: 0.1s; }
        .sk-cube-grid .sk-cube5 {
            -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s; }
        .sk-cube-grid .sk-cube6 {
            -webkit-animation-delay: 0.3s;
            animation-delay: 0.3s; }
        .sk-cube-grid .sk-cube7 {
            -webkit-animation-delay: 0s;
            animation-delay: 0s; }
        .sk-cube-grid .sk-cube8 {
            -webkit-animation-delay: 0.1s;
            animation-delay: 0.1s; }
        .sk-cube-grid .sk-cube9 {
            -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s; }

        @-webkit-keyframes sk-cubeGridScaleDelay {
            0%, 70%, 100% {
                -webkit-transform: scale3D(1, 1, 1);
                transform: scale3D(1, 1, 1);
            } 35% {
                  -webkit-transform: scale3D(0, 0, 1);
                  transform: scale3D(0, 0, 1);
              }
        }

        @keyframes sk-cubeGridScaleDelay {
            0%, 70%, 100% {
                -webkit-transform: scale3D(1, 1, 1);
                transform: scale3D(1, 1, 1);
            } 35% {
                  -webkit-transform: scale3D(0, 0, 1);
                  transform: scale3D(0, 0, 1);
              }
        }
    </style>

</head>

<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0">
        <?php
        require_once($_SERVER['DOCUMENT_ROOT'] . '/layout/header.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/layout/navbar-right.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/layout/navbar-side.php');
        ?>

    </nav>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <h1 class="page-header">Course Management</h1>
                    Access Type:
                    <?php

                    if (WebUser::getUser()->isProf())
                    {
                        echo "PROFESSOR";
                    }
                    else if (WebUser::getUser()->isSysAdmin())
                    {
                        echo "ADMINISTRATOR";
                    }
                    else if (WebUser::getUser()->isTa())
                    {
                        echo "TEACHER ASSISTANT";
                    }

                    ?>
                    <br>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#managegroups" data-toggle="tab">Manage Groups
                                <span class="glyphicon glyphicon-globe"></span></a></li>
                        <li><a href="#deliverablesManager" data-toggle="tab">Deliverables
                                <span class="glyphicon glyphicon-info-sign"></span></a></li>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="managegroups">
                            <h4>Groups</h4>

                            <table width="100%" border="0" class="table" id="groupstable">
                                <thead>
                                <tr>
                                    <th>GroupId</th>
                                    <th>GroupName</th>
                                    <th>LeaderName</th>
                                    <th>CreatorName</th>
                                    <th>Semester</th>
                                    <th>Members</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>


                            <button type="button" id="createGroupButton" class="btn btn-success">Create Group</button>


                        </div>
                        <div class="tab-pane fade" id="deliverablesManager">
                            <h4>Deliverables</h4>


                            <table id="deliverablesTable" class="table table-bordered" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Semester</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <button type="button" id="createNewDeliverable" class="btn btn-primary">New Deliverable
                            </button>
                        </div>


                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->


        <!-- MODAL WINDOWS -->


        <!-- delete confirmation -->
        <div id="deleteGroupModal" style="display:none">
            <p>
                <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>The group will be deleted. Are you sure?
            </p>
        </div>

        <!-- Promote User Response -->
        <div id="promoteUserAjax" style="display: none;"></div>

        <!-- Delete User Response -->
        <div id="deleteUserAjax" style="display: none;"></div>

        <!-- Delete Member confirmation -->
        <div id="deleteMemberModal" style="display:none">
            <p>
                <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span><span
                    id="deleteMemberConfirmationMessage"></span>
            </p>
        </div>

        <!-- Message during deliverable creation -->
        <div id="createDeliverableAjax" style="display: none;"></div>

        <!-- Message during group creation -->
        <div id="createGroupAjax" style="display: none;"></div>

        <!-- CREATE DELIVERABLE WINDOW -->
        <div id="newDeliverableModal" style="display: none;">
            <form role="form" id="newDeliverableForm">
                <div class="form-group">


                    <table class="table-bordered table">
                        <tr>
                            <td>Deliverable name:</td>
                            <td>
                                <div class="form-group has-feedback">
                                    <input placeholder="Deliverable name" name="newDeliverableName"
                                        id="newDeliverableName" class="form-control"> <span class="help-block"></span>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>Start date:</td>
                            <td>
                                <div class="form-group has-feedback">
                                    <input placeholder="Start date" id="newDeliverableStartDate"
                                        name="newDeliverableStartDate" class="form-control">
                                    <span class="help-block"></span>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>End date:</td>
                            <td>
                                <div class="form-group has-feedback">
                                    <input placeholder="End date" id="newDeliverableEndDate"
                                        name="newDeliverableEndDate" class="form-control">
                                    <span class="help-block"></span></div>
                            </td>
                        </tr>
                        <tr>
                            <td>Semester:</td>
                            <td>
                                <div class="form-group has-feedback">
                                    <select class="form-control" id="selectSemesterNewDeliverable"
                                        name="selectSemesterNewDeliverable">
                                        <option value="">--Select--</option>
                                        <?php

                                        foreach ($semesters as $sec)
                                        {
                                            ?>
                                            <option value="<?php echo $sec['sid']; ?>">
                                                Semester <?php echo $sec['sid'] . ' (' . $sec['startDate'] . ') - (' . $sec['endDate'] . ')'; ?></option>
                                            <?php
                                        }
                                        ?>

                                    </select>


                                    <span class="help-block"></span>
                                </div>


                            </td>
                        </tr>
                        <tr>
                            <td>Assign to:</td>
                            <td><select class="form-control" id="selectGroupsNewDeliverable" multiple>

                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <input type="submit" hidden id="createDeliverable">

            </form>

        </div>

        <!-- CREATE GROUP MODAL WINDOW -->
        <div id="createGroupModal" style="display: none">
            <form role="form" id="createGroupForm">
                <div class="form-group">


                    <table class="table table-bordered">
                        <tr>
                            <td>Group Name:</td>
                            <td>
                                <div class="form-group has-feedback">
                                    <input id="newGroupName" name="newGroupName" class="form-control" placeholder="Enter group name">
                                    <span class="help-block"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Maximum Bandwidth:</td>
                            <td>
                                <div class="form-group has-feedback">
                                    <input id="groupBandwidth" name="groupBandwidth" class="form-control" placeholder="Enter group file bandwidth (MB)"> <span class="help-block"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Select Section:</td>
                            <td>

                                <div class="form-group">
                                <select class="form-control" id="sectionSelect" name="sectionSelect">
                                    <option value="all">All sections</option>
                                    <?php
                                    $pdo = Registry::getConnection();
                                    $query = $pdo->prepare("SELECT DISTINCT sectionName FROM StudentSemester");
                                    $query->execute();
                                    while ($sec = $query->fetch())
                                    {


                                        $sName = $sec['sectionName'];
                                        ?>
                                        <option value="<?php echo $sName; ?>"><?php echo $sName; ?></option>
                                        <?php
                                    }
                                    ?>

                                </select>
                                    </div>


                            </td>
                        </tr>
                        <tr>
                            <td>Select Semester:</td>
                            <td>
                                <div class="form-group has-feedback">
                                    <select class="form-control required" id="semesterSelect" name="semesterSelect">
                                        <option value="">--Select--</option>
                                        <?php
                                        foreach ($semesters as $sec)
                                        {
                                            ?>
                                            <option value="<?php echo $sec['sid']; ?>">
                                                Semester <?php echo $sec['sid'] . ' (' . $sec['startDate'] . ') - (' . $sec['endDate'] . ')'; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select> <span class="help-block"></span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>Student Name:</td>
                            <td>

                                <input id="studentName" class="form-control" placeholder="Enter student name" disabled>

                            </td>
                        </tr>
                    </table>

                    <table class="table table-bordered" width="100%">
                        <tr>
                            <th>Members</th>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" class="table table-bordered" id="selectedStudentsTable">


                                    <thead>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </td>
                        </tr>


                    </table>

                </div>
                <input type="submit" hidden id="hiddenSubmit">
            </form>
        </div>


        <!-- EDIT GROUP WINDOW -->

        <div id="editGroupModal" style="display: none;">

            <ul class="nav nav-tabs">
                <li class="active"><a href="#editGroupGeneral" data-toggle="tab">General <span class="glyphicon glyphicon-star"></span></a></li>
                <li><a href="#editGroupMembers" data-toggle="tab">Members <span class="glyphicon glyphicon-user"></span></a></li>
                <li><a href="#editGroupDeliverables" data-toggle="tab">Deliverables <span class="glyphicon glyphicon-info-sign"></span></a></li>
                <li><a href="#editGroupFiles" data-toggle="tab">Files <span class="glyphicon glyphicon-th-list"></span></a></li>
                <li><a href="#editGroupStats" data-toggle="tab">Statistics <span class="glyphicon glyphicon-stats"></span></a></li>
            </ul>
            <div class="tab-content">

                <div class="tab-pane fade in active" id="editGroupGeneral">
                    <form role="form" id="editGroupGeneralForm">
                        <div class="form-group">
                            <table class="table table-bordered">
                                <br>
                                <tr>
                                    <th colspan="2">General</th>
                                </tr>
                                <tr>
                                    <td>Group Name:</td>
                                    <td>
                                        <div class="form-group has-feedback">
                                            <input id="groupNameEdit" name="groupNameEdit" class="form-control"
                                                placeholder="Enter group name"> <span class="help-block"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Maximum Bandwidth:</td>
                                    <td>
                                        <div class="form-group has-feedback">
                                            <input id="groupBandwidthEdit" name="groupBandwidthEdit"
                                                class="form-control" placeholder="Enter group file bandwidth (MB)">
                                            <span class="help-block"></span>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <button type="submit" id="saveGeneralInformation"
                                class="btn btn-outline btn-primary btn-lg btn-block">Save
                            </button>

                            <!-- This is used to hold group id -->
                            <input type="hidden" id="editGroupGid" value="">
                            <input type="hidden" id="editGroupSid" value="">
                            <!-- hidden group id: DO NOT REMOVE -->

                            <div id="editGroupGeneralAjax" style="display: none;"></div>

                        </div>
                    </form>

                </div>

                <div class="tab-pane fade" id="editGroupMembers">
                    <h4>Members</h4>

                    <table width="100%" class="table table-bordered" id="groupMembers" name="groupMembers">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Section</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <hr>
                    <!-- Add new members to group -->
                    <h4>New Members</h4>
                    <table class="table table-bordered">
                        <tr>
                            <td>Select Section:</td>
                            <td>

                                <select class="form-control" id="sectionSelectEdit">
                                    <option value="all">All sections</option>
                                    <?php
                                    $pdo = Registry::getConnection();
                                    $query1 = $pdo->prepare("SELECT DISTINCT sectionName FROM StudentSemester");
                                    $query1->execute();
                                    while ($sec = $query1->fetch())
                                    {
                                        $sName = $sec['sectionName'];
                                        ?>
                                        <option value="<?php echo $sName; ?>"><?php echo $sName; ?></option>
                                        <?php
                                    }
                                    ?>

                                </select>

                            </td>
                        </tr>
                        <tr>
                            <td>Student Name:
                                <button id="studentPool" class="btn btn-outline btn-primary btn-square btn-sm">Pool
                                    <i class="fa fa-user"></i></button>
                            </td>
                            <td>
                                <input
                                    id="studentNameEdit" class="form-control" placeholder="Enter student name">

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="text-warning text-right">
                                    <i>*Only students from the same semester as group will be filtered.</i></p></td>
                        </tr>
                    </table>
                    <table width="100%" class="table table-bordered" id="selectedStudentsTableEditGroup"
                        name="selectedStudentsTableEditGroup">
                        <thead>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <button type="button"
                        id="addMembers"
                        class="btn btn-outline btn-primary btn-lg btn-block">
                        Add Members
                    </button>

                    <br>
                    <div id="addMembersAjax"></div>
                    <!-- /Add new members to group -->
                </div>

                <div class="tab-pane fade" id="editGroupDeliverables">
                    <h4>Assigned Deliverables</h4>

                    <table width="100%" border="0" class="table table-bordered table-hover" id="groupDeliverables">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date Posted</th>
                            <th>Due Date</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <hr>
                    <h4>Unassigned Deliverables</h4>
                    <table width="100%" border="0" class="table table-bordered table-hover"
                        id="unassignedGroupDeliverables">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date Posted</th>
                            <th>Due Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <button type="button"
                        id="assignDeliverables"
                        class="btn btn-outline btn-primary btn-lg btn-block">
                        Assign
                    </button>

                    <br>
                    <div id="assignDeliverablesAjax"></div>

                </div>

                <div class="tab-pane fade" id="editGroupFiles">
                    <h4>Files</h4>









                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#allFilesCollapse">All Files</a>
                                </h4>
                            </div>
                            <div id="allFilesCollapse" class="panel-collapse collapse in">
                                <div class="panel-body">








                                    <table width="100%" border="0" class="table table-bordered table-hover" id="groupFiles">
                                        <thead>

                                        <tr>

                                            <th>File ID</th>
                                            <th>Deliverable Name</th>
                                            <th>File Name</th>
                                            <th>Latest Revision</th>
                                            <th>Revisions</th>
                                            <th>Size</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>








                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#deletedFilesCollapse">Deleted Files</a>
                                </h4>
                            </div>
                            <div id="deletedFilesCollapse" class="panel-collapse collapse">
                                <div class="panel-body">


                                    <table width="100%" border="0" class="table table-bordered table-hover" id="deletedFilesTable">
                                        <thead>
                                        <tr>

                                            <th>File ID</th>
                                            <th>Deliverable Name</th>
                                            <th>File Name</th>
                                            <th>Revisions</th>
                                            <th>Size</th>
                                            <th>Deleted by</th>
                                            <th>Deleted on</th>
                                            <th>Expires</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>



                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#pDeletedFiles">Permanently Deleted Files</a>
                                </h4>
                            </div>
                            <div id="pDeletedFiles" class="panel-collapse collapse">
                                <div class="panel-body">

                                    <table width="100%" border="0" class="table table-bordered table-hover" id="pdeletedFilesTable">
                                        <thead>
                                        <tr>

                                            <th>File ID</th>
                                            <th>Deliverable Name</th>
                                            <th>File Name</th>
                                            <th>Revisions</th>
                                            <th>Size</th>
                                            <th>Deleted by</th>
                                            <th>Deleted on</th>
                                            <th>Expires</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>









                </div>

                <div class="tab-pan fade" id="editGroupStats">
                    <h4>Statistics</h4>

                    <button type="button" id="retrieveStats" class="btn btn-primary btn-lg btn-block">Retrieve</button>
                    <br>

                    <p id="loaderStats" class="text-center" >
                    <div class="sk-cube-grid" style="display: none;">
                        <div class="sk-cube sk-cube1"></div>
                        <div class="sk-cube sk-cube2"></div>
                        <div class="sk-cube sk-cube3"></div>
                        <div class="sk-cube sk-cube4"></div>
                        <div class="sk-cube sk-cube5"></div>
                        <div class="sk-cube sk-cube6"></div>
                        <div class="sk-cube sk-cube7"></div>
                        <div class="sk-cube sk-cube8"></div>
                        <div class="sk-cube sk-cube9"></div>
                    </div>
                    </p>
                    <div id="groupCharts">
                    </div>
                </div>
            </div>

        </div>

        <!-- Delete Group Deliverable Confirmation -->
        <div id="deleteGroupDeliverableContainer" style="display: none;">
            <p>
                <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>

                <span id="deleteGroupDeliverableConfirmation"></span>
            </p>
        </div>


        <!-- Delete Deliverable Confirmation -->
        <div id="deleteDeliverableContainer" style="display: none;">
            <p>
                <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>

                <span id="deleteDeliverableConfirmation"></span>
            </p>
        </div>




        <!-- Delete Group Deliverable Ajax Response -->
        <div id="deleteGroupDeliverableAjaxResponse" style="display: none;"></div>


        <!-- Delete Deliverable Ajax Response -->
        <div id="deleteDeliverableAjaxResponse" style="display: none;"></div>


        <!-- STUDENT POOL -->

        <div id="studentPoolContainer" style="display: none;"></div>


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

    <!-- DataTables JavaScript -->
    <script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables/media/js/dataTables.bootstrap.min.js"></script>
    <!-- DataTable extensions -->
    <script src="bower_components/datatables/extensions/Buttons/js/dataTables.buttons.js"></script>
    <script src="bower_components/datatables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
    <script src="bower_components/datatables/extensions/Select/js/dataTables.select.min.js"></script>
    <script src="bower_components/datatables/extensions/Buttons/js/buttons.flash.js"></script>
    <!-- jQuery UI -->
    <script src="bower_components/jquery-ui/jquery-ui.min.js"></script>

    <!-- Validator -->
    <script src="bower_components/validator/dist/jquery.validate.min.js"></script>

    <!-- Highcharts -->
    <script src="bower_components/highcharts/js/highcharts.js"></script>


    <!-- CRS -->

    <script src="js/crs.js"></script>
    <script>
        $(function ()
        {

            _tables_ = {
                groups : null,
                selectedStudentsTable : null,
                deliverablesTable : null,
                selectedStudentsTableEditGroup : null
            }


            /* groups table */
            groups = $('#groupstable').DataTable({
                "processing" : true,
                "serverSide" : false,
                "displayLength" : 25,
                "ajax" : {
                    "url" : "ajax/groupsInfo.php",
                    "type" : "POST"
                },
                "columns" : [
                    {"data" : "gid"},
                    {"data" : "gName"},
                    {"data" : "leaderName"},
                    {"data" : "creatorId"},
                    {"data" : "sid"},
                    {"data" : "totalMembers"},
                    {
                        'render' : function (data, type, row)
                        {
                            var edit = '<button data-gname="' + row.gName + '" data-gid="' + row.gid + '" id="groupEdit" title="Edit group" type="button" class="btn btn-warning btn-square btn-sm"><i class="fa fa-pencil"></i></button>&nbsp';
                            var deleteB = '<button data-gid="' + row.gid + '" data-gname="' + row.gName + '"  id="groupDelete" title="Delete group" type="button" class="btn btn-danger btn-square btn-sm"><i class="fa fa-times"></i> </button>';
                            return edit + deleteB;
                        },
                        className : "dt-center"
                    }
                ],
                columnDefs : [{
                    orderable : false,
                    targets : [6]
                }],
            });

            /* To create a group */
            $(document).on('click', '#createGroupButton', function ()
            {
                $("#createGroupModal").dialog({
                    modal : true,
                    title : "Create Group",
                    show : "fade",
                    width : 800,
                    height : 700,
                    hide: 'fade',
                    resizable : false,
                    buttons : {
                        "Create" : function ()
                        {
                            // since the modal button is not a submit button and not attached to form, added a hidden submit button and triggered a click
                            $('#hiddenSubmit').trigger('click');

                        },
                        "Cancel" : function ()
                        {
                            $(this).dialog("close");

                        }
                    },
                    close : function ()
                    {
                        $(this).dialog("destroy");
                        $form = $('form#createGroupForm');
                        $form[0].reset();
                        $form.find('.form-group').each(function ()
                        {
                            $(this).removeClass('has-success has-error has-feedback');
                        });
                        $form.find('.help-block').each(function ()
                        {
                            $(this).remove();
                        });
                        $form.find('.form-control-feedback').each(function ()
                        {
                            $(this).remove();
                        });

                        // reset datatable
                        selectedStudentsTable.clear().draw();
                        students = [];
                        selected = [];

                        // disabled autocomplete for student
                        $('#studentName').prop("disabled", true);
                    }
                })
            });

            /* Cancel Delete Button */
            $(document).on('click', '#deleteCancelButton', function ()
            {
                $("#deleteGroupModal").dialog("destroy");
            });
            <!-- TODO: change this to grab entire row instead of individual vars -->
            $(document).on('click', '#groupDelete', function ()
            {
                var gid = $(this).data('gid');
                var gName = $(this).data('gname');

                $("#deleteGroupModal").dialog({
                    modal : true,
                    title : "Delete " + gName + "?",
                    show : "fade",
                    hide: 'fade',
                    "buttons" : {
                        "Delete Group" : function ()
                        {
                            $('#deleteGroupModal').dialog({buttons : {}}).html("Deleting group, lease wait...");

                            $.ajax({
                                url : 'ajax/groupDelete.php',
                                data : "gid=" + gid,
                                type : 'POST',
                                dataType : 'html',
                                success : function (data)
                                {
                                    $('#deleteGroupModal').html(data);
                                }
                            });
                        },
                        "Cancel" : function ()
                        {
                            $(this).dialog("close");
                        }
                    },
                    close : function (ev, ui)
                    {
                        $(this).dialog("destroy");
                    }

                });
            });

            // selected student ids
            selected = [];
            // selected students data. to be used in datatables
            students = [];
            $("input#studentName").autocomplete({
                appendTo : "#createGroupModal",
                minLength : 1,
                source : function (request, response)
                {
                    $.ajax({
                        url : "ajax/studentSearch.php",
                        dataType : "json",
                        data : {
                            studentName : request.term,
                            selectedStudents : selected,
                            section : $('#sectionSelect').val(),
                            semester : $('#semesterSelect').val()


                        },
                        success : function (data)
                        {
                            response($.map(data.data, function (item)
                            {

                                return {
                                    label : item.name,
                                    uid : item.uid,
                                    sName : item.sName,
                                    sid : item.sid
                                };


                            }));
                        }
                    });
                },
                search : function ()
                {
                    $(this).addClass('ui-autocomplete-loading');
                },
                open : function ()
                {
                    $(this).removeClass('ui-autocomplete-loading');
                },
                select : function (event, ui)
                {
                    // push id into selected ids array

                    if (jQuery.isEmptyObject(ui.item))
                    {
                        return false;
                    }

                    selected.push(ui.item.uid);
                    students.push(ui.item);
                    selectedStudentsTable.clear().draw();
                    selectedStudentsTable.rows.add(students); // Add new data
                    selectedStudentsTable.columns.adjust().draw(); // Redraw the DataTable
                    // clear student name input field
                    $("input#studentName").val("");
                    return false;
                }
            }).data("ui-autocomplete")._renderItem = function (ul, item)
            {
                if (!jQuery.isEmptyObject(item))
                {
                    return $("<li></li>").data("item.autocomplete", item).append("<a><strong>" + item.label + "</strong> in section <i>" + item.sName + "</i> -> Semester " + item.sid + "</a>").appendTo(ul);
                }
                else
                {
                    return $("<li></li>").data("item.autocomplete", item).append("<strong> No Results</strong>").appendTo(ul);
                }
            };


            /* when semester changes, delete students chosen */
            $(document).on('change', '#semesterSelect', function ()
            {
                selected = [];
                // selected students data. to be used in datatables
                students = [];

                selectedStudentsTable.clear().draw();

                if ($('#semesterSelect').val() != "")
                {
                    $('#studentName').prop("disabled", false);
                }
                else
                {
                    $('#studentName').prop("disabled", true);
                }

            });

            /* new group validator */
            $('form#createGroupForm').validate({
                rules : {
                    newGroupName : {
                        required : true
                    },
                    semesterSelect : {
                        required : true
                    },
                    sectionSelect :
                    {
                        required: false
                    },
                    groupBandwidth : {
                        required : true,
                        number : true,
                        min : 1,
                        max : 2048  //TODO These values should be given through some PHP setting
                    }
                },
                messages : {
                    newGroupName : {
                        required : "A group name is required"
                    },
                    semesterSelect : {
                        required : "A semester is required"
                    }
                },
                submitHandler : function (form)
                {
                    var serialized = $(form).serialize();

                    // grab all selected students
                    var added = [];
                    selectedStudentsTable.rows('.selected').every(function (index)
                    {
                        var student = this.data();
                        added.push(student.uid);
                    });

                    if (added.length == 0)
                    {
                        return false;
                    }
                    // send data to server to check for credentials
                    $('#createGroupAjax').html("Please wait while group is being created...").dialog({
                        title : 'Create Group',
                        modal : true,
                        width : 300,
                        height : 200,
                        show : 'fade',
                        hide: 'fade',
                        draggable : false,
                        resizable : false
                    });

                    $.ajax({
                        url : 'ajax/createGroup.php',
                        type : 'POST',
                        dataType : 'html',
                        data : {
                            form : serialized,
                            uids : added,
                            maxb : $('#groupBandwidth').val()
                        },
                        success : function (data)
                        {
                            $('#createGroupAjax').html(data);
                        }
                    });
                }
            });

            /* SELECTED STUDENTS TABLE */
            selectedStudentsTable = $('#selectedStudentsTable').DataTable({
                "displayLength" : 25,
                data : students,
                dom : 'Bfrtip',
                deferRender : true,
                select : {
                    style : "os",
                    selector : "td:not(:last-child)"
                },
                buttons : [
                    {
                        "extend" : "selectAll"
                    },
                    {
                        "extend" : "selectNone"
                    },
                    {
                        "text" : "Remove Student",
                        "action" : function ()
                        {
                            // get rows
                            var rows = selectedStudentsTable.rows('.selected');
                            // loop through every row
                            rows.every(function (rowIdx, tableLoop, rowLoop)
                            {
                                var data = this.data();
                                // delete this id from the "selected" students array.
                                var index = selected.indexOf(data.uid);
                                if (index > -1)
                                {
                                    selected.splice(index, 1);
                                }

                                // remove from students data. this rebuilds array without the user id that we're deleting
                                students = students.filter(function (re)
                                {
                                    return re.uid !== data.uid;
                                })

                            });
                            //redraw table
                            rows.remove().draw();

                        }
                    }
                ],
                columns : [
                    {
                        title : "Student ID",
                        "data" : "uid"
                    },
                    {
                        title : "Student Name",
                        "data" : "label"  // this is called label because that's what the autocomplete calls it. see above
                    },
                    {
                        title : "Section",
                        "data" : "sName"
                    },
                    {
                        title : "Leader",
                        'render' : function (data, type, row)
                        {
                            var check = false;
                            if (selectedStudentsTable.rows().count() <= 0)
                            {
                                check = true;
                            }
                            return '<input type="radio" id="groupLeader" name="groupLeader" value="' + row.uid + '" checked="' + check + '">';

                        }
                    },
                ],
                columnDefs : [{
                    orderable : false,
                    targets : [3]
                }],
            });


            /* adnmin deliverables table */
            deliverablesTable = $('#deliverablesTable').DataTable({
                "processing" : true,
                "serverSide" : false,
                "displayLength" : 25,
                "ajax" : {
                    "url" : "ajax/adminDeliverableList.php",
                    "type" : "POST",
                    "data" : {}
                },
                "columns" : [
                    {"data" : "did"},
                    {"data" : "name"},
                    {"data" : "sid"},
                    {"data" : "startDate"},
                    {"data" : "endDate"},
                    {
                        'render' : function (colValue, type, row)
                        {

                            return '<button id="deleteGeneralDeliverable" title="Delete deliverable" type="button" class="btn btn-danger btn-square btn-sm"><i class="fa fa-times"></i></button>&nbsp';


                        },
                        className : "dt-center"
                    }
                ],
                columnDefs : [{
                    orderable : false,
                    targets : [5]
                }],
            });


            /** new deliverable stuff */
            $(document).on('change', '#selectSemesterNewDeliverable', function ()
            {

                var sid = $(this).val();
                if (sid == "" || typeof sid === "undefined")
                {
                    return false;
                }

                $.ajax({
                    url : "ajax/groupsSemester.php",
                    type : "POST",
                    dataType : "json",
                    data : {
                        sid : $(this).val()
                    },
                    success : function (data)
                    {

                        $('#selectGroupsNewDeliverable').find('option')
                                                        .remove()
                                                        .end();

                        $.each(data, function (index, value)
                        {
                            $('#selectGroupsNewDeliverable')
                                .append($('<option/>', {
                                    value : value.gid,
                                    text : value.name
                                }).prop('selected', true));
                        });
                        // $('#selectGroupsNewDeliverable').prop('selected', true);
                    }
                });
            });

            /* new deliverable date pickers */
            $('#newDeliverableStartDate, #newDeliverableEndDate').datepicker({
                changeMonth : true,
                changeYear : true,
                showButtonPanel : true,
                dateFormat : "yy-mm-dd"
            });

            /* new deliverable validator */
            newDeliverableValidator = $('form#newDeliverableForm').validate({
                rules : {
                    newDeliverableName : {
                        required : true
                    },
                    newDeliverableStartDate : {
                        required : true,
                        date : true
                    },
                    newDeliverableEndDate : {
                        required : true,
                        date : true
                    },
                    selectSemesterNewDeliverable : {
                        required : true
                    }
                },
                messages : {
                    newDeliverableName : {
                        required : "A deliverable name is required"
                    },
                    selectSemesterNewDeliverable : {
                        required : "Please select a valid semester"
                    }
                },
                submitHandler : function (form)
                {
                    var ser = $('form#newDeliverableForm').serialize();
                    var gids = $('#selectGroupsNewDeliverable').val();

                    $('#createDeliverableAjax').html("Please wait while deliverable is being created").dialog({
                        title : 'New Deliverable',
                        modal : true,
                        width : 300,
                        show : 'fade',
                        hide: 'fade',
                        height : 200,
                        draggable : false,
                        resizable : false
                    });

                    $.ajax({
                        url : "ajax/createDeliverable.php",
                        type : "POST",
                        dataType : "html",
                        data : {
                            form : ser,
                            gids : gids

                        },
                        success : function (data)
                        {
                            $('#createDeliverableAjax').html(data);
                        }
                    });
                }
            });

            /* create new deliverable */
            $('#createNewDeliverable').click(function ()
            {
                $('#newDeliverableModal').dialog({
                    width : 650,
                    height : 550,
                    modal : true,
                    resizable : false,
                    show : 'fade',
                    hide: 'fade',
                    title : 'New Deliverable',
                    buttons : {
                        "Create" : function ()
                        {
                            $('#createDeliverable').trigger('click');
                        },
                        "Cancel" : function ()
                        {
                            $(this).dialog('close');
                        }


                    },
                    close : function ()
                    {
                        $(this).dialog("destroy");
                        $form = $('form#newDeliverableForm');
                        $form[0].reset();
                        $form.find('.form-group').each(function ()
                        {
                            $(this).removeClass('has-success has-error has-feedback');
                        });
                        $form.find('.help-block').each(function ()
                        {
                            $(this).remove();
                        });
                        $form.find('.form-control-feedback').each(function ()
                        {
                            $(this).remove();
                        });
                    }
                });
            });


            // table containing selected students. Group Edit
            // selected student ids
            selectedEdit = [];
            // selected students data. to be used in datatables
            studentsEdit = [];

            selectedStudentsTableEditGroup = $('#selectedStudentsTableEditGroup').DataTable({
                destroy : true,
                data : studentsEdit,
                dom : 'Bfrtip',
                select : {
                    style : "os"
                },
                buttons : [
                    {
                        "extend" : "selectAll"
                    },
                    {
                        "extend" : "selectNone"
                    },
                    {
                        "text" : "Remove Student",
                        "action" : function ()
                        {

                            // get rows
                            var rows = selectedStudentsTableEditGroup.rows('.selected');
                            // loop through every row
                            rows.every(function (rowIdx, tableLoop, rowLoop)
                            {
                                var data = this.data();
                                // delete this id from the "selected" students array.
                                var index = selectedEdit.indexOf(data.uid);
                                if (index > -1)
                                {
                                    selectedEdit.splice(index, 1);
                                }

                                // remove from students data. this rebuilds array without the user id that we're deleting
                                studentsEdit = studentsEdit.filter(function (re)
                                {
                                    return re.uid !== data.uid;
                                })

                            });
                            //redraw table
                            rows.remove().draw();

                        }
                    }

                ],
                columns : [
                    {
                        title : "Student ID",
                        "data" : "uid"
                    },
                    {
                        title : "Student Name",
                        "data" : "label",  // this is called label because that's what the autocomplete calls it. see above
                    },
                    {
                        title : "Section",
                        "data" : "sName",

                    }
                ]
            });


            /* edit group */
            $(document).on('click', '#groupEdit', function ()
            {

                var data = groups.row($(this).closest('tr')).data();
                var gid = data.gid;
                var sid = data.sid;

                // add values to inputs
                $('#groupNameEdit').val(data.gName);
                $('#groupBandwidthEdit').val(data.bandwidth);
                $('#editGroupGid').val(data.gid);
                $('#editGroupSid').val(data.sid);


                // INITIALIZE GROUP FILES
                groupFiles = $('#groupFiles').DataTable({
                    "processing" : true,
                    "serverSide" : false,
                    destroy : true,
                    "displayLength" : 10,
                    dom : 'Bfrtip',
                    select : {
                        style : "os",
                        selector : "td:not(:has(:button))" // a row can be selected that doesn't have a button on it
                    },
                    buttons : [

                        {
                            text : 'Refresh',
                            action : function (e, dt, node, config)
                            {
                                dt.ajax.reload();
                            }
                        }
                    ],
                    "ajax" : {
                        "url" : "ajax/groupFiles.php",
                        "type" : "POST",
                        "data" : {
                            "gid" : data.gid,
                        }
                    },
                    "columns" : [

                        {"data" : "fid"},
                        {"data" : "deliverable"},
                        {"data" : "filename"},
                        {"data" : "ldate"},
                        {"data" : "revisions"},
                        {"data" : "size"},
                        {
                            'render' : function (data, type, row)
                            {
                                return '<button title="Download '+ row.filename +'" id="downloadButton" name="downloadButton" type="button" class="btn btn-outline btn-primary btn-square btn-sm"> <i class="fa fa-download"></i></button>';

                            },
                            className : "dt-center"
                        }
                    ],
                    'order' : [[2, "asc"]],
                    "columnDefs" : [
                        {
                            orderable : false,
                            targets : [6]
                        }
                    ],
                    "rowCallback" : function (nRow, aData)
                    {
                        $(nRow).addClass('selectable');
                    }
                });

                // INITIALIZE GROUP MEMBERS
                groupMembers = $('#groupMembers').DataTable({
                    "processing" : true,
                    destroy : true,
                    "displayLength" : 10,
                    dom : 'Bfrtip',
                    buttons : [
                        {
                            text : 'Refresh',
                            action : function (e, dt, node, config)
                            {
                                dt.ajax.reload();
                            }
                        }
                    ],
                    "ajax" : {
                        "url" : "ajax/adminGroupMembers.php",
                        "type" : "POST",
                        "data" : {
                            "gid" : data.gid,
                            "sid" : data.sid
                        }
                    },
                    "columns" : [

                        {"data" : "uid"},
                        {
                            "data" : "name",
                            "render" : function (data, type, row)
                            {

                                if (row.isLeader)
                                {
                                    return "<strong><p class='text-primary'>" + data + "</p></strong>";
                                }
                                else
                                {
                                    return data;
                                }
                            }
                        },
                        {"data" : "section"},
                        {
                            'render' : function (colValue, type, row)
                            {

                                var promote = '';
                                var deleteB = '';
                                if (!row.isLeader)
                                {
                                    deleteB = '<button data-gid="' + data.gid + '" id="deleteMember" title="Delete member" type="button" class="btn btn-danger btn-square btn-sm"><i class="fa fa-times"></i></button>&nbsp';

                                    promote = '<button data-gid="' + data.gid + '" id="promoteMember" title="Promote member" type="button" class="btn btn-success btn-square btn-sm"><i class="fa fa-arrow-circle-up"></i></button>&nbsp';
                                }


                                return deleteB + promote;
                            },
                            className : "dt-center"
                        }
                    ],
                    'order' : [[1, "asc"]],
                    columnDefs : [{
                        orderable : false,
                        targets : [3]
                    }],
                });

                // INITIALIZE GROUP DELIVERABLES



                /* open the modal window */
                $('#editGroupModal').dialog({
                    width : 900,
                    height : 700,
                    modal : true,
                    title : "Group " + data.gName,
                    show : 'fade',
                    hide: 'fade',
                    resizable : false,
                    close : function ()
                    {
                        $form = $('form#editGroupGeneralForm');
                        $('#editGroupGeneralAjax').hide().html("");
                        $form[0].reset();

                        $form.find('.form-group').each(function ()
                        {
                            $(this).removeClass('has-success has-error has-feedback');
                        });
                        $form.find('.help-block').each(function ()
                        {
                            $(this).remove();
                        });
                        $form.find('.form-control-feedback').each(function ()
                        {
                            $(this).remove();
                        });
                    }
                });


            });


            /* SEPARATED FILE LOADERS FOR PERFORMANCE INCREASE
            * It wasn't necessary to load everything when the user opens up group folder
            * */
            _groupTabCounters =
            {
                _editGroupFilesCounts : 0,
                _editGroupDeliverables : 0
            }

            $(document).on('click', 'a[href="#editGroupFiles"]', function(){
                if(_groupTabCounters._editGroupFilesCounts==1)
                    return;
                var gid = $('#editGroupGid').val();
                var sid = $('#editGroupSid').val();
                /* deleted files table */
                deletedFilesTable = $('#deletedFilesTable').DataTable({
                    processing : true,
                    "serverSide" : false,
                    "destroy" : true,
                    buttons : [
                        {
                            "extend" : "selectAll"
                        },
                        {
                            "extend" : "selectNone"
                        },
                        {
                            text : 'Refresh',
                            action : function (e, dt, node, config)
                            {
                                dt.ajax.reload();
                            }
                        }

                    ],
                    "ajax" :
                    {
                        "url" : "ajax/deletedFilesList.php",
                        "type" : "POST",
                        "data" :
                        {
                            "gid" : gid
                        }
                    },
                    "columns" : [

                        {"data" : "fid"},
                        {"data" : "deliverable"},
                        {"data" : "filename"},
                        {"data" : "revisions"},
                        {"data" : "size"},
                        {"data" : "deleterName"},
                        {"data" : "dateDeleted"},
                        {"data" : "expires"}
                    ],
                    'order' : [[2, "asc"]]
                });

                /* permanently deleted files table */
                pdeletedFilesTable = $('#pdeletedFilesTable').DataTable({
                    processing : true,
                    "serverSide" : false,
                    "destroy" : true,
                    buttons : [
                        {
                            "extend" : "selectAll"
                        },
                        {
                            "extend" : "selectNone"
                        },
                        {
                            text : 'Refresh',
                            action : function (e, dt, node, config)
                            {
                                dt.ajax.reload();
                            }
                        }

                    ],
                    "ajax" :
                    {
                        "url" : "ajax/pdeletedFilesList.php",
                        "type" : "POST",
                        "data" :
                        {
                            "gid" : gid
                        }
                    },
                    "columns" : [

                        {"data" : "fid"},
                        {"data" : "deliverable"},
                        {"data" : "filename"},
                        {"data" : "revisions"},
                        {"data" : "size"},
                        {"data" : "deleterName"},
                        {"data" : "dateDeleted"},
                        {"data" : "expires"}
                    ],
                    'order' : [[2, "asc"]]
                });
                _groupTabCounters._editGroupFilesCounts++;
            });

            $(document).on('click', 'a[href="#editGroupDeliverables"]', function(){
                if(_groupTabCounters._editGroupDeliverables==1)
                    return;
                var gid = $('#editGroupGid').val();
                var sid = $('#editGroupSid').val();

                /* group deliverables table */
                groupDeliverables = $('#groupDeliverables').DataTable({
                    "processing" : true,
                    "pageLength" : 10,
                    destroy : true,
                    dom : 'Bfrtip',
                    buttons : [
                        {
                            text : 'Refresh',
                            action : function (e, dt, node, config)
                            {
                                dt.ajax.reload();
                            }
                        }
                    ],
                    "ajax" : {
                        "url" : "ajax/deliverablesInfo.php",
                        "type" : "POST",
                        "data" : {
                            "gid" : gid
                        }
                    },
                    "columns" : [
                        {"data" : "name"},
                        {"data" : "datePosted"},
                        {"data" : "dueDate"},
                        {
                            'render' : function (colValue, type, row)
                            {
                                var deleteB = '<button id="deleteDeliverable" title="Delete deliverable" type="button" class="btn btn-danger btn-square btn-sm"><i class="fa fa-times"></i></button>&nbsp';
                                return deleteB;
                            },
                            className : "dt-center"
                        }
                    ],
                    'order' : [[1, "asc"]],
                    columnDefs : [{
                        orderable : false,
                        targets : [3]
                    }],
                });

                /* unassigned group deliverables table */
                unassignedGroupDeliverables = $('#unassignedGroupDeliverables').DataTable({
                    "processing" : true,
                    destroy : true,
                    "pageLength" : 10,
                    select : {
                        style : "os"
                    },
                    dom : 'Bfrtip',
                    buttons : [
                        {
                            "extend" : "selectAll"
                        },
                        {
                            "extend" : "selectNone"
                        },
                        {

                            text : 'Refresh',
                            action : function (e, dt, node, config)
                            {
                                dt.ajax.reload();
                            }
                        }
                    ],
                    "ajax" : {
                        "url" : "ajax/unassignedGroupDeliverables.php",
                        "type" : "POST",
                        "data" : {
                            "gid" : gid,
                            "sid" : sid
                        }
                    },
                    "columns" : [
                        {"data" : "name"},
                        {"data" : "datePosted"},
                        {"data" : "dueDate"}
                    ]
                });

                _groupTabCounters._editGroupDeliverables++;
            });



            $("input#studentNameEdit").autocomplete({
                appendTo : "#editGroupModal",
                source : function (request, response)
                {
                    $.ajax({
                        url : "ajax/studentSearch.php",
                        dataType : "json",
                        data : {
                            studentName : request.term,
                            selectedStudents : selectedEdit,
                            section : $('#sectionSelectEdit').val(),
                            semester : $('#editGroupSid').val()
                        },
                        success : function (data)
                        {
                            response($.map(data.data, function (item)
                            {

                                return {
                                    label : item.name,
                                    uid : item.uid,
                                    sName : item.sName,
                                    sid : item.sid
                                };


                            }));
                        }
                    });
                },
                search : function ()
                {
                    $(this).addClass('ui-autocomplete-loading');
                },
                open : function ()
                {
                    $(this).removeClass('ui-autocomplete-loading');
                },
                minLength : 1,
                select : function (event, ui)
                {
                    // push id into selected ids array

                    if (jQuery.isEmptyObject(ui.item))
                    {
                        return false;
                    }

                    selectedEdit.push(ui.item.uid);
                    studentsEdit.push(ui.item);
                    selectedStudentsTableEditGroup.clear().draw();
                    selectedStudentsTableEditGroup.rows.add(studentsEdit); // Add new data
                    selectedStudentsTableEditGroup.columns.adjust().draw(); // Redraw the DataTable
                    // clear student name input field
                    $("input#studentNameEdit").val("");
                    return false;
                }
            }).data("ui-autocomplete")._renderItem = function (ul, item)
            {
                if (!jQuery.isEmptyObject(item))
                {
                    return $("<li></li>").data("item.autocomplete", item).append("<a><strong>" + item.label + "</strong> in section <i>" + item.sName + "</i> -> Semester " + item.sid + "</a>").appendTo(ul);
                }
                else
                {
                    return $("<li></li>").data("item.autocomplete", item).append("<strong> No Results</strong>").appendTo(ul);
                }
            };


            /* promote group member */

            $(document).on('click', '#promoteMember', function ()
            {
                var data = groupMembers.row($(this).closest('tr')).data();
                var gid = $(this).data('gid');
                $(this).closest('td').find("button").remove().end().html("Reassigning leadership...");
                $.post("ajax/promoteMember.php", {
                    gid : gid,
                    uid : data.uid
                }).done(function (data)
                {

                    $('#promoteUserAjax').html(data);
                });
            });

            /* delete group member */

            $(document).on('click', '#deleteMember', function ()
            {
                var data = groupMembers.row($(this).closest('tr')).data();
                var gid = $(this).data('gid');

                $('#deleteMemberConfirmationMessage').html("Are you sure you want to delete <strong>" + data.name + "</strong> from this group?");
                $('#deleteMemberModal').dialog({
                    modal : true,
                    show : 'fade',
                    hide: 'fade',
                    title : 'Delete ' + data.name,
                    buttons : {
                        "Delete" : function ()
                        {
                            $('#deleteMemberModal').dialog("close");
                            $('#deleteUserAjax').dialog({
                                title : 'Delete ' + data.name,
                                width : 300,
                                modal : true,
                                height : 200
                            }).html("Removing member...please wait...");

                            $(this).closest('td').find("button").remove().end().html("Removing...");
                            $.post("ajax/deleteMember.php", {
                                gid : gid,
                                uid : data.uid
                            }).done(function (data)
                            {
                                $('#deleteUserAjax').html(data);
                            });
                        },
                        "Cancel" : function ()
                        {
                            $(this).dialog("close");
                        }
                    },
                    close : function ()
                    {
                        $(this).dialog("destroy");
                    }
                });

            });


            /* save general group information */

            $('form#editGroupGeneralForm').validate({
                rules : {
                    groupNameEdit : {
                        required : true
                    },
                    groupBandwidthEdit : {
                        required : true,
                        number : true,
                        min : 1,
                        max : 2048
                    }
                },
                messages : {
                    groupNameEdit : {
                        required : "A group name is required"
                    }
                },
                submitHandler : function (form)
                {
                    $button = $('#saveGeneralInformation');
                    var buttonText = $button.text();
                    var serialized = $(form).serialize();
                    $('#editGroupGeneralAjax').html("");
                    $button.text("Saving...").prop("disabled", true);
                    $.ajax({
                        url : "ajax/editGroup.php",
                        type : "POST",
                        dataType : "html",
                        data : {
                            form : serialized,
                            gid : $('#editGroupGid').val()
                        },
                        success : function (data)
                        {
                            $button.text(buttonText).prop("disabled", false);
                            $('#editGroupGeneralAjax').fadeIn().html(data);

                        },
                        error : function()
                        {
                            $button.text(buttonText).prop("disabled", false);
                        }
                    });


                }
            });

            // ASSIGN DELIVERABLES

            $(document).on('click', '#assignDeliverables', function ()
            {
                $assignButton = $(this);
                var text = $assignButton.text();
                var added = [];
                var gid = $('#editGroupGid').val();
                var sid = $('#editGroupSid').val();
                unassignedGroupDeliverables.rows('.selected').every(function (index)
                {
                    var del = this.data();
                    added.push(del.did);
                });
                // if no deliverable selected
                if (added.length == 0)
                {
                    return false;
                }

                $assignButton.html("Assigning...").prop('disabled', true);

                $.ajax({
                    url : 'ajax/assignDeliverables.php',
                    data : {
                        dids : added,
                        gid : gid,
                        sid : sid
                    },
                    type : 'POST',
                    dataType : 'html',
                    success : function (data)
                    {
                        $('#assignDeliverablesAjax').html(data);

                    },
                    complete : function ()
                    {
                        $assignButton.html(text).prop('disabled', false);
                    }
                });
            });

            // ADD NEW MEMBERS
            $(document).on('click', '#addMembers', function ()
            {
                $addMembersButton = $(this);
                var text = $addMembersButton.text();
                var added = [];
                var gid = $('#editGroupGid').val();
                var sid = $('#editGroupSid').val();


                selectedStudentsTableEditGroup.rows('.selected').every(function (index)
                {
                    var student = this.data();
                    added.push(student.uid);
                });

                // if no one is selected
                if (added.length == 0)
                {
                    return false;
                }

                $addMembersButton.html("Adding...").prop('disabled', true);

                $.ajax({
                    url : 'ajax/addMembers.php',
                    data : {
                        uids : added,
                        gid : gid,
                        sid : sid

                    },
                    type : 'POST',
                    dataType : 'html',
                    success : function (data)
                    {
                        $('#addMembersAjax').html(data);

                    },
                    complete : function ()
                    {
                        $addMembersButton.html(text).prop('disabled', false);
                    }
                });

            });

            /* when clicking on student pool button */
            $(document).on('click', '#studentPool', function ()
            {
                var sid = $('#editGroupSid').val();
                $('#studentPoolContainer').dialog({
                    width : 400,
                    height : 400,
                    show : 'fade',
                    title : 'Student List - Semester ' + sid
                });
                $('#studentPoolContainer').html("Loading student list...")
                $.post("ajax/studentPool.php", {
                    sid : sid
                }).done(function (data)
                {
                    $('#studentPoolContainer').html(data);
                });


            });


            /* delete group deliverable */
            $(document).on('click', '#deleteDeliverable', function ()
            {
                var data = groupDeliverables.row($(this).closest('tr')).data();
                var gid = data.gid;
                var did = data.did;

                $('#deleteGroupDeliverableContainer').dialog({
                    modal : true,
                    width : 400,
                    height : 250,
                    show : 'fade',
                    resizable : false,
                    draggable : false,
                    title : 'Delete Deliverable',
                    buttons : {
                        "Delete" : function ()
                        {
                            $('#deleteGroupDeliverableContainer').dialog("close");
                            $('#deleteGroupDeliverableAjaxResponse').html("Please wait...").dialog({
                                modal : true,
                                width : 400,
                                height : 200,
                                show: 'fade',
                                hide: 'fade',
                                resizable : false,
                                draggable : false,
                                title : 'Delete Deliverable'
                            });

                            $.post("ajax/deleteGroupDeliverable.php", {
                                did : did,
                                gid : gid
                            }).done(function (data)
                            {
                                $('#deleteGroupDeliverableAjaxResponse').html(data);
                            });

                        },
                        "Cancel" : function ()
                        {
                            $(this).dialog("close");
                        }
                    },
                    close : function ()
                    {
                        $(this).dialog("destroy");
                    }
                });

                var message = "Are you sure you want to delete deliverable <strong>" + data.name + "</strong>?<p></p>";
                message += "<p class='text-danger'>Please note that this action cannot be undone and all related deliverable files will be purged.</p>";
                $('#deleteGroupDeliverableConfirmation').html(message);

            });



            /* when clicking on a file */
            $(document).on('click', '#downloadButton', function (e)
            {
                var fileData = groupFiles.row($(this).closest('tr')).data();

                e.preventDefault();

                window.location.href = "view.php?vid=" + fileData.vid;
            });


            /* when clicking on retrieve stats button */
            $(document).on('click', '#retrieveStats',function(){
                $button = $(this);
                var text = $button.text();
                $('#groupCharts').html('');
                $button.text("Loading data...").prop("disabled", true);
                $('.sk-cube-grid').fadeIn();
                $.ajax({
                    url: 'ajax/gStats.php',
                    cache: false,
                    success: function(data)
                    {
                        $('#groupCharts').html(data);
                        $button.text(text).prop("disabled", false);
                        $('.sk-cube-grid').hide();
                    },
                    error: function()
                    {
                        $button.text(text).prop("disabled", false);
                    }
                });
            });


            $(document).on('click', '#deleteGeneralDeliverable', function(){
                var data = deliverablesTable.row($(this).closest('tr')).data();
                console.log(data);

                var did = data.did;


                var message = "Are you sure you want to delete deliverable <strong>" + data.name + "</strong>?<p></p>";
                message += "<p class='text-danger'>Please note that this action cannot be undone and all related groups files for this deliverable will be permanently deleted.</p>";
                $('#deleteDeliverableConfirmation').html(message);

                $('#deleteDeliverableContainer').dialog({
                    modal : true,
                    width : 400,
                    height : 250,
                    show : 'fade',
                    hide: 'fade',
                    resizable : false,
                    draggable : false,
                    title : 'Delete Deliverable',
                    buttons : {
                        "Delete" : function ()
                        {
                            $('#deleteDeliverableContainer').dialog("close");
                            $('#deleteDeliverableAjaxResponse').html("Please wait...").dialog({
                                modal : true,
                                width : 400,
                                height : 200,
                                show: 'fade',
                                hide: 'fade',
                                resizable : false,
                                draggable : false,
                                title : 'Delete Deliverable'
                            });

                            $.post("ajax/deleteDeliverable.php", {
                                did : did
                            }).done(function (data)
                            {
                                $('#deleteDeliverableAjaxResponse').html(data);
                            });

                        },
                        "Cancel" : function ()
                        {
                            $(this).dialog("close");
                        }
                    },
                    close : function ()
                    {
                        $(this).dialog("destroy");
                    }
                });

            });


        });

    </script>
</body>
</html>
