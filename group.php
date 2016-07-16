<?php
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/dbc.php');
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

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="bower_components/datatables-responsive/css/responsive.dataTables.scss" rel="stylesheet">

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
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
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
                    <?php
                    $cid = $_GET['cid'];
                    $Course = new Course($cid);
                    $User = new User($_SESSION['uid']);
                    $gid = $User->getGroupId($cid);
                    $Group = new Group($gid);
                    ?>
                    <h1 class="page-header"><?php echo 'Group ' .$Group->getGName() .' - ' .$Course->getCourseName()?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-md-8">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#members" data-toggle="tab">Members</a></li>
                        <li><a href="#deliverables" data-toggle="tab">Deliverables</a></li>
                        <li><a href="#files" data-toggle="tab">All Files</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="members">
                            <h4>Members</h4>
                            <table width="100%" border="0" class="table" id="memberstable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="deliverables">
                            <h4>Deliverables</h4>
                            <table width="100%" border="0" class="table" id="deliverablestable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Date Posted</th>
                                    <th>Due Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="files">
                            <h4>Files</h4>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Group Info
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li><?php echo 'Group id: ' .$Group->getGid() ?></li>
                                <li><?php echo 'Group name: ' .$Group->getGName()?></li>
                                <li><?php $group_leader = new User($Group->getLeaderId());
                                    echo 'Group leader: ' .$group_leader->getFirstName() .' ' .$group_leader->getLastName()?></li>
                            </ul>
                        </div>
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

<!-- DataTables JavaScript -->
<script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<script src="bower_components/datatables-plugins/ajaxreloader/fnReloadAjax.js"></script>

<script>

    $(function (){

        T = $('#memberstable').dataTable({
            "bProcessing": true,
            "bServerSide": false,
            "sAjaxSource": "ajax/membersInfo.php",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "url": sSource,
                    "data": "gid=" + <?php echo $Group->getGid(); ?>,
                    cache: false,
                    "success": fnCallback,
                });
            },
            "columns": [
                {"data": "name"},
                {"data": "username"},
                {"data": "email"}
            ],
            'aaSorting': [[0, "asc"]],
            'iDisplayLength': 25
        });



    });

    $(function (){

        T = $('#deliverablestable').dataTable({
            "bProcessing": true,
            "bServerSide": false,
            "sAjaxSource": "ajax/deliverablesInfo.php",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "url": sSource,
                    "data": "cid=" + <?php echo $_GET['cid']; ?>,
                    cache: false,
                    "success": fnCallback,
                });
            },
            "columns": [
                {"data": "name"},
                {"data": "datePosted"},
                {"data": "dueDate"}
            ],
            'aaSorting': [[0, "asc"]],
            'iDisplayLength': 25
        });



    });
</script>

</body>

</html>
