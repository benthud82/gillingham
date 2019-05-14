<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    include_once '../globalfunctions/slottingfunctions.php';

    $var_whse = 'GB0001';


    $datesqlall = $conn1->prepare("SELECT DISTINCT
                                        picksbybay_DATE as dates
                                    FROM
                                        gillingham.picksbybay
                                    ORDER BY picksbybay_DATE;");
    $datesqlall->execute();
    $datesqlallarray = $datesqlall->fetchAll(pdo::FETCH_ASSOC);
    $ids = array_column($datesqlallarray, 'dates');
    $includedates = '["' . implode('" , "', $ids) . '"]';


    $datesql = $conn1->prepare("SELECT 
                                    max(picksbybay_DATE) as recentdate
                                FROM
                                    gillingham.picksbybay;");
    $datesql->execute();
    $datesqlarary = $datesql->fetchAll(pdo::FETCH_ASSOC);
    $today = $datesqlarary[0]['recentdate'];
    ?>
    <head>
        <title>OSS - Picks by Bay</title>
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

                    <div class="col-md-3 col-xl-2" style="">
                        <div class="form-group">
                            <label>Date:</label>
                            <input name="startfiscal" id="startfiscal" class="selectstyle" style="cursor: pointer; max-width: 120px;" value="<?php echo date("m/d/Y", strtotime($today)); ?>"/>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 col-lg-2 col-xl-2 text-center">
                        <button id="loaddata" type="button" class="btn btn-primary" onclick="gettable();">Load Data</button>
                    </div>
                </div>

                <div id="tablecontainer" class="hidden">
                    <table id="ptbtable" class="table table-striped table-bordered" cellspacing="0" style="font-size: 11px; font-family: Calibri;">
                        <thead>
                            <tr>
                                <th data-toggle='tooltip' title='Click on bay for detail' data-placement='top' data-container='body'>Bay</th>
                                <th>Total Picks</th>
                                <th>Bay Walk Meters</th>
                                <th>Total Walk Meters</th>
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


            function gettable(datefromurl) {
                $('#tablecontainer').addClass('hidden');

                if (typeof datefromurl !== 'undefined') {
                    var datesel = datefromurl;
                } else {
                    var datesel = $('#startfiscal').val();
                }



                oTable = $('#ptbtable').dataTable({
                    dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'B><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                    destroy: true,
                    "scrollX": true,
                    "order": [[3, "desc"]],
                    "aoColumnDefs": [
                        {
                            "aTargets": [0], // Column to target
                            "mRender": function (data, type, full) {
                                // 'full' is the row's data object, and 'data' is this column's data
                                // e.g. 'full[0]' is the comic id, and 'data' is the comic title
                                return '<span class="bayclick" style="cursor: pointer;">' + data + '</span>';  //add class of bay click to toggle modal with actual pick detail for clicked bay
                            }
                        }
                    ],
                    'sAjaxSource': "globaldata/picksbybaydata.php?datesel=" + datesel,
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5'
                    ]
                });
                $('#tablecontainer').removeClass('hidden');
                if (typeof datefromurl !== 'undefined') {
                    filldatevalue(datefromurl);  //file the item input field
                    cleanurl();  //clean the URL of post data
                }
            }
        </script>

        <script>
            $("#reports").addClass('active');

            //datepicker initialization and function to only show available dates from mysql table
            var availableDates = <?php echo $includedates; ?>;
            function available(date) {
                ymd = date.getFullYear() + "-" + ('0' + (date.getMonth() + 1)).slice(-2) + "-" + ('0' + date.getDate()).slice(-2);
                if ($.inArray(ymd, availableDates) !== -1) {
                    return [true, "", "Available"];
                } else {
                    return [false, "", "Not Available"];
                }
            }
            $('#startfiscal').datepicker({beforeShowDay: available});


            $(document).on("click touchstart", ".bayclick", function (e) {
                $('#itemactioncompletemodal').modal('toggle');
                $('#itemdetailcontainerloading').toggleClass('hidden');
                $('#divtablecontainer').addClass('hidden');
                var baycode = $(this).text();
                var datesel = $('#startfiscal').val();
                debugger;
                $.ajax({
                    url: 'globaldata/pickdetaildatabybay.php',
                    data: {baycode: baycode, datesel: datesel},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $('#itemdetailcontainerloading').toggleClass('hidden');
                        $('#divtablecontainer').removeClass('hidden');
                        $("#pickdetaildata").html(ajaxresult);
                    }
                });
            });

            //is date in url to auto load data
            $(document).ready(function () {
                if (window.location.href.indexOf("date") > -1) {
                    debugger;
                    //Place this in the document ready function to determine if there is search variables in the URL.  
                    //Must clean the URL after load to prevent looping
                    var datefromurl = GetUrlValue('date');

                    gettable(datefromurl);  //pass the 
                }
            });


            function GetUrlValue(VarSearch) {  //parse URL to pull variable defined
                debugger;
                var SearchString = window.location.search.substring(1);
                var VariableArray = SearchString.split('&');
                for (var i = 0; i < VariableArray.length; i++) {
                    var KeyValuePair = VariableArray[i].split('=');
                    if (KeyValuePair[0] === VarSearch) {
                        return KeyValuePair[1];
                    }
                }
            }

            function cleanurl() { //clean the URL if called from another page
                var clean_uri = location.protocol + "//" + location.host + location.pathname;
                window.history.replaceState({}, document.title, clean_uri);
            }

            function filldatevalue(datefromurl) {  //fill item input text
                document.getElementById("startfiscal").value = datefromurl;
            }

        </script>
    </body>
</html>
