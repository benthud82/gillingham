<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    include_once 'globalfunctions/slottingfunctions.php';
    $var_userid = $_SESSION['MYUSER'];
    $whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
    $whssql->execute();
    $whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

    $var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];


    $datesqlall = $conn1->prepare("SELECT DISTINCT
                                    optbayhist_date as dates
                                FROM
                                    gillingham.optimalbay_hist

                                ORDER BY optbayhist_date;");
    $datesqlall->execute();
    $datesqlallarray = $datesqlall->fetchAll(pdo::FETCH_ASSOC);
    $ids = array_column($datesqlallarray, 'dates');
    $includedates = '["' . implode('" , "', $ids) . '"]';


    $datesql = $conn1->prepare("SELECT 
                                max(optbayhist_date) as recentdate
                            FROM
                                gillingham.optimalbay_hist");
    $datesql->execute();
    $datesqlarary = $datesql->fetchAll(pdo::FETCH_ASSOC);
    $today = $datesqlarary[0]['recentdate'];
    ?>
    <head>
        <title>OSS - Heat Map</title>
        <link href="js/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css"/>
        <?php include_once 'headerincludes.php'; ?>

        <script src="js/svg-pan-zoom.js" type="text/javascript"></script>

        <style>
            rect {transition: .6s fill; opacity: 1 !important; cursor: pointer;}
            rect:hover {opacity: 1 !important;  transition: .4s !important}
            .borderedcontainer{
                border: 1px dashed black;
                height: 600px;
                padding: 0px;
                background: white;
            }
            text {cursor: default;}

            html{
                height: 100%;
                margin: 0;
                padding: 0;
            }
        </style>


    </head>
    <body style="">
        <!--include horz nav php file-->
        <?php include_once 'horizontalnav.php'; ?>
        <!--include vert nav php file-->
        <?php include_once 'verticalnav.php'; ?>



        <section id="content"> 
            <section class="main padder"> 
                <div class="row" style="padding-bottom: 25px;padding-top: 75px;"> 
                    <div class="col-md-3 col-lg-2 col-xl-2">
                        <div class="pull-left" style="margin-left: 15px" >
                            <label>Zone: </label>
                            <select class="selectstyle" id="zone" name="tier">
                                <option value="L%">Loose</option>
                                <!--<option value="C%">Case</option>-->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-xl-2">
                        <div class="pull-left" style="margin-left: 15px" >
                            <label>Map Type: </label>
                            <select class="selectstyle" id="maptype" name="maptype" style="width: 150px;">
                                <!--<option value="both">Pick and Replen Map</option>-->
                                <option value="pickall">Pick Map</option>
                                <option value="replen">Replen Map</option>
                                <!--<option value="pickhigh">Pick Map - HIGHS</option>-->
                                <!--<option value="replen">Replen Map</option>-->
                            </select>
                        </div>
                    </div>

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


                <div id="itemdetailcontainerloading" class="loading col-sm-12 text-center hidden" >
                    Data Loading <img src="../ajax-loader-big.gif"/>
                </div>
                <div class="row">

                    <!--Map Container-->
                    <div class="col-lg-9">
                        <div id="svgcontainer" class=" hidden" style="margin: 15px;"></div>
                    </div>
                    <!--Item Detail Container-->
                    <div class="col-sm-12 col-lg-3">
                        <div id="clickedloccontainer" class="hidden" style="margin: 15px;"></div>
                    </div>
                </div>

            </section>
        </section>

        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});
            $('#summarycontainter').hide();


            function gettable() {
                $('#itemdetailcontainerloading').removeClass('hidden');
                $('#svgcontainer').addClass('hidden');
                $('#clickedloccontainer').addClass('hidden');
                $('#datecontainer').addClass('hidden');
                var userid = $('#userid').text();
                var zonesel = $('#zone').val();
                var mapsel = $('#maptype').val();
                var datesel = $('#startfiscal').val();

                debugger;
                if (typeof $('#building').val() !== 'undefined') {
                    var building = $('#building').val();
                } else {
                    var building = 1;
                }


                var aislelabel = $("#aislelabel").prop('checked');

                $.ajax({
                    url: 'globaldata/heatmapdata_' + mapsel + '.php',
                    data: {zonesel: zonesel, userid: userid, aislelabel: aislelabel, building: building, datesel: datesel},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $('#itemdetailcontainerloading').addClass('hidden');
                        $('#svgcontainer').removeClass('hidden');
                        $('#datecontainer').removeClass('hidden');

                        $("#svgcontainer").html(ajaxresult);
                        window.zoomTiger = svgPanZoom('#svg2', {
                            zoomEnabled: true,
                            controlIconsEnabled: true,
                            fit: true,
                            center: true
                        });

                    }
                });
            }

            $(document).on("click touchstart", ".clickablesvg", function (e) {

                var baycode = this.id;
                var userid = $('#userid').text();
                var mapsel = $('#maptype').val();
                debugger;
                $.ajax({
                    url: 'globaldata/clickedlocationdetail.php',
                    data: {baycode: baycode, userid: userid, mapsel: mapsel},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $('#clickedloccontainer').removeClass('hidden');
                        $("#clickedloccontainer").html(ajaxresult);
                    }
                });
            });

            function callitemdetail(clickedcell) {  //on itemdetail arrow click, go to new page with itemdetail for slotting
                var itemnum = clickedcell;
                var userid = $('#userid').text();
                var url = "itemdetail.php?itemnum=" + itemnum + "&userid=" + userid;
                //                $(location).attr('href', url);
                window.open(url, '_blank');
            }
        </script>

        <script>
            $("#heatmap").addClass('active');

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

        </script>


    </body>
</html>
