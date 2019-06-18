<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    include_once '../globalfunctions/slottingfunctions.php';
    ?>
    <head>
        <title>To/From Flow Rack</title>
        <link href="js/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css"/>
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


                    <div class="pull-left " style="margin-left: 20px">
                        <label>Report Type:</label>
                        <select class="selectstyle" id="reportsel" name="reportsel" style="width: 175px;padding: 5px; margin-right: 10px;margin-left: 20px">
                            <option value="TOFLOW">TO Flow Rack</option>
                            <option value="FROMFLOW">FROM Flow Rack</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 col-lg-2 col-xl-2 text-center">
                        <button id="loaddata" type="button" class="btn btn-primary" onclick="gettable();">Load Data</button>
                    </div>
                </div>

                <div id="tablecontainer" class="hidden">
                    <table id="ptbtable" class="table table-striped table-bordered" cellspacing="0" style="font-size: 11px; font-family: Calibri;">
                        <thead>
                            <tr>
                                <th>Item Number</th>
                                <th>Location</th>
                                <th>Pick Zone</th>
                                <th>DSLS</th>
                                <th>ADBS</th>
                                <th>Avg. Inv. OH</th>
                                <th>Pick Average</th>
                                <th>Ship Average</th>
                                <th>Current Tier</th>
                                <th>Current Dim Group</th>
                                <th>Sugg. Tier</th>
                                <th>Sugg. Dim Group</th>
                                <th>Sugg. Max</th>
                                <th>Sugg. Min</th>
                                <th>Current Yr. Replen</th>
                                <th>Sugg. Yr. Replen</th>
                            </tr>
                        </thead>
                    </table>
                </div>


                <!--Modal for pick detail data after bay is clicked in datatable-->
                <!-- Complete Action Modal -->
                <div id="itemactioncompletemodal" class="modal fade " role="dialog">
                    <div class="modal-dialog modal-lg">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Bay Pick Detail</h4>
                            </div>

                            <div class="modal-body" id="" style="margin: 50px;">
                                <div id="itemdetailcontainerloading" class="loading col-sm-12 text-center hidden" >
                                    Accessing Live Data.  Wait time up to 1 minute. <img src="../ajax-loader-big.gif"/>
                                </div>
                                <div id="pickdetaildata"></div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>
        </section>


        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});

     function gettable() {

       var reportsel = $('#reportsel').val();

                oTable = $('#ptbtable').dataTable({
                    dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'B><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                    destroy: true,
                    "scrollX": true,
                    "aoColumnDefs": [
                        {
                            "aTargets": [0], // Column to target
                            "mRender": function (data, type, full) {
                                // 'full' is the row's data object, and 'data' is this column's data
                                // e.g. 'full[0]' is the comic id, and 'data' is the comic title
                                    return '<a href="itemquery.php?itemnum=' + full[0] +  '" target="_blank">' + data + '</a>';
                            }
                        }
                    ],
                    'sAjaxSource': "globaldata/flowreport.php?reportsel=" + reportsel,
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
