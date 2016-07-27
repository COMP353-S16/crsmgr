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
                <div class="col-md-12">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#managegroups" data-toggle="tab">Manage Groups</a></li>
                        <li><a href="#deliverablesManager" data-toggle="tab">Deliverables</a></li>
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

                            <?php
                            if (WebUser::getUser()->isProf() || WebUser::getUser()->isSysAdmin())
                            {
                                ?>

                                <button type="button" id="createGroupButton" class="btn btn-success">Add new group</button>
                                <?php
                            } ?>

                        </div>
                        <div class="tab-pane fade" id="deliverablesManager">
                            <h4>Deliverables</h4>


                            <table id="deliverablesTable" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                            <?php
                            if (WebUser::getUser()->isProf() || WebUser::getUser()->isSysAdmin())
                            {
                                ?>
                                <button type="button" id="createNewDeliverable" class="btn btn-primary">New Deliverable</button>
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
                <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span><span id="deleteMemberConfirmationMessage"></span>
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
                                    <input placeholder="Deliverable name" name="newDeliverableName" id="newDeliverableName" class="form-control">
                                    <span class="help-block"></span>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>Start date:</td>
                            <td>
                                <div class="form-group has-feedback">
                                    <input placeholder="Start date" id="newDeliverableStartDate" name="newDeliverableStartDate" class="form-control">
                                    <span class="help-block"></span>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>End date:</td>
                            <td>
                                <div class="form-group has-feedback">
                                    <input placeholder="End date" id="newDeliverableEndDate" name="newDeliverableEndDate" class="form-control">
                                    <span class="help-block"></span></div>
                            </td>
                        </tr>
                        <tr>
                            <td>Semester:</td>
                            <td>
                                <div class="form-group has-feedback">
                                    <select class="form-control" id="selectSemesterNewDeliverable" name="selectSemesterNewDeliverable">
                                        <option value="">--Select--</option>
                                        <?php
                                        $pdo = Registry::getConnection();
                                        $query = $pdo->prepare("SELECT * FROM Semester");
                                        $query->execute();
                                        while ($sec = $query->fetch())
                                        {
                                            ?>
                                            <option value="<?php echo $sec['sid']; ?>">Semester <?php echo $sec['sid'] . ' (' . $sec['startDate'] . ') - (' . $sec['endDate'] . ')'; ?></option>
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
                                    <input id="groupBandwidth" name="groupBandwidth" class="form-control" placeholder="Enter group file bandwidth (MB)">
                                    <span class="help-block"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Select Section:</td>
                            <td>
                                <div class="form-group has-feedback">
                                    <select class="form-control" id="sectionSelect">
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
                                    <span class="help-block"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Student Name:</td>
                            <td><input id="studentName" class="form-control" placeholder="Enter student name"></td>
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
                <li class="active"><a href="#editGroupGeneral" data-toggle="tab">General</a></li>
                <li><a href="#editGroupMembers" data-toggle="tab">Members</a></li>
                <li><a href="#editGroupDeliverables" data-toggle="tab">Deliverables</a></li>
                <li><a href="#editGroupFiles" data-toggle="tab">Files</a></li>
                <li><a href="#editGroupStats" data-toggle="tab">Statistics</a></li>
            </ul>

            <div class="tab-content">

                <div class="tab-pane fade in active" id="editGroupGeneral">
                    <h4>General</h4>
                    <form role="form" id="editGroupForm">
                        <div class="form-group">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Group Name:</td>
                                    <td>
                                        <input id="newGroupNameEdit" name="newGroupNameEdit" class="form-control" placeholder="Enter group name">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Maximum Bandwidth:</td>
                                    <td>
                                        <input id="groupBandwidthEdit" name="groupBandwidthEdit" class="form-control" placeholder="Enter group file bandwidth (MB)">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Select Section:</td>
                                    <td>
                                        <select class="form-control" id="sectionSelectEdit">
                                            <option value="all">All sections</option>
                                            <?php
                                            $pdo = Registry::getConnection();
                                            $query1 = $pdo->prepare("SELECT DISTINCT sectionName FROM StudentSemester");
                                            $query1->execute();
                                            while ($sec = $query->fetch())
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
                                    <td>Student Name:</td>
                                    <td>
                                        <input id="studentNameEdit" class="form-control" placeholder="Enter student name">
                                    </td>
                                </tr>
                            </table>


                            <table width="100%" class="table table-bordered" id="selectedStudentsTableEditGroup" name="selectedStudentsTableEditGroup">
                                <thead>
                                </thead>
                                <tbody></tbody>
                            </table>


                        </div>
                        <input type="submit" hidden id="hiddenEditGroupSubmit">
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

                </div>

                <div class="tab-pane fade" id="editGroupDeliverables">
                    <h4>Deliverables</h4>
                </div>

                <div class="tab-pane fade" id="editGroupFiles">
                    <h4>Files</h4>

                    <table width="100%" border="0" class="table table-bordered table-hover" id="groupFiles">
                        <thead>

                        <tr>

                            <th>File ID</th>
                            <th>Deliverable Name</th>
                            <th>File Name</th>
                            <th>Latest Revision</th>
                            <th>Revisions</th>
                            <th>Size</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>


                </div>

                <div class="tab-pan fade" id="editGroupStats">
                    <h4>Statistics</h4>
                </div>
            </div>

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

            /* groups table */
            groups = $('#groupstable').DataTable({
                "processing" : true,
                "serverSide" : false,
                "displayLength" : 25,
                "ajax" : {
                    "url" : "ajax/groupsInfo.php",
                    "type" : "POST",
                    "data" : {}
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

                            var edit = '<button data-gname="' + row.gName + '" data-gid="' + row.gid + '" id="groupEdit" title="Edit group" type="button" class="btn btn-warning btn-square btn-sm"><i class="fa fa-pencil-square"></i></button>&nbsp';
                            var deleteB = '<button  data-gid="' + row.gid + '" data-gname="' + row.gName + '"  id="groupDelete" title="Delete group" type="button" class="btn btn-danger btn-square btn-sm"><i class="fa fa-times"></i> </button>';


                            return edit + deleteB;
                        }
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
                        $('form#createGroupForm')[0].reset();

                        // reset datatable
                        selectedStudentsTable.clear().draw();
                        students = [];
                        selected = [];
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
                    "buttons" : {
                        "Delete Group" : function ()
                        {


                            $('#deleteGroupModal').dialog({buttons : {}}).html("Deleting group, lease wait...");

                            $.ajax({
                                url : 'ajax/groupDelete.php',
                                data : "gid=" + gid,
                                type : 'POST',
                                error : function ()
                                {
                                    console.log('An error occured');
                                },
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
                source : function (request, response)
                {
                    $.ajax({
                        url : "ajax/studentSearch.php",
                        dataType : "json",
                        data : {
                            studentName : request.term,
                            selectedStudents : selected,
                            section : $('#sectionSelect').val()
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


            /* new group validator */
            $('form#createGroupForm').validate({
                rules : {
                    newGroupName : {
                        required : true
                    },
                    groupBandwidth : {
                        required : true,
                        number : true,
                        min : 1,
                        max : 2048
                    }
                },
                submitHandler : function (form)
                {
                    var serialized = $('#createGroupForm :input').serialize();
                    console.log(serialized);
                    // send data to server to check for credentials
                    $('#createGroupAjax').html("Please wait while group is being created").dialog({
                        title : 'Create Group',
                        modal : true,
                        width : 300,
                        height : 200,
                        draggable : false,
                        resizable : false
                    });

                    $.ajax({
                        url : 'ajax/createGroup.php',
                        data : {
                            form : serialized,
                            uids : selected,
                            maxb : $('#groupBandwidth').val()
                        },
                        type : 'POST',
                        error : function ()
                        {
                        },
                        dataType : 'html',
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
                        "text" : "Remove",
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
                    {"data" : "startDate"},
                    {"data" : "endDate"}
                ]
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
            $('form#newDeliverableForm').validate({
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
            $('#createNewDeliverable').click(function (){
                $('#newDeliverableModal').dialog({
                    width : 650,
                    height : 550,
                    modal : true,
                    resizable : false,
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
                        $('form#newDeliverableForm')[0].reset();
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
                "displayLength" : 25,
                data : studentsEdit,
                dom : 'Bfrtip',
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
                        "text" : "Remove",
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


                $('#editGroupModal').dialog({
                    width : 900,
                    height : 800,
                    modal : true,
                    title : data.gName
                });

                // INITIALIZE GROUP FILES
                groupFiles = $('#groupFiles').DataTable({
                    "processing" : true,
                    "serverSide" : false,
                    destroy : true,
                    "displayLength" : 10,
                    dom : 'Bfrtip',
                    buttons : [

                        {
                            text : 'Refresh',
                            action : function (e, dt, node, config)
                            {
                                groupFiles.ajax.reload();
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
                        {"data" : "size"}
                    ],
                    'order' : [[2, "asc"]],
                    "rowCallback" : function (nRow, aData)
                    {
                        $(nRow).addClass('selectable');
                    }
                });

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
                                groupMembers.ajax.reload();
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
                                    var deleteB = '<button data-gid="' + data.gid + '" id="deleteMember" title="Delete member" type="button" class="btn btn-danger btn-square btn-sm"><i class="fa fa-times"></i></button>&nbsp';

                                    var promote = '<button data-gid="' + data.gid + '" id="promoteMember" title="Promote member" type="button" class="btn btn-success btn-square btn-sm"><i class="fa fa-arrow-circle-up"></i></button>&nbsp';
                                }
                                return deleteB + promote;
                            }
                        }
                    ],

                    'order' : [[1, "asc"]],
                    columnDefs : [{
                        orderable : false,
                        targets : [3]
                    }],
                });

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
                            section : $('#sectionSelectEdit').val()
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
                            }).html("Remove member...please wait...");

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
        });

    </script>
</body>
</html>
