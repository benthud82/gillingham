<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    ?>
    <head>
        <title>OSS - Max/Min Adjustment</title>
        <?php include_once 'headerincludes.php'; ?>
    </head>

    <body style="">
        <!--include horz nav php file-->
        <?php include_once 'horizontalnav.php'; ?>
        <!--include vert nav php file-->
        <?php include_once 'verticalnav.php'; ?>


        <section id="content"> 
            <section class="main padder"> 
                <div class="row" style="padding-bottom: 30px;padding-top: 75px;"> 
                    <div class="pull-left " style="margin-left: 20px">
                        <label>Report Type:</label>
                        <select class="selectstyle" id="reportsel" name="reportsel" style="width: 175px;padding: 5px; margin-right: 10px;margin-left: 20px">
                            <option value="MAX">Max Adjustment</option>
                            <!--<option value="MIN">Min Adjustment</option>-->
                        </select>
                    </div>
                    <div class="pull-left  " >
                        <div class="col-md-12">
                            <label class="" style=" float: left;">Include Already Reviewed?:</label>
                            <div class="switch-field"style="padding-left: 20px;">
                                <input type="radio" id="switch_left" name="switch_2" value="yes" />
                                <label for="switch_left" class="greenbackground" data-toggle="tooltip" data-title="Include max/min opps already reviewed?" data-placement="bottom">Yes</label>
                                <input type="radio" id="switch_right" name="switch_2" value="no" checked />
                                <label id="nolabel" for="switch_right" data-toggle="tooltip" data-title="Include max/min opps already reviewed?" data-placement="bottom">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 col-lg-2 col-xl-2 text-center">
                        <button id="loaddata" type="button" class="btn btn-primary" onclick="gettable();">Load Data</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <!--Vector map error table.  -->
                        <section class="panel hidewrapper" id="sec_baylocerror" style="margin-bottom: 50px; margin-top: 20px;"> 
                            <header class="panel-heading bg bg-inverse h2">Location Max/Min Adjustment Opps.</header>
                            <div id="tbl_baylocerror" class="panel-body">

                                <div id="tablecontainer" class="hidden">
                                    <table id="ptbtable" class="table table-bordered" cellspacing="0" style="font-size: 11px; font-family: Calibri;">
                                        <thead>
                                            <tr>
                                                <th>Mark as OK</th>
                                                <th>Whse</th>
                                                <th>Item</th>
                                                <th>Location</th>
                                                <th>Current Max</th>
                                                <th>Current Min</th>
                                                <th>Current Yearly Replens</th>
                                                <th>Sugg. Max</th>
                                                <th>Sugg. Min</th>
                                                <th>New Yearly Replens</th>
                                                <th>Yearly Replen Reduction</th>
                                                <th>Reviewed?</th>
                                                <th data-toggle='tooltip' title='Click "SHOW COMMENTS" to view Comments' data-placement='top' data-container='body'>Comments?</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <!--Add comment modal-->
                <?php include_once 'globaldata/addcommentmodal.php'; ?>


                <!-- Mark as Reviewed Modal -->
                <div id="reviewmodal" class="modal fade " role="dialog">
                    <div class="modal-dialog modal-lg">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Mark as Reviewed</h4>
                            </div>
                            <form class="form-horizontal" id="postreview">
                                <div class="modal-body">
                                    <div class="form-group hidden">
                                        <input type="text" name="itemnummodal" id="itemnummodal" class="form-control" tabindex="1" />
                                    </div>
                                    <div class="form-group hidden">
                                        <input type="text" name="locationmodal" id="locationmodal" class="form-control" tabindex="1" />
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Mark item as reviewed: </label>
                                        <div class="col-md-9">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-lg pull-left" name="addreview" id="addreview">Yes!</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!--Modal to view all comments for item-->
                <div id="allcommentsmodal" class="modal fade " role="dialog">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Item Comments</h4>
                            </div>

                            <div class="modal-body" id="" style="margin: 50px;">
                                <div id="commentdetaildata"></div>
                            </div>

                        </div>
                    </div>
                </div>


                <!--Include acutal move modal-->
                <?php include_once 'globaldata/actualmovemodal.php'; ?>

            </section>
        </section>


        <script>
            $(document).on("click touchstart", ".moveauditclick", function (e) {
                $('#actualmovemodal').modal('toggle');
                $('#itemdetailcontainerloading').toggleClass('hidden');
                $('#divtablecontainer').addClass('hidden');
                var lseorcse = 'moveauditclicklse';
                var itemcode = $(this).closest('tr').find('td:eq(2)').text();

                var userid = $('#userid').text();
                debugger;
                $.ajax({
                    url: 'globaldata/moveaudithistory.php',
                    data: {itemcode: itemcode, userid: userid, lseorcse: lseorcse},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $('#itemdetailcontainerloading').toggleClass('hidden');
                        $('#divtablecontainer').removeClass('hidden');
                        $("#movedetaildata").html(ajaxresult);
                    }
                });
            });


            function gettable() {

                $('#tablecontainer').addClass('hidden');
                var userid = $('#userid').text();
                var reportsel = $('#reportsel').val();

                if (document.getElementById('switch_left').checked) {
                    var includeaudit = 1;
                } else {
                    var includeaudit = 0;
                }

                oTable = $('#ptbtable').dataTable({
                    dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'B><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                    destroy: true,
                    "order": [[10, "desc"]],
                    "scrollX": true,
                    "fnCreatedRow": function (nRow, aData, iDataIndex) {
                        $('td:eq(0)', nRow).append("<div class='text-center'><i class='fa fa-check reviewclick' style='cursor: pointer;     margin-right: 5px;' data-toggle='tooltip' data-title='Mark as reviewed?' data-placement='top' data-container='body' ></i><i id='" + aData[2] + "' class='fa fa-comment addcomment' style='cursor: pointer;' data-toggle='tooltip' data-title='Add Comment' data-placement='top' data-container='body' ></i> </div>");
                    },
                    "rowCallback": function (row, data, index) {
                        if (data[11] !== null) {
                            $(row).addClass('recentcomment');
                        }
                        if (data[12] === 'SHOW COMMENTS') {  //add class to show comment so modal can be displayed
                            $('td:eq(12)', row).addClass("showallcomments");
                        }
                        $('td:eq(6)', row).addClass("moveauditclick");
                    },
                    "aoColumnDefs": [
                        {
                            "aTargets": [2], // Column to target
                            "mRender": function (data, type, full) {
                                // 'full' is the row's data object, and 'data' is this column's data
                                // e.g. 'full[0]' is the comic id, and 'data' is the comic title
                                return '<a href="itemquery.php?itemnum=' + full[2] + '&userid=' + userid + '" target="_blank">' + data + '</a>';
                            }
                        }
                    ],
                    'sAjaxSource': "globaldata/maxmin.php?userid=" + userid + "&reportsel=" + reportsel + '&includeaudit=' + includeaudit,
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5'
                    ]
                });
                $('#tablecontainer').removeClass('hidden');

            }


            //Toggle review modal
            $(document).on("click", ".reviewclick", function (e) {
                $('#reviewmodal').modal('toggle');
                $('#itemnummodal').val($(this).closest('tr').find('td:eq(2)').text());
                $('#locationmodal').val($(this).closest('tr').find('td:eq(3)').text());
            });

            //mark item as reviewed through mysql post
            $(document).on("click", "#addreview", function (event) {
                event.preventDefault();
                var itemnum = $('#itemnummodal').val();
                var location = $('#locationmodal').val();
                var userid = $('#userid').text();
                debugger;
                var formData = 'itemnum=' + itemnum + '&userid=' + userid + '&location=' + location;
                $.ajax({
                    url: 'formpost/postmarkreviewed.php',
                    type: 'POST',
                    data: formData,
                    success: function (result) {
                        $('#reviewmodal').modal('hide');
                        gettable();
                    }
                });

            });

            //if all comments for an item is wanted to be viewed through modal
            $(document).on("click", ".showallcomments", function (event) {
                $('#allcommentsmodal').modal('toggle');
                $('#commentmodal_container').addClass('hidden');
                var itemnum = $(this).closest('tr').find('td:eq(2)').text();
                var userid = $('#userid').text();
                $.ajax({
                    url: 'globaldata/commentsbyitem.php',
                    data: {itemnum: itemnum, userid: userid},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $('#commentmodal_container').removeClass('hidden');
                        $("#commentdetaildata").html(ajaxresult);
                    }
                });

            });

        </script>

        <!--Personal Script for showing and completing item comments-->
        <script src="js/itemcomments.js" type="text/javascript"></script>


        <script>
            $("#reports").addClass('active');
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});
        </script>
    </body>
</html>
