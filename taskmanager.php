<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    ?>
    <head>
        <title>Task Manager</title>
        <?php include_once 'headerincludes.php'; ?>
    </head>

    <body style="">
        <!--include horz nav php file-->
        <?php include_once 'horizontalnav.php'; ?>
        <!--include vert nav php file-->
        <?php include_once 'verticalnav.php'; ?>


        <section id="content"> 
            <section class="main padder"> 
                <div class="row"  style="padding-top:  75px;padding-left: 10px;padding-bottom: 15px;">
                    <h2>Task Manager</h4>
                </div>
                <div class="" id="alltablecontainer">
                    <div class="row">

                        <!--Open tasks assigned to me-->
                        <div class="col-md-12 col-lg-12 col-xl-6 ">
                            <div class="hidewrapper">
                                <section class="panel portlet-item" style="opacity: 1; z-index: 0;"> 
                                    <header class="panel-heading bg-danger" style="font-size: 24px;"> My Open Tasks  </header> 
                                    <div class="panel-body">
                                        <div id="mytasktablecontainer" class="">
                                            <table id="mytasktable" class="table table-bordered table-striped" cellspacing="0" style="font-size: 14px; font-family: Calibri;">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Assigned By</th>
                                                        <th>Item Code</th>
                                                        <th>Location</th>
                                                        <th>Assigned Date</th>
                                                        <th>Comment</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>

                        <!--All open tasks-->
                        <div class="col-md-12 col-lg-12 col-xl-6 ">
                            <div class="hidewrapper">
                                <section class="panel portlet-item" style="opacity: 1; z-index: 0;"> 
                                    <header class="panel-heading bg-info" style="font-size: 24px;"> All Open Tasks  </header> 
                                    <div class="panel-body">
                                        <div id="alltasktablecontainer" class="">
                                            <table id="alltasktable" class="table table-bordered table-striped" cellspacing="0" style="font-size: 14px; font-family: Calibri;">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Assigned By</th>
                                                        <th>Assigned To</th>
                                                        <th>Item Code</th>
                                                        <th>Location</th>
                                                        <th>Assigned Date</th>
                                                        <th>Comment</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div> <!--end of row-->
                    <div class="row">
                        <!--My completed Tasks-->
                        <div class="col-md-12 col-lg-12 col-xl-6 ">
                            <div class="hidewrapper">
                                <section class="panel portlet-item" style="opacity: 1; z-index: 0;"> 
                                    <header class="panel-heading bg-success" style="font-size: 24px;"> My Completed Tasks  </header> 
                                    <div class="panel-body">
                                        <div id="mycompletedtablecontainer" class="">
                                            <table id="mycompletedtable" class="table table-bordered table-striped" cellspacing="0" style="font-size: 14px; font-family: Calibri;">
                                                <thead>
                                                    <tr>
                                                        <th>Assigned By</th>
                                                        <th>Item Code</th>
                                                        <th>Completed Date</th>
                                                        <th>Assigned Comment</th>
                                                        <th>Completed Comment</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <!--My assigned tasks status-->
                        <div class="col-md-12 col-lg-12 col-xl-6 ">
                            <div class="hidewrapper">
                                <section class="panel portlet-item" style="opacity: 1; z-index: 0;"> 
                                    <header class="panel-heading bg-inverse" style="font-size: 24px;"> My Assigned Task Status </header> 
                                    <div class="panel-body">
                                        <div id="mystatustablecontainer" class="">
                                            <table id="mystatustable" class="table table-bordered table-striped" cellspacing="0" style="font-size: 14px; font-family: Calibri;">
                                                <thead>
                                                    <tr>
                                                        <th>Assigned To</th>
                                                        <th>Item Code</th>
                                                        <th>Assigned Date</th>
                                                        <th>Assigned Comment</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>

                    </div> <!--end of row-->
                </div><!--end of  all table hidden container-->



                <!-- Complete Action Modal -->
                <div id="itemactioncompletemodal" class="modal fade " role="dialog">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Complete Item Action</h4>
                            </div>
                            <form class="form-horizontal" id="postitemactioncomplete">
                                <div class="modal-body">
                                    <div class="col-md-3 hidden">
                                        <!--ID of the assigned comment to pass to postcompletetask.php-->
                                        <input type="text" name="assid" id="assid" class="form-control" placeholder="" tabindex="0"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Enter Action Completed: </label>
                                        <div class="col-md-9">
                                            <textarea rows="3" placeholder="" class="form-control" id="commentmodal_action" name="commentmodal_action" tabindex="1"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-lg pull-left" name="btn_completeaction" id="btn_completeaction">Complete Item Action</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </section>
        </section>


        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});
            $(document).ready(function () {

                var userid = $('#userid').text();
                //fill mytasktable datatable
                oTable1 = $('#mytasktable').DataTable({
                    dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                    destroy: true,
                    "order": [[4, "asc"]],
                    "scrollX": true,
                    'sAjaxSource': "globaldata/taskmanager_myopentasks.php?userid=" + userid,
                    "fnCreatedRow": function (nRow, aData, iDataIndex) {
                        $('td:eq(5)', nRow).append("<div class='text-center'><i  class='fa fa-times-circle completeaction_mytasks' style='cursor: pointer;' data-toggle='tooltip' data-title='Mark as Complete' data-placement='top' data-container='body' ></i> </div>");
                    },
                    "aoColumnDefs": [
                        {
                            "targets": [0],
                            "visible": false,
                            "searchable": false
                        },
                        {
                            "aTargets": [2], // Column to target
                            "mRender": function (data, type, full) {
                                // 'full' is the row's data object, and 'data' is this column's data
                                // e.g. 'full[0]' is the comic id, and 'data' is the comic title
                                return '<a href="itemquery.php?itemnum=' + full[2] + '&userid=' + userid + '" target="_blank">' + data + '</a>';
                            }
                        }
                    ]
                });
                //fill alltasktable datatable
                oTable2 = $('#alltasktable').DataTable({
                    dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                    destroy: true,
                    "order": [[5, "asc"]],
                    "scrollX": true,
                    'sAjaxSource': "globaldata/taskmanager_allopentasks.php?userid=" + userid,
                    "fnCreatedRow": function (nRow, aData, iDataIndex) {
                        $('td:eq(6)', nRow).append("<div class='text-center'><i class='fa fa-times-circle completeaction_alltasks' style='cursor: pointer;' data-toggle='tooltip' data-title='Mark as Complete' data-placement='top' data-container='body' ></i> </div>");
                    },
                    "aoColumnDefs": [
                        {
                            "targets": [0],
                            "visible": false,
                            "searchable": false
                        },
                        {
                            "aTargets": [3], // Column to target
                            "mRender": function (data, type, full) {
                                // 'full' is the row's data object, and 'data' is this column's data
                                // e.g. 'full[0]' is the comic id, and 'data' is the comic title
                                return '<a href="itemquery.php?itemnum=' + full[3] + '&userid=' + userid + '" target="_blank">' + data + '</a>';
                            }
                        }
                    ]
                });
                //fill mycompletedtable datatable
                oTable3 = $('#mycompletedtable').DataTable({
                    dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                    destroy: true,
                    "order": [[3, "desc"]],
                    "scrollX": true,
                    'sAjaxSource': "globaldata/taskmanager_mycompletedtasks.php?userid=" + userid,
                    "aoColumnDefs": [
                        {
                            "aTargets": [1], // Column to target
                            "mRender": function (data, type, full) {
                                // 'full' is the row's data object, and 'data' is this column's data
                                // e.g. 'full[0]' is the comic id, and 'data' is the comic title
                                return '<a href="itemquery.php?itemnum=' + full[1] + '&userid=' + userid + '" target="_blank">' + data + '</a>';
                            }
                        }
                    ]
                });
                //fill mystatustable datatable
                oTable4 = $('#mystatustable').DataTable({
                    dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                    destroy: true,
                    "order": [[2, "desc"]],
                    "scrollX": true,
                    'sAjaxSource': "globaldata/taskmanager_mystatustasks.php?userid=" + userid,
                    "aoColumnDefs": [
                        {
                            "aTargets": [1], // Column to target
                            "mRender": function (data, type, full) {
                                // 'full' is the row's data object, and 'data' is this column's data
                                // e.g. 'full[0]' is the comic id, and 'data' is the comic title
                                return '<a href="itemquery.php?itemnum=' + full[1] + '&userid=' + userid + '" target="_blank">' + data + '</a>';
                            }
                        }
                    ]
                });
            });
            //modal to show complete item action from mytaskstable
            $(document).on("click", ".completeaction_mytasks", function (e) {
                debugger;
                var tr = $(this).closest("tr");
                var rowindex = tr.index();
                var assigntask_id = oTable1.row(rowindex).data()[0];
                $('#assid').val(assigntask_id);
                $('#itemactioncompletemodal').modal('toggle');
            });
            //modal to show complete item action from alltasks table
            $(document).on("click", ".completeaction_alltasks", function (e) {
                debugger;
                var tr = $(this).closest("tr");
                var rowindex = tr.index();
                var assigntask_id = oTable2.row(rowindex).data()[0];
                $('#assid').val(assigntask_id);
                $('#itemactioncompletemodal').modal('toggle');
            });
            //complete item action to post to mysql
            $(document).on("click", "#btn_completeaction", function (event) {
                event.preventDefault();
                var commentmodal = $('#commentmodal_action').val();
                var assigntask_id = $('#assid').val();
                var formData = 'commentmodal=' + commentmodal + '&assigntask_id=' + assigntask_id;
                $.ajax({
                    url: 'formpost/itemactioncomplete.php',
                    type: 'POST',
                    data: formData,
                    success: function (result) {
                        $('#itemactioncompletemodal').modal('hide');
                        location.reload();
                    }
                });
            });
        </script>
        <script>
            $("#taskmanager").addClass('active');
        </script>
    </body>
</html>


