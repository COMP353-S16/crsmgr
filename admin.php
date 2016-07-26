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
        .ui-autocomplete-loading { background:url('images/ajax.gif') no-repeat right center }
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
                            if(WebUser::getUser()->isProf() || WebUser::getUser()->isSysAdmin()) {
                                ?>

                                <button type="button" id="createGroupButton" class="btn btn-success">Add new group</button>
                                <?php
                            }?>

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
                            if(WebUser::getUser()->isProf() || WebUser::getUser()->isSysAdmin())
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
            <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>The group will be deleted. Are you sure?</p>
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
                            <td>Deliverable name: </td>
                            <td>

                                <input placeholder="Deliverable name" name="newDeliverableName" id="newDeliverableName" class="form-control">
                            </td>

                        </tr>
                        <tr>
                            <td>Start date: </td>
                            <td>

                                <input placeholder="Start date" id="newDeliverableStartDate" name="newDeliverableStartDate" class="form-control">


                            </td>
                        </tr>
                        <tr>
                            <td>End date: </td>
                            <td><input placeholder="End date" id="newDeliverableEndDate" name="newDeliverableEndDate" class="form-control"></td>
                        </tr>
                        <tr>
                            <td>Semester: </td>
                            <td>

                                <select class="form-control" id="selectSemesterNewDeliverable" name="selectSemesterNewDeliverable">
                                    <option value="">--Select--</option>
                                    <?php
                                    $pdo = Registry::getConnection();
                                    $query = $pdo->prepare("SELECT * FROM Semester");
                                    $query->execute();
                                    while($sec = $query->fetch())
                                    {
                                        ?>
                                        <option value="<?php echo $sec['sid']; ?>">Semester <?php echo $sec['sid']. ' ('.$sec['startDate'].') - ('.$sec['endDate'].')'; ?></option>
                                        <?php
                                    }
                                    ?>

                                </select>



                            </td>
                        </tr>
                        <tr>
                            <td>Assign to: </td>
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
                            <td>Group Name: </td>
                            <td> <input id="newGroupName" name="newGroupName" class="form-control" placeholder="Enter group name"></td>
                        </tr>
                        <tr>
                            <td>Maximum Bandwidth: </td>
                            <td>
                                <input id="groupBandwidth" name="groupBandwidth" class="form-control" placeholder="Enter group file bandwidth (MB)">
                            </td>
                        </tr>
                        <tr>
                            <td>Select Section:</td>
                            <td>
                                <select class="form-control" id="sectionSelect">
                                    <option value="all">All sections</option>
                                    <?php
                                    $pdo = Registry::getConnection();
                                    $query = $pdo->prepare("SELECT DISTINCT sectionName FROM StudentSemester WHERE sid=:sid");
                                    $query->bindValue(":sid", 1); // SEMESTER SHOULD GO HERE
                                    $query->execute();
                                    while($sec = $query->fetch())
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
                            <td>Student Name: </td>
                            <td><input id="studentName" class="form-control" placeholder="Enter student name"></td>
                        </tr>
                    </table>

                    <table class="table table-bordered" width="100%">
                        <tr>
                            <th>Members</th>
                        </tr>
                        <tr>
                            <td><table width="100%"  class="table table-bordered" id="selectedStudentsTable">


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
                <li class="active"><a href="#editGroupMembers" data-toggle="tab">Members</a></li>
                <li><a href="#editGroupDeliverables" data-toggle="tab">Deliverables</a></li>
                <li><a href="#editGroupFiles" data-toggle="tab">Files</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="editGroupMembers">
                    <h4>Members</h4>
                </div>
                <div class="tab-pane fade" id="editGroupDeliverables">
                    <h4>Deliverables</h4>
                </div>

                <div class="tab-pane fade" id="editGroupFiles">
                    <h4>Files</h4>
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
    <script>

        $(function () {

            groups = $('#groupstable').DataTable({
                "processing": true,
                "serverSide": false,
                "displayLength": 25,
                "ajax": {
                    "url": "ajax/groupsInfo.php",
                    "type": "POST",
                    "data": 
                    {

                    }
                },
                "columns": [
                    {"data": "gid"},
                    {"data": "gName"},
                    {"data": "leaderId"},
                    {"data": "creatorId"},
                    {"data" : "sid"},
                    {"data":"totalMembers"},
                    {
                        'render': function (data, type, row) {
                            console.log(row);
                            var edit = '<button data-gname="' + row.gName + '" data-gid="' + row.gid + '" id="groupEdit" title="Edit group" type="button" class="btn btn-warning btn-square btn-sm"><i class="fa fa-pencil-square"></i></button>&nbsp';
                            var deleteB = '<button  data-gid="' + row.gid + '" data-gname="' + row.gName + '"  id="groupDelete" title="Delete group" type="button" class="btn btn-danger btn-square btn-sm"><i class="fa fa-times"></i> </button>';


                            return edit + deleteB;
                        }
                    }
                ],
                columnDefs: [{
                    orderable: false,
                    targets:   [6]
                }],
            });

            $(document).on('click', '#deleteConfirmButton', function () {

            });






            /* To create a group */
            $(document).on('click', '#createGroupButton', function () {
                $("#createGroupModal").dialog( {
                    modal: true,
                    title: "Create Group",
                    show: "fade",
                    width: 800,
                    height: 700,
                    resizable : false,
                    buttons :
                    {
                        "Create" : function()
                        {
                            // since the modal button is not a submit button and not attached to form, added a hidden submit button and triggered a click
                            $('#hiddenSubmit').trigger('click');

                        },
                        "Cancel" : function()
                        {
                            $(this).dialog("close");
                        }
                    },
                    close : function()
                    {
                        $(this).dialog("destroy");
                        $("input#studentName").val("");

                        // reset datatable
                        selectedStudentsTable.clear().draw();
                        students = [];
                        selected = [];
                    }
                })
            });

            /* Cancel Delete Button */
            $(document).on('click', '#deleteCancelButton', function () {
                $("#deleteGroupModal").dialog("destroy");
            });
<!-- TODO: change this to grab entire row instead of individual vars -->
            $(document).on('click', '#groupDelete', function () {
                var gid = $(this).data('gid');
                var gName = $(this).data('gname');

                $("#deleteGroupModal").dialog({
                    modal: true,
                    title: "Delete " + gName + "?",
                    show: "fade",
                    "buttons" :
                    {
                        "Delete Group": function()
                        {



                            $('#deleteGroupModal').dialog({ buttons:{}}).html("Deleting group, lease wait...");

                            $.ajax({
                                url: 'ajax/groupDelete.php',
                                data: "gid=" + gid,
                                type: 'POST',
                                error: function()
                                {
                                    console.log('An error occured');
                                },
                                dataType: 'html',
                                success: function(data)
                                {
                                    $('#deleteGroupModal').html(data);
                                }
                            });
                        },
                      "Cancel" : function()
                      {
                          $(this).dialog("close");
                      }
                    },
                    close: function (ev, ui)
                    {
                        $(this).dialog("destroy");
                    }

                });
            });

            // selected student ids
            selected = [];
            // selected students data. to be used in datatables
            students = [];
            $( "input#studentName" ).autocomplete({
                appendTo: "#createGroupModal",
                source: function (request, response) {
                    $.ajax({
                        url: "ajax/studentSearch.php",
                        dataType: "json",
                        data: {
                            studentName: request.term,
                            selectedStudents: selected,
                            section : $('#sectionSelect').val()
                        },
                        success: function (data) {
                            response($.map(data.data, function (item) {

                                return {
                                    label: item.name,
                                    uid: item.uid,
                                    sName: item.sName,
                                    sid: item.sid
                                };


                            }));
                        }
                    });
                },
                search  : function(){$(this).addClass('ui-autocomplete-loading');},
                open    : function(){$(this).removeClass('ui-autocomplete-loading');},
                minLength: 1,
                select: function (event, ui)
                {
                    // push id into selected ids array

                    if(jQuery.isEmptyObject(ui.item))
                        return false;

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
                    return $("<li></li>").data("item.autocomplete", item).append("<a><strong>" + item.label + "</strong> in section <i>"+ item.sName +"</i> -> Semester " + item.sid + "</a>").appendTo(ul);
                else
                    return $("<li></li>").data("item.autocomplete", item).append("<strong> No Results</strong>").appendTo(ul);
            };



            /* new group validator */
            $('form#createGroupForm').validate({
                rules: {
                    newGroupName:
                    {
                        required: true
                    },
                    groupBandwidth :
                    {
                        required : true,
                        number: true,
                        min: 1,
                        max : 2048
                    }
                },
                submitHandler: function (form)
                {
                    var serialized = $('#createGroupForm :input').serialize();
                    console.log(serialized);
                    // send data to server to check for credentials
                    $('#createGroupAjax').html("Please wait while group is being created").dialog({
                        title : 'Create Group',
                        modal: true,
                        width: 300,
                        height: 200,
                        draggable : false,
                        resizable : false
                    });

                    $.ajax({
                        url: 'ajax/createGroup.php',
                        data: {
                            form : serialized,
                            uids : selected,
                            maxb : $('#groupBandwidth').val()
                        },
                        type: 'POST',
                        error: function()
                        {
                        },
                        dataType: 'html',
                        success: function(data)
                        {
                            $('#createGroupAjax').html(data);
                        }
                    });
                }
            });


            /* SELECTED FILES TABLE */
            selectedStudentsTable = $('#selectedStudentsTable').DataTable({
                "displayLength": 25,
                data: students,
                dom: 'Bfrtip',
                deferRender: true,
                select: {
                    style : "os",
                    selector : "td:not(:last-child)"
                },
                buttons:[
                    {
                        "extend": "selectAll"
                    },
                    {
                        "extend": "selectNone"
                    },
                    {
                        "text" : "Remove",
                        "action": function()
                        {

                            // get rows
                            var rows  = selectedStudentsTable.rows('.selected');
                            // loop through every row
                            rows.every( function ( rowIdx, tableLoop, rowLoop ) {
                                var data = this.data();
                                // delete this id from the "selected" students array.
                                var index = selected.indexOf(data.uid);
                                if(index > -1)
                                    selected.splice(index,1);

                                // remove from students data. this rebuilds array without the user id that we're deleting
                                students = students.filter(function(re){
                                    return re.uid !== data.uid;
                                })

                            });
                            //redraw table
                            rows.remove().draw();

                        }
                    }

                ],
                columns: [
                    {
                        title: "Student ID",
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
                        'render': function ( data, type, row )
                        {
                            var check = false;
                            if(selectedStudentsTable.rows().count() <= 0)
                                check = true;

                            return '<input type="radio" id="groupLeader" name="groupLeader" value="' + row.uid +'" checked="' +check+'">';

                        }
                    },
                ],
                columnDefs: [{
                    orderable: false,
                    targets:   [3]
                }],
            });


            deliverablesTable = $('#deliverablesTable').DataTable({
                "processing": true,
                "serverSide": false,
                "displayLength": 25,
                "ajax": {
                    "url": "ajax/adminDeliverableList.php",
                    "type": "POST",
                    "data":
                    {

                    }
                },
                "columns": [
                    {"data": "did"},
                    {"data": "name"},
                    {"data": "startDate"},
                    {"data": "endDate"}
                ]
            });


            /** new deliverable stuff */
            $(document).on('change', '#selectSemesterNewDeliverable', function(){

                var sid = $(this).val();
                if(sid=="" || typeof sid === "undefined")
                    return false;

                $.ajax({
                    url: "ajax/groupsSemester.php",
                    type: "POST",
                    dataType : "json",
                    data : {
                        sid : $(this).val()
                    },
                    success : function(data)
                    {
                        console.log(data);
                        $('#selectGroupsNewDeliverable').find('option')
                            .remove()
                            .end();

                        $.each(data, function (index, value) {
                            $('#selectGroupsNewDeliverable')
                                .append($('<option/>', {
                                value: value.gid,
                                text : value.name
                            }).prop('selected', true));
                        });
                       // $('#selectGroupsNewDeliverable').prop('selected', true);
                    }
                });
            });


            $('#newDeliverableStartDate, #newDeliverableEndDate').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat : "yy-mm-dd"
            });

            /* new deliverable validator */
            $('form#newDeliverableForm').validate({
                rules: {
                    newDeliverableName:
                    {
                        required: true
                    },
                    newDeliverableStartDate :
                    {
                        required : true,
                        date : true
                    },
                    newDeliverableEndDate :
                    {
                        required : true,
                        date : true
                    },
                    selectSemesterNewDeliverable :
                    {
                        required : true
                    }
                },
                messages  :
                {
                    newDeliverableName :
                    {
                        required : "A deliverable name is required"
                    },
                    selectSemesterNewDeliverable :
                    {
                        required : "Please select a valid semester"
                    }
                },
                submitHandler: function (form)
                {
                    var ser = $('form#newDeliverableForm').serialize();
                    var gids = $('#selectGroupsNewDeliverable').val();

                    $('#createDeliverableAjax').html("Please wait while deliverable is being created").dialog({
                        title : 'New Deliverable',
                        modal: true,
                        width: 300,
                        height: 200,
                        draggable : false,
                        resizable : false
                    });

                    $.ajax({
                        url: "ajax/createDeliverable.php",
                        type: "POST",
                        dataType : "html",
                        data : {
                            form : ser,
                            gids : gids

                        },
                        success : function(data)
                        {
                            $('#createDeliverableAjax').html(data);
                        }
                    });
                }
              });


            $('#createNewDeliverable').click(function(){
                $('#newDeliverableModal').dialog({
                    width: 650,
                    height: 550,
                    modal: true,
                    resizable: false,
                    title : 'New Deliverable',
                    buttons :
                    {
                        "Create" : function()
                        {
                            $('#createDeliverable').trigger('click');
                        },
                        "Cancel" : function()
                        {
                            $(this).dialog('close');
                        }


                    },
                    close : function()
                    {
                        $(this).dialog("destroy");
                        $('form#newDeliverableForm')[0].reset();
                    }
                });
            });



            // when edit button is pressed

            $(document).on('click', '#groupEdit', function(){

                var data =  groups.row( $(this).closest('tr') ).data();
                var gid = data.gid;
                console.log(data);
                $('#editGroupModal').dialog({
                   width: 900,
                    height: 800,
                    modal: true,
                    title: data.gName
                });
            });

        });

    </script>
</body>
</html>
