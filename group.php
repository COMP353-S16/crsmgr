<?php
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/dbc.php');
$pdo = Registry::getConnection();
$query = $pdo->prepare("SELECT did FROM Deliverables");
$query->execute();
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
            z-index:10000
        }
        .selectable {

            cursor:pointer;
        }

    </style>
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
                    $gid = $_GET['gid'];
                    $Group = new Group($gid);
                    ?>
                    <h1 class="page-header"><?php echo 'Group ' .$Group->getGName()?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-md-8">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#members" data-toggle="tab">Members <span class="glyphicon glyphicon-user"></span></a></li>
                        <li><a href="#deliverables" data-toggle="tab">Deliverables <span class="glyphicon glyphicon-info-sign"></span></a></li>
                        <li><a href="#files" data-toggle="tab">All Files <span class="glyphicon glyphicon-th-list"></span></a></li>

                        <li><a href="#filesubmission" data-toggle="tab">File Submission <span class="glyphicon glyphicon-upload"></span></a> </li>
                        <li><a href="#deletedfiles" data-toggle="tab">Deleted Files <span class="glyphicon glyphicon-trash"></span> </a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="members">
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

                            <table width="100%" border="0" class="table table-bordered table-hover" id="groupfiles">
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
                                <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align:right">Approximate total:</th>
                                    <th colspan="2"></th>
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
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>

                        <div class="tab-pane fade" id="filesubmission">
                            <h4>File Submission</h4>

                            <?php
                            if($query->rowCount()>0)
                            {
                            ?>
                            <!-- File Uploader -->
                            <form id="uploadForm">

                                <div class="form-group">
                                    <label for="sel1">Select Deliverable</label>
                                    <select class="form-control" id="deliverableSelect">
                                        <?php
                                        while($del = $query->fetch())
                                        {
                                            $Deliverable = new Deliverable($del['did']);

                                            $startDate = $Deliverable->getStartDate();

                                            if(time() >= strtotime($startDate))
                                            {


                                                ?>
                                                <option value="<?php echo $del['did']; ?>"><?php echo $Deliverable->getDName(); ?>
                                                   - Due on <?php echo $Deliverable->getEndDate(); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <label id="label-browser" class="btn btn-success btn-file">
                                    Browse
                                    <input type="file" name="fileUpload" id="fileUpload" class="fileUpload" style="display: none;" multiple/>
                                </label>




                                <button class="btn btn-warning btn-file" id="cancelUpload">Cancel</button>
                                Max upload size: <?php echo $max_upload = min((int)ini_get('post_max_size'), (int)(ini_get('upload_max_filesize'))); ?>M
                                <p>
                                <div class="progress" style="display: none;">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0"
                                         aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                                </div>
                                <div id="uploadResult"></div>

                            </form>
                            <!-- /File Uploader -->

                            <?php
                            }
                            else
                            {
                                ?>
                                <p class="text-warning">There are no assigned deliverables.</p>
                                <?php
                            }
                            ?>

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
                                <li><?php echo 'Number of uploaded files: ' .$Group_Files->getNbOfUploadedFiles()?></li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Group Files
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li>Bandwidth: <span id="bandwidth">-</span> </li>
                                <li>Total Files: <span id="totalFiles">-</span> </li>
                                <li>Deleted Files: <span id="totalDeletedFiles">-</span> </li>
                                <li>Used Bandwidth: <span id="usedBandwidth">-</span> </li>
                                <li>Number of Downloads: <span id="downloads">-</span> </li>
                                <li>Number of Revisions: <span id="revisions">-</span> </li>
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
    <!-- MODAL WINDOWS -->
    <div id="fileInfoModal" style="display:none;">
        <div id="versionsContainer">

            <table width="100%" border="0" class="table table-bordered" id="versionsTable">
                <thead>
                <tr>

                    <th>ID</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Size</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div id="deleteEntriesContainer" style="display: none;">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span><div id="deleteEntryContent"></div></p>
    </div>

    <div id="deleteProgress"></div>
    
    <div id="recoverFilesContainer" style="display: none;"></div>

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


<script>

    $(function (){


        members = $('#memberstable').dataTable({
            "processing": true,
            "serverSide": false,
            "displayLength": 25,
            "ajax": {
                "url" : "ajax/membersInfo.php",
                "type" : "POST",
                "data" : {
                    "gid" : '<?php echo $Group->getGid(); ?>',

                }
            },
            "columns": [
                {"data": "name"},
                {"data": "username"},
                {"data": "email"}
            ]
        });


        deliverables = $('#deliverablestable').dataTable({
            "processing": true,
            "serverSide": false,
            "displayLength": 25,
            "ajax": {
                "url" : "ajax/deliverablesInfo.php",
                "type" : "POST",
                "data" : {
                    "gid" : '<?php echo $Group->getGid(); ?>',
                }
            },
            "columns": [
                {"data": "name"},
                {"data": "datePosted"},
                {"data": "dueDate"}
            ]
        });




        $("#fileUpload").liteUploader({
            script: "fileuploads/",
            params: {
                gid: "<?php echo $Group->getGid();?>",  // group id
            },
            singleFileUploads: true,

            rules: {
                //allowedFileTypes: "image/jpeg,image/jpg, image/png,image/gif,text/plain, application/msword, application/pdf",  // only mime here
                maxSize: <?php echo $max_upload = min((int)ini_get('post_max_size'), (int)(ini_get('upload_max_filesize'))) * 1024 * 1024; ?>
            },
            beforeRequest : function(files, formData)
            {

                formData.append("did", $('#deliverableSelect').val() );
                return Promise.resolve(formData);
            }
        }).on("lu:start", function(e, files){
            $('.progress').fadeIn();
        }).on("lu:before", function (e, files) {

            $('.progress-bar').attr('aria-valuenow', 0)
                .width(0 + "%")
                .text(0 + '%');
            $('#uploadResult').html("");


        }).on("lu:cancel", function (e) {

            $('.progress-bar').attr('aria-valuenow', 0)
                .removeClass('progress-bar-danger').addClass('progress-bar-success')
                .width(0 + "%")
                .text(0 + '%');

            $('#progress').html(percentage + "%");



        }).on("lu:success", function (e, response) {

            $('#uploadResult').html(response);

            // reset browse button
            var e = $('#uploadForm');
            e.wrap('<form>').closest('form').get(0).reset();
            e.unwrap();

            groupFiles.ajax.reload();

        }).on("lu:progress", function (e, percentage) {

            $('.progress-bar').attr('aria-valuenow', percentage)
                .removeClass('progress-bar-danger').addClass('progress-bar-success')
                .width(percentage + "%")
                .text(percentage + '%');

            $('#progress').html(percentage + "%");

        }).on("lu:errors", function (e, errors) {
            console.log(errors);

            for (var i = 0; i < errors.length; i++) {
                if (errors[i].type = "type") {
                    console.log('Invalid file type');
                }
            }

        }).change(function () {
            $(this).data("liteUploader").startUpload();
        });

        $("#cancelUpload").click(function () {
            $("#fileUpload").data("liteUploader").cancelUpload();
        });










        /* Group Files table */
        groupFiles = $('#groupfiles').DataTable({
            "processing": true,
            "serverSide": false,
            "displayLength": 10,
            dom: 'Bfrtip',
            select: {
                style : "os",
                selector: ':checkbox'
            },
            buttons:[
                {
                    "extend": "selectAll",
                    "action": function ()
                    {
                        var rows = groupFiles.rows();
                        for(var i = 0; i < rows.length; i++)
                        {
                            $(rows[i]).find(':checkbox').prop("checked", true);
                        }

                        groupFiles.rows().select();
                    }
                },
                {
                    "extend": "selectNone",
                    "action": function ()
                    {
                        var rows = groupFiles.rows();
                        for(var i = 0; i < rows.length; i++)
                        {
                            $(rows[i]).find(':checkbox').prop("checked", false);
                        }
                        groupFiles.rows().deselect();
                    }
                },
                {
                    "text" : "Delete",
                    "action": deleteFiles
                }

            ],
            "ajax": {
                "url" : "ajax/groupFiles.php",
                "type" : "POST",
                "data" : {
                    "gid" : 1,
                    "did" : 1
                }
            },
            "columns": [

                {"data": "fid"},
                {"data" : "deliverable"},
                {"data": "filename"},
                {"data": "ldate"},
                {"data": "revisions"},
                {"data" : "size"},
                {
                    'render': function ( data, type, row )
                    {
                        return '<input type="checkbox" data-fid="'+row.fid+'" name="fid[]">';

                    }
                }
            ],
            columnDefs: [{
                orderable: false,
                targets:   6
            }],
            'order': [[2, "asc"]],
            "rowCallback": function (nRow, aData)
            {
                $(nRow).addClass('selectable');
            },
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                    i.replace(/[\{\sKB},]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                // Total over all pages
                total = api.column(5).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    },0);

                console.log(total);
                // Total over this page
                pageTotal = api.column( 5, { page: 'current'} ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    },0);

                // Update footer

                // convert to megabytes after 1024 KB
                if(pageTotal>1024)
                    pageTotal /= 1024;
                if(total > 1024)
                    total /= 1024;

                // display
                $(api.column(5).footer()).html(pageTotal.toFixed(2) +' of  '+ total.toFixed(2) +' MB');
            },
            "drawCallback" : function(settings)
            {

            }
        });

        // get files summary
        loadFileSummary();


        /* deleted files table */
        deletedFilesTable = $('#deletedFilesTable').DataTable({
            "processing": true,
            "serverSide": false,
            "displayLength": 25,
            dom: 'Bfrtip',
            select: {
                style : "os",
                selector: ':checkbox'
            },
            buttons:[
                {
                    "extend": "selectAll",
                    "action": function ()
                    {
                        var rows = deletedFilesTable.rows();
                        for(var i = 0; i < rows.length; i++)
                        {
                            $(rows[i]).find(':checkbox').prop("checked", true);
                        }

                        deletedFilesTable.rows().select();
                    }
                },
                {
                    "extend": "selectNone",
                    "action": function ()
                    {
                        var rows = deletedFilesTable.rows();
                        for(var i = 0; i < rows.length; i++)
                        {
                            $(rows[i]).find(':checkbox').prop("checked", false);
                        }
                        deletedFilesTable.rows().deselect();
                    }
                },
                {
                    "text" : "Recover",
                    "action": recoverFiles
                }

            ],
            "ajax": {
                "url" : "ajax/deletedFilesList.php",
                "type" : "POST",
                "data" : {
                    "gid" : "<?php echo $Group->getGid(); ?>"
                }
            },
            "columns": [

                {"data": "fid"},
                {"data" : "deliverable"},
                {"data": "filename"},
                {"data": "revisions"},
                {"data" : "size"},
                {"data" : "expires"},

                {
                    'render': function ( data, type, row )
                    {
                        return '<input type="checkbox" data-fid="'+row.fid+'" name="fid[]">';

                    }
                }
            ],
            columnDefs: [{
                orderable: false,
                targets:   6
            }],
            'order': [[2, "asc"]],
            "rowCallback": function (nRow, aData)
            {
            }
        });

        // delete files
        function deleteFiles( e, dt, node, config )
        {
            // collect all fids
            var ids = [];
            var files = $.map(dt.rows('.selected').data(), function (item) {

                return item;
            });
            if(files.length == 0)
            {
                alert('nothing to delete!');
                return false;
            }
            console.log(files);

            var msg = "Are you sure you wish to delete the following files?";
            msg += "<ul>";
            for (var i in files) {
                msg += "<li>" + files[i].filename + " </li>";
                ids.push(files[i].fid);
            }
            msg += "</ul>";
            msg += "Note that all versions associated with each file will also be delete.";

            $('#deleteEntryContent').html(msg);
            $('#deleteEntriesContainer').dialog({
                open: function ()
                {
                    $('.ui-widget-overlay').hide().fadeIn();
                },
                show: 'fade',
                hide: 'fade',
                width: 420,
                height: 500,
                modal: true,
                title: "Delete Files",
                close : function()
                {
                    // uncheck boxes
                    $(":checkbox:checked").each(function(){
                        $(this).prop("checked", false);

                    });
                    // deselect rows
                    groupFiles.rows().deselect();

                    $(this).dialog("destroy");
                },
                buttons:
                {
                    "Delete Files" : function()
                    {
                        deleteGroupFiles(ids);
                    },
                    "Cancel": function()
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
                modal: true,
                width: 300,
                resizable: false,
                height: 230,
                title: "File Deletion"
            });


            $.ajax({
                url: 'ajax/deleteFiles.php',
                data: {fids: ids},
                type: 'POST',
                dataType: 'html',
                success: function(data)
                {
                    $('#deleteProgress').html(data);
                },
                error: function()
                {
                    $('#deleteProgress').html("There was an error.");
                }

            });
        };


        function recoverFiles()
        {
            // collect all fids
            var ids = [];
            var files = $.map(deletedFilesTable.rows('.selected').data(), function (item) {

                return item;
            });
            if(files.length == 0)
            {
                return false;
            }

            for (var i in files)
            {
                ids.push(files[i].fid);
            }

            $('#recoverFilesContainer').html("Recovering files, please wait...").dialog({
                modal: true,
                width: 300,
                resizable: false,
                height: 230,
                title: "File Recovery"
            });

            $.ajax({
                url: 'ajax/recoverFiles.php',
                data: {fids: ids},
                type: 'POST',
                dataType: 'html',
                success: function(data)
                {
                    $('#recoverFilesContainer').html(data);
                },
                error: function()
                {
                    $('#recoverFilesContainer').html("There was an error");
                }

            });
        }



        /**
         * when clicking on a row
         */

        $(document).on('click', '#groupfiles  tbody tr td:not(:last-child)', function () {
            var fileData = groupFiles.row(this).data();
            console.log(fileData);
            window.open("view.php?fid=" + fileData.fid)
            
        });
        
    });

    // must be put here to be used globally
    function loadFileSummary() {

        $.ajax({
            data: {
                gid : "<?php echo $Group->getGid(); ?>"
            },
            url: "ajax/filesSummary.php",
            dataType: "json",
            success: function (data)
            {
                $('#downloads').text(data.downloads);
                $('#totalFiles').text(data.totalFiles);
                $('#revisions').text(data.revisions);
                $('#bandwidth').text(data.bandwidth);
                $('#usedBandwidth').text(data.usedBandwidth);
                $('#totalDeletedFiles').text(data.totalDeletedFiles);
            }
        });
    }
</script>

</body>

</html>
