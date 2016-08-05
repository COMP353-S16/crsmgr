<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
//check if user is logged in
WebUser::isLoggedIn(true);

if (WebUser::getUser()->isStudent())
{
    exit("Not an administrator");
}

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

    <link rel="shortcut icon" type="image/png" href="images/favicon.png" />

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

                    <h1 class="page-header">System Administration</h1>

                    <br>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs">
                        <li><a href="#groups" data-toggle="tab">Groups
                                <span class="glyphicon glyphicon-globe"></span></a></li>
                        <li><a href="#semesterManage" data-toggle="tab">Semesters
                                <span class="glyphicon glyphicon-education"></span></a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="groups">
                            <h4>Groups</h4>

                            <table width="100%" border="0" class="table" id="groupsTable">
                                <thead>
                                <tr>
                                    <th>Group ID</th>
                                    <th>Group Name</th>
                                    <th>Leader Name</th>
                                    <th>Creator Name</th>
                                    <th>Semester</th>
                                    <th>Members</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="semesterManage">
                            <h4>Semesters</h4>


                            <div class="row">
                                <div class="col-lg-4">


                                    <form role="form" id="newSemesterForm">


                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                New Semester
                                            </div>
                                            <div class="panel-body">


                                                <table class="table" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <td>Start date:</td>
                                                        <td>
                                                            <div class="form-group has-feedback">
                                                                <input id="newSemesterStartDate" name="newSemesterStartDate"
                                                                    class="form-control"
                                                                    placeholder="Enter semester end date">
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>End date:</td>
                                                        <td>
                                                            <div class="form-group has-feedback">
                                                                <input id="newSemesterEndDate" name="newSemesterEndDate"
                                                                    class="form-control"
                                                                    placeholder="Enter semester end date">
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </thead>
                                                </table>

                                            </div>
                                            <div class="panel-footer">
                                                <button id="createSemester" type="submit"
                                                    class="btn btn-primary btn-lg btn-block">Create
                                                </button>
                                                <br>
                                                <div id="createSemesterAjaxResponse" style="display: none;"></div>
                                            </div>
                                        </div>


                                    </form>


                                </div>


                                <div class="col-lg-5">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            Semester History
                                        </div>
                                        <div class="panel-body">

                                            <table width="100%" class="table table-bordered" id="semestersTable">
                                                <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                </tr>

                                                </thead>
                                                <tbody></tbody>
                                            </table>


                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>

                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

        <!-- Archive Confirmation -->
        <div id="archiveConfirmModal" style="display: none;">
            <p>
                <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>

                Archiving takes some time when there are a lot of files. Once this process executes, it cannot be
                stopped until it is fully executed. Do you want to proceed?
            </p>

        </div>

        <div id="archiveAjaxResponse" style="display: none;">

        </div>

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


    <!-- CRS -->

    <script src="js/crs.js"></script>
    <script>

        $(function ()
        {
            $('#newSemesterForm').validate({
                rules : {
                    newSemesterStartDate : {
                        required : true,
                        date : true
                    },
                    newSemesterEndDate : {
                        required : true,
                        date : true
                    }
                },
                submitHandler : function (form)
                {
                    $button = $('#createSemester');
                    var buttonText = $button.text();
                    var serialized = $(form).serialize();
                    $button.text("Creating...").attr('disabled', true);
                    $.ajax({
                        url : "ajax/createSemester.php",
                        type : "POST",
                        dataType : "html",
                        data : serialized,
                        success : function (data)
                        {
                            $button.text(buttonText);
                            $('#createSemesterAjaxResponse').fadeIn().html(data);

                        },
                        complete : function ()
                        {
                            $button.attr('disabled', false);
                        }
                    });


                }
            });


            /* new deliverable date pickers */
            $('#newSemesterEndDate, #newSemesterStartDate').datepicker({
                changeMonth : true,
                changeYear : true,
                showButtonPanel : true,
                dateFormat : "yy-mm-dd"
            });


            semestersTable = $('#semestersTable').DataTable({
                "processing" : true,
                "serverSide" : false,
                "displayLength" : 25,

                "ajax" : {
                    "url" : "ajax/semestersList.php",
                    "type" : "POST"
                },
                "columns" : [
                    {"data" : "sid"},
                    {"data" : "startDate"},
                    {"data" : "endDate"}
                ]
            });


            /* groups table */
            groups = $('#groupsTable').DataTable({
                "processing" : true,
                "serverSide" : false,
                "displayLength" : 25,
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
                            var archive = '<button id="groupArchive" title="Archive group files" type="button" class="btn btn-success btn-outline"><i class="fa fa-save"> Archive</i></button>&nbsp';
                            return archive;
                        }
                    }
                ],
                columnDefs : [{
                    orderable : false,
                    targets : [6]
                }],
            });


            $(document).on('click', '#groupArchive', function ()
            {
                var group = groups.row($(this).closest('tr')).data();
                console.log(group);
                $('#archiveConfirmModal').dialog({
                    title : 'Archive',
                    width : 400,
                    height : 200,
                    modal : true,
                    draggable : false,
                    show : 'fade',
                    resizabled : false,
                    buttons : {
                        "Archive" : function ()
                        {
                            $(this).dialog("close");
                            $('#archiveAjaxResponse').html("Archiving...this might take a while...please wait.").dialog({
                                title : 'Archiving...',
                                width : 400,
                                height : 192,
                                modal : true,
                                draggable : false,
                                show : 'fade',
                                resizabled : false,
                            }).append('<span id="archiveTimer"></span>');

                            startTime = new Date();
                            setTimeout(stimer, 1000);

                            $.ajax({
                                url : 'ajax/archive.php',
                                data : "gid=" + group.gid,
                                type : 'POST',
                                dataType : 'html',
                                success : function (data)
                                {
                                    $('#archiveAjaxResponse').html(data);

                                },
                                error : function ()
                                {
                                    clearTimeout(stimer);
                                }
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

            function stimer()
            {
                // later record end time
                var endTime = new Date();

                // time difference in ms
                var timeDiff = endTime - startTime;

                // strip the miliseconds
                timeDiff /= 1000;

                // get seconds
                var seconds = padToFour(Math.round(timeDiff % 60));

                // remove seconds from the date
                timeDiff = Math.floor(timeDiff / 60);

                // get minutes
                var minutes = padToFour(Math.round(timeDiff % 60));

                // remove minutes from the date
                timeDiff = Math.floor(timeDiff / 60);

                // get hours
                var hours = Math.round(timeDiff % 24);

                // remove hours from the date
                timeDiff = Math.floor(timeDiff / 24);

                // the rest of timeDiff is number of days
                var days = timeDiff;


                $('#archiveTimer').html('<p class="' + (parseInt(minutes) <= 0 ? 'text-success' : 'text-danger' ) + '">Elapsed time: ' + hours + " hours " + minutes + " minutes " + seconds + " seconds" + "</p>");


                setTimeout(stimer, 1000);
            }

            function padToFour(number)
            {
                if (number <= 9999)
                {
                    number = ("0" + number).slice(-2);
                }
                return number;
            }
        })

    </script>
</body>
</html>
