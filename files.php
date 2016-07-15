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





            <table width="100%" border="0" class="table" id="groupfiles">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>File Name</th>
                    <th>Latest Date</th>
                    <th>Revision</th>
                </tr>
                </thead>
                <tbody>
                <tr>
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
<script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<script src="bower_components/datatables-plugins/ajaxreloader/fnReloadAjax.js"></script>



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

            T.fnReloadAjax(null, null, true);

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









        T = $('#groupfiles').dataTable({
            "bProcessing": true,
            "bServerSide": false,
            "sAjaxSource": "fileuploads/groupFiles.php",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "url": sSource,
                    "data": "gid=" + 1 + "&did=" + 1,
                    cache: false,
                    "success": fnCallback,
                });
            },
            "columns": [
                {"data": "fid"},
                {"data": "filename"},
                {"data": "ldate"},
                {"data": "version"}
            ],
            'aaSorting': [[0, "asc"]],
            'iDisplayLength': 25
        });




    });

</script>

</body>

</html>