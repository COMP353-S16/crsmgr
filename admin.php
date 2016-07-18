<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
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
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-md-8">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#managegroups" data-toggle="tab">Manage Groups</a></li>
                        <li><a href="#dostuff" data-toggle="tab">Do stuff</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="managegroups">
                            <h4>Groups</h4>
                            <table width="100%" border="0" class="table" id="groupstable">
                                <thead>
                                <tr>
                                    <th>GroupId</th>
                                    <th>GroupName</th>
                                    <th>SectionId</th>
                                    <th>LeaderName</th>
                                    <th>CreatorName</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                </tbody>
                            </table>
                            <ul>

                            </ul>

                            <button type="button" class="btn btn-success">Add new group</button>
                        </div>
                        <div class="tab-pane fade" id="dostuff">
                            <h4>Do stuff</h4>

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
    <script src="bower_components/datatables/media/js/dataTables.bootstrap.min.js"></script>
    <!-- DataTable extensions -->
    <script src="bower_components/datatables/extensions/Buttons/js/dataTables.buttons.js"></script>
    <script src="bower_components/datatables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
    <script src="bower_components/datatables/extensions/Select/js/dataTables.select.min.js"></script>
    <script src="bower_components/datatables/extensions/Buttons/js/buttons.flash.js"></script>

    <script>

        $(function () {

            groups = $('#groupstable').dataTable({
                "processing": true,
                "serverSide": false,
                "displayLength": 25,
                "ajax": {
                    "url": "ajax/groupsInfo.php",
                    "type": "POST",
                    "data": {}
                },
                "columns": [
                    {"data": "gid"},
                    {"data": "gName"},
                    {"data": "sid"},
                    {"data": "leaderId"},
                    {"data": "creatorId"},
                    {
                        'render': function (data, type, row) {
                            return '<button data-gid="' + row.gid + '" id="groupEdit" title="Edit group" type="button" class="btn btn-warning btn-circle"><i class="fa fa-list"></i></button>&nbsp' +
                                '<button title="Delete group" type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button>';
                        }
                    }
                ]
            });


            $(document).on('click', '#groupEdit', function(){
                var gid = $(this).data('gid');
                console.log(gid);
            });


        });


    </script>

</body>

</html>
