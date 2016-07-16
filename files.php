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

    </style>


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
                    <h1 class="page-header">Home</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- File Uploader -->
            <form id="uploadForm">

                <label id="label-browser" class="btn btn-success btn-file">
                    Browse
                    <input type="file" name="fileUpload" id="fileUpload" class="fileUpload" style="display: none;" multiple/>
                </label>
                <button class="btn btn-warning btn-file" id="cancelUpload">Cancel</button>
                Max upload size: <?php echo $max_upload = min((int)ini_get('post_max_size'), (int)(ini_get('upload_max_filesize'))); ?>M
                <p>
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0"
                         aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                </div>
                <div id="uploadResult"></div>
                </p>
            </form>

            <!-- /File Uploader -->





            <table width="100%" border="0" class="table table-bordered" id="groupfiles">
                <thead>
                <tr>

                    <th>ID</th>
                    <th>Deliverable ID</th>
                    <th>File Name</th>
                    <th>Latest Revision</th>
                    <th>Revisions</th>
                    <th>Size</th>
                    <th></th>
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

            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->

    <!-- MODAL WINDOWS -->
    <div id="fileInfoModal"></div>
    <div id="deleteEntriesContainer"><div id="deleteEntryContent"></div></div>

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
    $(function () {
        $("#fileUpload").liteUploader({
            script: "fileuploads/",
            params: {
                gid: 1,  // group id
                did: 1   // deliverable id
            },
            singleFileUploads: true,

            rules: {
                //allowedFileTypes: "image/jpeg,image/jpg, image/png,image/gif,text/plain, application/msword, application/pdf",  // only mime here
                maxSize: <?php echo $max_upload = min((int)ini_get('post_max_size'), (int)(ini_get('upload_max_filesize'))) * 1024 * 1024; ?>
            }
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
            "displayLength": 25,
            dom: 'Bfrtip',
            select: {
                style : "os",
                selector: 'td:has(:checkbox)'
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
                targets:   4
            }],
            'order': [[0, "asc"]],
            "rowCallback": function (nRow, aData)
            {
                // when row is created
                //console.log(aData);
            }
        });


        // delete files
        function deleteFiles( e, dt, node, config )
        {
            // collect all fids
            var ids = $.map(dt.rows('.selected').data(), function (item) {

                return item;
            });
            if(ids.length == 0)
            {
                alert('nothing to delete!');
                return false;
            }
            console.log(ids);

            var msg = "Are you sure you wish to delete the following files?";
            msg += "<ul>";
            for (var i in ids) {
                msg += "<li>" + ids[i].filename + " </li>";
            }
            msg += "</ul>";
            msg += "Note that all versions associated with each file will also be delete.";

            $('#deleteEntryContent').html(msg);
            $('#deleteEntriesContainer').dialog({
                open: function () {
                    $('.ui-widget-overlay').hide().fadeIn();
                },
                close: function () {
                },
                show: 'fade',
                hide: 'fade',
                width: 420,
                height: 300,
                modal: true,
                title: "Delete Files",
                buttons: {
                    "Cancel": function () {
                        $(this).dialog('destroy');

                    }
                }
            });

        }


        /**
         * when clicking on a row
         */

        $(document).on('click', '#groupfiles  tbody tr td:not(:first-child)', function () {
            var fileData = groupFiles.row(this).data();

            console.log(fileData);

           /* $("#fileInfoModal").dialog({
                modal: true,
                width: 600,
                height: 600,
                title: "File: " + fileData.filename,
                show: "fade",
                close: function (ev, ui) {
                    $(this).html("");
                }
            });*/


        });



    });

</script>

</body>

</html>
