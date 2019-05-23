<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    ?>
    <head>
        <title>OSS - Walk Time</title>
        <?php include_once 'headerincludes.php'; ?>
    </head>

    <body style="">
        <!--include horz nav php file-->
        <?php include_once 'horizontalnav.php'; ?>
        <!--include vert nav php file-->
        <?php include_once 'verticalnav.php'; ?>


        <section id="content"> 
            <section class="main padder"> 
                <div class="row" style="padding-bottom: 25px; padding-top: 75px;"> 

                    <div class="pull-left  col-lg-3 col-xl-2" >
                        <select class="selectstyle" id="reportsel" name="reportsel" style="width: 250px;padding: 5px; margin-right: 10px;">
                            <option value=highwalk>High Walk Times</option>
                            <option value=negativewalk>Negative Walk Times</option>
                        </select>
                    </div>
                    <div class="col-lg-2" >
                        <button id="loaddata" type="button" class="btn btn-primary" onclick="gettable();" style="margin-bottom: 5px;">Load Data</button>
                    </div>
                </div>





                <div id="tablecontainer" class="hidden">
                    <table id="ptbtable" class="table table-striped table-bordered" cellspacing="0" style="font-size: 11px; font-family: Calibri;">
                        <thead>
                            <tr>
                                <th>Whse</th>
                                <th>Item</th>
                                <th>Location</th>
                                <th>Additional Feet</th>
                                <th>DSLS</th>
                                <th>ADBS</th>
                                <th>Current Grid5</th>
                                <th>Current Depth</th>
                                <th>Current Bay</th>
                                <th>Suggested Grid5</th>
                                <th>Suggested Depth</th>
                                <th>Suggested Bay</th>
                                <th>Suggested Max</th>
                                <th>Suggested Min</th>
                                <th>Implied Replen</th>
                                <th>Currrent Replen</th>
                                <th>Daily Pick</th>
                                <th>Daily Unit</th>
                                <th>Replen Score</th>
                                <th>Walk Score</th>
                                <th>Total Score</th>
                            </tr>
                        </thead>
                    </table>
                </div>


                <!--Include acutal move modal-->
                <?php include_once 'globaldata/actualmovemodal.php'; ?>
                <?php include_once 'globaldata/actualpickmodal.php'; ?>

            </section>
        </section>


        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});

            function gettable() {

                $('#tablecontainer').addClass('hidden');
                var userid = $('#userid').text();
                var reportsel = $('#reportsel').val();

                $(document).on("click touchstart", ".moveauditclick", function (e) {
                    $('#actualmovemodal').modal('toggle');
                    $('#itemdetailcontainerloading').toggleClass('hidden');
                    $('#divtablecontainer').addClass('hidden');
                    var lseorcse = 'moveauditclicklse';
                    var itemcode = $(this).closest('tr').find('td:eq(1)').text();

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

                $(document).on("click touchstart", ".pickauditclick", function (e) {
                    $('#actualpickmodal').modal('toggle');
                    $('#itemdetailcontainerloading_pick').toggleClass('hidden');
                    $('#divtablecontainer_pick').addClass('hidden');
                    var lseorcse = 'pickauditclicklse';
                    var itemcode = $(this).closest('tr').find('td:eq(1)').text();

                    var userid = $('#userid').text();
                    debugger;
                    $.ajax({
                        url: 'globaldata/pickaudithistory.php',
                        data: {itemcode: itemcode, userid: userid, lseorcse: lseorcse},
                        type: 'POST',
                        dataType: 'html',
                        success: function (ajaxresult) {
                            $('#itemdetailcontainerloading_pick').toggleClass('hidden');
                            $('#divtablecontainer_pick').removeClass('hidden');
                            $("#pickdetaildata").html(ajaxresult);
                        }
                    });
                });

                oTable = $('#ptbtable').dataTable({
                    dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'B><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                    destroy: true,
                    "scrollX": true,
                    "aoColumnDefs": [
                        {
                            "aTargets": [1], // Column to target
                            "mRender": function (data, type, full) {
                                // 'full' is the row's data object, and 'data' is this column's data
                                // e.g. 'full[0]' is the comic id, and 'data' is the comic title
                                return '<a href="itemquery.php?itemnum=' + full[1] + '&userid=' + userid + '" target="_blank">' + data + '</a>';
                            }
                        }
                    ],
                    "rowCallback": function (row, data, index) {
                        $('td:eq(14)', row).addClass("moveauditclick");
                        $('td:eq(15)', row).addClass("pickauditclick");
                    },
                    'sAjaxSource': "globaldata/walktimedata.php?userid=" + userid + "&reportsel=" + reportsel,
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5'
                    ]
                });
                $('#tablecontainer').removeClass('hidden');

            }
        </script>

        <script>
            $("#reports").addClass('active');

        </script>
    </body>
</html>
