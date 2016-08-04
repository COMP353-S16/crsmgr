<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
//check if user is logged in
WebUser::isLoggedIn(true);


$Student = WebUser::getUser();
if ($Student instanceof Student)
{
    $Semesters = new Semesters();
    $sid = $Semesters->getSid();


    $gid = null;
    if ($Student->getSemesters()->isRegisteredForSemester($sid) && $Student->isInGroupFromSid($sid))
    {
        $gid = $Student->getGroupIdFromSid($sid);
        $Group = new Group($gid);
    }
    else
    {
        header("location: home.php");
    }

    $isGroupClosed = $Group->isGroupClosed();
}
else
{
    exit ("Not a student");
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
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        /*fixes modal window issue */
        .ui-widget-overlay {
            position: fixed;
            z-index: 10000
        }

        .selectable {

            cursor: pointer;
        }

        th.dt-center, td.dt-center {
            text-align: center;
        }

        .statsLoader {
            background: url('images/ajax.gif');
        }
    </style>
</head>

<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
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
                    <h1 class="page-header">Group
                        <strong><?php echo $Group->getGName() ?><?php echo($isGroupClosed ? "<i>[CLOSED]</i>" : ""); ?></strong>
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <?php
            if ($isGroupClosed)
            {
                ?>
                <p class="text-center text-danger text-capitalize"> This group no longer has access to group files.</p>
                <?php
            }
            ?>
            <div class="row">
                <div class="col-md-8">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#generalinfo" data-toggle="tab">
                                General
                                <span class="glyphicon glyphicon-globe"></span>
                            </a>
                        </li>
                        <li><a href="#members" data-toggle="tab">Members
                                <span class="glyphicon glyphicon-user"></span></a></li>
                        <li><a href="#deliverables" data-toggle="tab">Deliverables
                                <span class="glyphicon glyphicon-info-sign"></span></a></li>
                        <li style="<?php echo($isGroupClosed ? "display:none;" : ""); ?>">
                            <a href="#files" data-toggle="tab">All Files
                                <span class="glyphicon glyphicon-th-list"></span></a></li>

                        <li style="<?php echo($isGroupClosed ? "display:none;" : ""); ?>">
                            <a href="#filesubmission" data-toggle="tab">File Submission
                                <span class="glyphicon glyphicon-upload"></span></a></li>
                        <li style="<?php echo($isGroupClosed ? "display:none;" : ""); ?>">
                            <a href="#deletedfiles" data-toggle="tab">Deleted Files
                                <span class="glyphicon glyphicon-trash"></span> </a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade  in active" id="generalinfo">
                            <h4>General</h4>

                            <table class="table">

                                <tr>
                                    <td>Group ID</td>
                                    <td><?php echo $Group->getGid(); ?></td>
                                </tr>
                                <tr>

                                    <td>Group name</td>
                                    <td> <?php echo $Group->getGName(); ?></td>
                                </tr>
                                <tr>

                                    <td>Group name</td>
                                    <td><?php $group_leader = new User($Group->getLeaderId());
                                        echo $group_leader->getFirstName() . ' ' . $group_leader->getLastName(); ?></td>
                                </tr>
                                <tr>
                                    <td>Semester start</td>
                                    <td><?php echo $Group->getSemester()->getSemesterStartDate(); ?></td>
                                </tr>
                                <tr>
                                    <td>Semester end </td>
                                    <td><?php echo $Group->getSemester()->getSemseterEndDate(); ?></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td><strong><?php echo(($isGroupClosed) ? "<p class='text-danger'>Closed</p>" : "<p class='text-success'>Open</p>"); ?></strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="members">
                            <h4>Members</h4>
                            <table width="100%" border="0" class="table table-bordered table-hover" id="memberstable">
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
                            <ul>

                            </ul>
                        </div>
                        <div class="tab-pane fade" id="deliverables">
                            <h4>Deliverables</h4>
                            <table width="100%" border="0" class="table table-bordered table-hover" id="deliverablestable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Date Posted</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="files">
                            <h4>Files</h4>

                            <table width="100%" border="0" class="table table-bordered table-hover" id="groupfiles">
                                <thead>

                                <tr>

                                    <th>ID</th>
                                    <th>Deliverable Name</th>
                                    <th>File Name</th>
                                    <th>Latest Revision</th>
                                    <th>Revisions</th>
                                    <th>Size</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align:right">Approximate total:</th>
                                    <th colspan="3"></th>
                                </tr>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="deletedfiles">
                            <h4>Deleted Files</h4>
                            Below is a list of deleted files. Files may only be recovered within 24 hours of their deletion.

                            <table width="100%" border="0" class="table table-bordered table-hover" id="deletedFilesTable">
                                <thead>
                                <tr>

                                    <th>File ID</th>
                                    <th>Deliverable Name</th>
                                    <th>File Name</th>
                                    <th>Revisions</th>
                                    <th>Size</th>
                                    <th>Expires</th>

                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>

                        <div class="tab-pane fade" id="filesubmission">
                            <h4>File Submission


                            </h4>
                            <span id="refreshStatus"></span>
                            <div id="groupDeliverablesListSubmission" >

                                <!-- File Uploader -->
                                <form id="uploadForm">

                                    <div class="form-group input-group">



                                        <select class="form-control" id="deliverableSelect">

                                        </select>
                                        <span class="input-group-btn">
                                            <button id="refreshDeliverablesList" class="btn btn-primary" type="button">Refresh</button>
                                        </span>



                                    </div>

                                    <label id="label-browser" class="btn btn-success btn-file" data-toggle="tooltip" data-placement="top" title="Browse Files">
                                        Browse
                                        <input type="file" name="fileUpload" id="fileUpload" class="fileUpload" style="display: none;" multiple/>
                                    </label>


                                    <button type="button" class="btn btn-warning btn-file" id="cancelUpload">Cancel</button>
                                    Max upload size: <?php echo $max_upload = min((int)ini_get('post_max_size'), (int)(ini_get('upload_max_filesize'))); ?>M
                                    <p>
                                    <div class="progress" style="display: none;">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0"
                                            aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                                    </div>
                                    <div id="uploadResult"></div>

                                </form>
                                <!-- /File Uploader -->




                            </div>

                            <p id="noAvailableDeliverables" style="display: none;" class="text-warning">There are no assigned deliverables.</p>

                        </div>



                    </div>
                </div>


                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">

                                <i class="fa fa-bar-chart-o fa-fw"></i>

                                Group Files

                                <div class="pull-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                            Actions
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li><a href="#" id="refreshStats">Refresh data <i class="fa fa-refresh fa-fw"></i></a></li>
                                        </ul>
                                    </div>
                                </div>


                            </div>


                            <div class="panel-body">

                                <div id="filesSummary">

                                    <table class="table">
                                        <tr>
                                            <td>Total files</td>
                                            <td id="_totalFiles">-</td>
                                        </tr>
                                        <tr>
                                            <td>Total uploads</td>
                                            <td id="_totalUploadedFiles">-</td>
                                        </tr>
                                        <tr>
                                            <td>Total downloads (includes non-members)</td>
                                            <td id="_totalDownloadedFiles">-</td>
                                        </tr>
                                        <tr>
                                            <td>Total deleted files</td>
                                            <td id="_totalDeletedFiles">-</td>
                                        </tr>
                                        <tr>
                                            <td>Total permanently deleted files</td>
                                            <td id="_totalPermanentDeletedFiles">-</td>
                                        </tr>
                                    </table>


                                </div>
                                <div id="usedba"></div>
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
    <!-- MODAL WINDOWS -->
    <div id="versionsModal" style="display:none;">


        <table width="100%" border="0" class="table table-bordered" id="versionsTable">
            <thead>
            <tr>

                <th>ID</th>
                <th>User</th>
                <th>Date</th>
                <th>Size</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </div>

    <div id="deleteEntriesContainer" style="display: none;">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
        <div id="deleteEntryContent"></div>
        </p>
    </div>

    <div id="deleteProgress" style="display: none;"></div>

    <div id="recoverFilesContainer" style="display: none;"></div>


    <div id="rollbackResponse" style="display: none;"></div>


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

<!-- File Uploader -->
<script src="bower_components/fileuploader/liteuploader.js"></script>

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
<!-- Highcharts -->
<script src="bower_components/highcharts/js/highcharts.js"></script>


<script>

    $(function ()
    {

        jQuery.fn.bstooltip = jQuery.fn.tooltip;
        $(function () {
            $('[data-toggle="tooltip"]').bstooltip()
        })

        members = $('#memberstable').DataTable({
            "processing" : true,
            "serverSide" : false,
            "displayLength" : 25,
            "ajax" : {
                "url" : "ajax/membersInfo.php",
                "type" : "POST",
                "data" : {
                    "gid" : '<?php echo $Group->getGid(); ?>',
                }
            },
            "columns" : [
                {"data" : "name"},
                {"data" : "username"},
                {"data" : "email"}
            ]
        });


        deliverables = $('#deliverablestable').DataTable({
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
                "url" : "ajax/deliverablesInfo.php",
                "type" : "POST",
                "data" : {
                    "gid" : '<?php echo $Group->getGid(); ?>',
                }
            },
            "columns" : [
                {"data" : "did"},
                {"data" : "name"},
                {"data" : "datePosted"},
                {"data" : "dueDate"},
                {
                    'render' : function (data, type, row)
                    {

                        if (row.open)
                        {
                            return "<p class='text-success'>OPEN</p>";
                        }
                        else
                        {
                            return "<p class='text-danger'>OPEN</p>";
                        }
                    },
                    className : "dt-center"
                }
            ]
        });


        $("#fileUpload").liteUploader({
            script : "fileuploads/",
            params : {
                gid : "<?php echo $Group->getGid();?>",  // group id
            },
            singleFileUploads : false,

            rules : {
                //allowedFileTypes: "image/jpeg,image/jpg, image/png,image/gif,text/plain, application/msword, application/pdf",  // only mime here
                maxSize : '<?php echo CoreConfig::settings()['uploads']['maxupload']; ?>'
            },
            beforeRequest : function (files, formData)
            {

                formData.append("did", $('#deliverableSelect').val());
                return Promise.resolve(formData);
            }
        }).on("lu:start", function (e, files)
        {
            $('.progress').fadeIn();
            $('.progress-bar').fadeIn().attr('aria-valuenow', 0)
                              .removeClass('progress-bar-danger progress-bar-success')
                              .width(0 + "%")
                              .text(0 + '%');
            $('#uploadResult').html("");

            $('#fileUpload').prop("disabled", true);
        }).on("lu:before", function (e, files)
            {

        }).on("lu:cancelled", function (e)
        {
            $('.progress').fadeOut();
            $('.progress-bar').attr('aria-valuenow', 0)
                              .attr("class", "")
                              .width(0 + "%")
                              .text(0 + '%');

            $('#progress').html(0 + "%");
            $('#uploadResult').html("Upload canceled.");

        }).on("lu:success", function (e, response)
        {

            $('#uploadResult').html(response);

            // reset browse button
            var e = $('#uploadForm');
            e.wrap('<form>').closest('form').get(0).reset();
            e.unwrap();

            //groupFiles.ajax.reload();

        }).on("lu:progress", function (e, percentage)
        {
            $('.progress-bar').attr('aria-valuenow', percentage)
                              .addClass('progress-bar-warning')
                              .width(percentage + "%")
                              .text(percentage + '%');

            $('#progress').html(percentage + "%");
            if (percentage == 100)
            {

                $('#uploadResult').html("Your file is being processed... please wait");

            }
        }).on("lu:errors", function (e, errors)
        {
            $('.progress-bar').removeClass('progress-bar-success progress-bar-warning').addClass('progress-bar-danger');
            var output = '<div class="alert alert-danger alert-dismissable">';
            output += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            output += '<ul>';
            for (var i = 0; i < errors.length; i++)
            {
                if (errors[i].type = "size")
                {
                    output += '<li>File size exceeded limit of <strong>' + '<?php echo number_format(CoreConfig::settings()['uploads']['maxupload'] / 1024 / 1024); ?>MB</strong>' + '</li>';
                }
            }
            output += '</ul>';
            output += '</div>';
            $('#uploadResult').html(output);
            $('#fileUpload').prop("disabled", false);
        }).change(function ()
        {
            $(this).data("liteUploader").startUpload();
        });

        $(document).on('click', "#cancelUpload", function ()
        {
            $("#fileUpload").data("liteUploader").cancelUpload();
        });


        /* Group Files table */
        groupFiles = $('#groupfiles').DataTable({
            "processing" : true,
            "serverSide" : false,
            "destroy" : true,
            "displayLength" : 10,
            dom : 'Bfrtip',
            select : {
                style : "os",
                selector : "td:not(:has(:button))" // a row can be selected that doesn't have a button on it
            },
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
                        groupFiles.ajax.reload();
                    }
                },
                {
                    "text" : "Delete",
                    "action" : deleteFiles
                }

            ],
            "ajax" : {
                "url" : "ajax/groupFiles.php",
                "type" : "POST",
                "data" : {
                    "gid" : '<?php echo $Group->getGid(); ?>',
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
                        return '<button title="Rollback to previous version" id="rollbackButton" name="rollbackButton" type="button" class="btn btn-outline btn-danger btn-square btn-sm"> <i class="fa fa-repeat"></i>  </button>';

                    },
                    className : "dt-center"
                },
                {
                    'render' : function (data, type, row)
                    {
                        return '<button title="Download ' + row.filename + '" id="downloadButton" name="downloadButton" type="button" class="btn btn-outline btn-primary btn-square btn-sm"> <i class="fa fa-download"></i></button>';

                    },
                    className : "dt-center"
                }
            ],
            columnDefs : [{
                orderable : false,
                targets : [6, 7]
            }],
            'order' : [[0, "dsc"]],
            "rowCallback" : function (nRow, aData)
            {
                $(nRow).find("td:not(:has(:button))").addClass('selectable');
            },
            "footerCallback" : function (row, data, start, end, display)
            {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function (i)
                {
                    return typeof i === 'string' ?
                    i.replace(/[\{\sMB},]/g, '') * 1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                // Total over all pages
                total = api.column(5).data().reduce(function (a, b)
                {
                    return intVal(a) + intVal(b);
                }, 0);


                // Total over this page
                pageTotal = api.column(5, {page : 'current'}).data().reduce(function (a, b)
                {
                    return intVal(a) + intVal(b);
                }, 0);

                // Update footer

                // convert to megabytes after 1024 KB
                if (pageTotal > 1024)
                {
                    pageTotal /= 1024;
                }
                if (total > 1024)
                {
                    total /= 1024;
                }

                // display
                $(api.column(5).footer()).html(pageTotal.toFixed(2) + ' of  ' + total.toFixed(2) + ' MB');
            },
            "drawCallback" : function (settings)
            {

            }
        });

        /**
         * when clicking on edit button
         **/

        $(document).on('click', '#rollbackButton', function ()
        {
            var fileData = groupFiles.row($(this).closest('tr')).data();

            // This is probably a window that the group leader would have open in order to change the file version... the rollback option

            versionsTable = $('#versionsTable').DataTable({
                "processing" : true,
                "destroy" : true,
                "serverSide" : false,
                "displayLength" : 25,
                select : {
                    style : "os",
                    selector : "td:not(:has(:button))" // a row can be selected that doesn't have a button on it
                },
                "ajax" : {
                    "url" : "ajax/fileVersions.php",
                    "type" : "POST",
                    "data" : {
                        "fid" : fileData.fid
                    }
                },
                "columns" : [
                    {"data" : "vid"},
                    {"data" : "user"},
                    {"data" : "date"},
                    {"data" : "size"},
                    {
                        'render' : function (data, type, row)
                        {

                            return '<button title="Download file version ' + row.filename + '" id="downloadVersionButton" name="downloadVersionButton" type="button" class="btn btn-outline btn-primary btn-square btn-sm"> <i class="fa fa-download"></i></button>';

                        },
                        className : "dt-center"
                    }
                ],
                'order' : [[0, "asc"]],
                columnDefs : [{
                    orderable : false,
                    targets : [4]
                }],
            });


            /* open rollback modal window */
            $("#versionsModal").dialog({
                modal : true,
                width : 600,
                height : 600,
                title : "File: " + fileData.filename,
                show : "fade",
                buttons : {
                    "Rollback" : function ()
                    {
                        var versionData = versionsTable.row('.selected').data();

                        if (typeof versionData === "undefined")
                        {
                            return false;
                        }
                        /* rollback functionality */

                        $.ajax({
                            data : {
                                fid : fileData.fid,
                                vid : versionData.vid
                            },
                            url : "ajax/rollback.php",
                            cache : false,
                            dataType : "html",
                            success : function (data)
                            {

                                $('#rollbackResponse').html(data);
                            }
                        });
                    },
                    "Cancel" : function ()
                    {
                        $(this).dialog("close")
                    }
                },
                close : function (ev, ui)
                {
                    versionsTable.destroy();
                }
            });


            $(document).on('click', '#versionsTable  tbody tr', function ()
            {
                var versionData = versionsTable.row(this).data();
                //console.log(versionData);


            });

        });

        // get files summary
        //loadFileSummary();


        /* deleted files table */
        deletedFilesTable = $('#deletedFilesTable').DataTable({
            processing : true,
            dom : 'Bfrtip',
            "serverSide" : false,
            "destroy" : true,
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
                    text : 'Refresh',
                    action : function (e, dt, node, config)
                    {
                        dt.ajax.reload();
                    }
                },
                {
                    "text" : "Recover",
                    "action" : recoverFiles
                }

            ],
            "ajax" : {
                "url" : "ajax/deletedFilesList.php",
                "type" : "POST",
                "data" : {
                    "gid" : "<?php echo $Group->getGid(); ?>"
                }
            },
            "columns" : [

                {"data" : "fid"},
                {"data" : "deliverable"},
                {"data" : "filename"},
                {"data" : "revisions"},
                {"data" : "size"},
                {"data" : "expires"}
            ],
            'order' : [[2, "asc"]],
            "rowCallback" : function (nRow, aData)
            {
                $(nRow).addClass('selectable');
            }
        });

        // delete files
        function deleteFiles(e, dt, node, config)
        {
            // collect all fids
            var ids = [];
            var files = $.map(dt.rows('.selected').data(), function (item)
            {

                ids.push(item.fid)
                return item;
            });
            if (ids.length == 0)
            {
                alert('nothing to delete!');
                return false;
            }


            var msg = "Are you sure you wish to delete the following files?";
            msg += "<ul>";
            for (var i in files)
            {
                msg += "<li>" + files[i].filename + " </li>";
            }
            msg += "</ul>";
            msg += "Note that all versions associated with each file will also be delete.";

            $('#deleteEntryContent').html(msg);
            $('#deleteEntriesContainer').dialog({
                open : function ()
                {
                    $('.ui-widget-overlay').hide().fadeIn();
                },
                show : 'fade',
                hide : 'fade',
                width : 420,
                height : 500,
                modal : true,
                title : "Delete Files",
                close : function ()
                {
                    // deselect rows
                    groupFiles.rows().deselect();

                    $(this).dialog("destroy");
                },
                buttons : {
                    "Delete Files" : function ()
                    {
                        deleteGroupFiles(ids);
                    },
                    "Cancel" : function ()
                    {
                        $(this).dialog('destroy')
                    }
                }
            });

        }

        function deleteGroupFiles(ids)
        {


            $('#deleteEntriesContainer').dialog("close");
            $('#deleteEntryContent').html("");

            $('#deleteProgress').html("Deleting files...please wait").dialog({
                modal : true,
                width : 300,
                resizable : false,
                height : 230,
                title : "File Deletion"
            });


            $.ajax({
                url : 'ajax/deleteFiles.php',
                data : {fids : ids},
                type : 'POST',
                dataType : 'html',
                cache : false,
                success : function (data)
                {
                    $('#deleteProgress').html(data);
                },
                error : function ()
                {
                    $('#deleteProgress').html("There was an error.");
                }

            });
        };


        function recoverFiles()
        {
            // collect all fids
            var ids = [];
            var files = $.map(deletedFilesTable.rows('.selected').data(), function (item)
            {

                return item;
            });
            if (files.length == 0)
            {
                return false;
            }

            for (var i in files)
            {
                ids.push(files[i].fid);
            }

            $('#recoverFilesContainer').html("Recovering files, please wait...").dialog({
                modal : true,
                width : 300,
                resizable : false,
                height : 230,
                title : "File Recovery"
            });

            $.ajax({
                url : 'ajax/recoverFiles.php',
                data : {fids : ids},
                type : 'POST',
                dataType : 'html',
                cache : false,
                success : function (data)
                {
                    $('#recoverFilesContainer').html(data);
                },
                error : function ()
                {
                    $('#recoverFilesContainer').html("There was an error");
                }

            });
        }


        /**
         * when clicking on a row
         */

        $(document).on('click', '#downloadButton', function (e)
        {
            var fileData = groupFiles.row($(this).closest('tr')).data();

            e.preventDefault();

            window.location.href = "view.php?vid=" + fileData.vid;
            var dls = parseInt($('#_totalDownloadedFiles').text());
            $('#_totalDownloadedFiles').text(++dls);

        });

        $(document).on('click', '#downloadVersionButton', function (e)
        {
            var fileData = versionsTable.row($(this).closest('tr')).data();

            e.preventDefault();

            window.location.href = "view.php?vid=" + fileData.vid;
        });


        /* refresh deliverable list for file submission */
        $(document).on('click', '#refreshDeliverablesList', loadDeliverablesList);

        /* load assigned deliverables */

        loadDeliverablesList();




        usedBand = new Highcharts.Chart({
            chart :{
                renderTo : 'usedba',
                type : 'pie',
            },
            credits : false,
            title: {
                text: 'Storage',

            },
            tooltip: {
                pointFormat: '<b>{point.percentage:.3f}%</b>'
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.3f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{

                colorByPoint: true,
                data: [{
                    name: 'Free',
                    y: 0 ,
                }, {
                    name: 'Used',
                    y : 0

                }]
            }]
        });


        /*refresh stats */
        $(document).on('click','#refreshStats',loadFileSummary);

    });


    function loadDeliverablesList()
    {
        $button = $('#refreshDeliverablesList');
        var buttonText = $button.text();
        $button.prop("disabled",true).text("Loading...");

        $('#noAvailableDeliverables').hide();
        $.ajax({
            url : "ajax/assignedDeliverablesList.php",
            type : "POST",
            dataType : "json",
            cache: false,
            data : {
                gid : "<?php echo $Group->getGid();?>"
            },
            success : function (data)
            {

                $('#refreshStatus').html("");
                $('#deliverableSelect').find('option').remove().end();
                if(jQuery.isEmptyObject(data))
                {
                    $('#noAvailableDeliverables').fadeIn();
                    $('#groupDeliverablesListSubmission').hide();
                }
                else
                {
                    $('#noAvailableDeliverables').hide();
                    $('#groupDeliverablesListSubmission').show();
                    $.each(data, function (index, value)
                    {
                        $('#deliverableSelect')
                            .append($('<option/>', {
                                value : value.did,
                                text : value.name
                            }).prop('selected', true));
                    });
                }

                $button.prop("disabled",false).text(buttonText);


            },
            error : function()
            {
                $('#refreshStatus').html("<p class='text-danger'>An error occured: could not load deliverables list.</p>");
                $button.prop("disabled",false).text(buttonText);

            }
        });

    }

    // must be put here to be used globally
    function loadFileSummary()
    {
        $at = $("#filesSummary td[id^='_']")
        $.each($at, function(key, value){
            $(value).html('loading...');
        });
        $.ajax({
            data : {
                gid : "<?php echo $Group->getGid(); ?>"
            },
            url : "ajax/filesSummary.php",
            dataType : "json",
            cache : false,
            success : function (data)
            {
                usedBand.series[0].setData([{ y : data.free},{y :data.used}], true);
                usedBand.setTitle({ text : "Storage" }, { text : "Total: <strong>" + data.total + "MB</strong>" });
                usedBand.redraw(true);
                // find the stats containers and give their values
                $.each(data, function(key, value){
                    if(key.toString().startsWith("_"))
                    {
                        $('#' + key).text(value);
                    }

                });

            }
        });
    }

    loadFileSummary();


</script>

</body>

</html>
