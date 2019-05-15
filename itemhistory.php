<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
     include_once 'connection/NYServer.php';
    ?>
    <head>
        <title>OSS - Item Score History</title>
        <?php include_once 'headerincludes.php'; ?>
    </head>

    <body style="">
        <!--include horz nav php file-->
        <?php include_once 'horizontalnav.php'; ?>
        <!--include vert nav php file-->
        <?php include_once 'verticalnav.php'; ?>


        <section id="content"> 
            <section class="main padder"> 
                <h2 style="padding-bottom: 0px;padding-top: 75px;">Item Score History</h2>
                <div class="row" style="padding-bottom: 25px;"> 
                    <div class="col-md-3 col-sm-3 col-xs-12 col-lg-2 col-xl-2 text-center">
                        <label>Item:</label>
                        <input name='itemnum' class='selectstyle' id='itemnum'/>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 col-lg-2 col-xl-2 text-center">
                        <button id="loaddata" type="button" class="btn btn-primary" onclick="gettable();" >Load Data</button>
                    </div>
                </div>
                <div id="hdr_stats" class="col-md-12"> </div>

                <div id="masterhider" class="hidden">
                    <div id="gotoitemquery"></div>
                    <div id="itemtrend"></div>

                    <section class="panel hidewrapper" id="tbl_historicalscores" style="margin-bottom: 50px; margin-top: 20px;"> 
                        <header class="panel-heading bg bg-inverse h2">Table - Historical Item Scores<i class="fa fa-close pull-right closehidden" style="cursor: pointer;" id="close_tblhistoricalscores"></i><i class="fa fa-chevron-up pull-right clicktotoggle-chevron" style="cursor: pointer;"></i></header>
                        <div id="tbl_historicalscores" class="panel-body">
                            <div id="tablecontainer" class="">
                                <table id="ptbtable" class="table table-bordered" cellspacing="0" style="font-size: 11px; font-family: Calibri;">
                                    <thead>
                                        <tr>
                                            <th>Record Date</th>
                                            <th>Location</th>
                                            <th>Curr. Tier</th>
                                            <th>Sugg. Tier</th>
                                            <th>Curr. Grid5</th>
                                            <th>Sugg. Grid5</th>
                                            <th>Sugg. Depth</th>
                                            <th>Sugg. Slot Qty</th>
                                            <th>Sugg. Max</th>
                                            <th>Curr. Moves</th>
                                            <th>Sugg. Moves</th>
                                            <th>Avg Daily Pick</th>
                                            <th>Avg Daily Unit</th>
                                            <th>Curr. Total Score</th>
                                            <th>Opt. Total Score</th>
                                            <th>Curr. Walk Score</th>
                                            <th>Opt. Walk Score</th>
                                            <th>Curr. Replen Score</th>
                                            <th>Opt. Replen Score</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </section>


                    <!--Historical Scores Graph-->
                    <section class="panel hidewrapper" id="graph_historicalscores" style="margin-bottom: 50px; margin-top: 20px;"> 
                        <header class="panel-heading bg bg-inverse h2">Graph - Historical Item Scores<i class="fa fa-close pull-right closehidden" style="cursor: pointer;" id="close_historicalscores"></i><i class="fa fa-chevron-up pull-right clicktotoggle-chevron" style="cursor: pointer;"></i></header>
                        <div id="historicalscores" class="panel-body" style="background: #efefef">
                            <div id="chartpage_historicalscores"  class="page-break" style="width: 100%">
                                <div id="charts padded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="alert alert-success" style="font-size: 100%;"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <i class="fa fa-arrow-up fa-lg"></i><span> Positive improvement indicated by <strong>upward</strong> trending graph. </span></div>
                                        </div>
                                    </div>
                                    <div id="container_historicalscores" class="dashboardstyle printrotate"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!--masterhider close-->
                </div> 


            </section>
        </section>


        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});
            $('#itemdetailcontainer').hide();
            $('#itemsettingscontainer').hide();

            function gettable(itemnumpost) {
                $('#masterhider').addClass('hidden');

                if (typeof itemnumpost !== 'undefined') {
                    var itemnum = itemnumpost;
                    var userid = $('#userid').text();

                } else {
                    var itemnum = $('#itemnum').val();
                    var userid = $('#userid').text();
                }

                //Build itemquery link
                $.ajax({
                    url: 'globaldata/itemquerylink.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {userid: userid, itemnum: itemnum},
                    success: function (result) {
                        $("#gotoitemquery").html(result);
                    }
                });

                //Item score history table
                oTable = $('#ptbtable').dataTable({
                    dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'B><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                    destroy: true,
                    "order": [[0, "desc"]],
                    "scrollX": true,
                    'sAjaxSource': "globaldata/itemhistorydata.php?userid=" + userid + "&itemnum=" + itemnum,
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5'
                    ]
                });

                //Trend calc for item score
                $.ajax({
                    url: 'globaldata/itemhistorytrend.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {userid: userid, itemnum: itemnum},
                    success: function (result) {
                        $("#itemtrend").html(result);
                    }
                });


                //options for actual replens highchart
                var options3 = {
                    chart: {
                        marginTop: 50,
                        marginBottom: 135,
                        renderTo: 'container_historicalscores',
                        type: 'spline'
                    }, credits: {
                        enabled: false
                    },
                    plotOptions: {
                        spline: {
                            marker: {
                                enabled: false
                            }
                        },
                        series: {
                            cursor: 'pointer',
                            point: {
                                events: {
                                    click: function () {
//                                        location.href = '7MovesDetail.php?date=' + this.category + '&type=' + this.series.name + '&formSubmit=Submit';
                                    }
                                }
                            }
                        }
                    },
                    title: {
                        text: ' '
                    },
                    xAxis: {
                        categories: [], labels: {
                            rotation: -90,
                            y: 25,
                            align: 'right',
                            step: 5,
                            style: {
                                fontSize: '12px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        },
                        legend: {
                            y: "10",
                            x: "5"
                        }

                    },
                    yAxis: {
                        title: {
                            text: 'Historical Item Scores'
                        },
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }],
                        opposite: true
                    }, tooltip: {
                        formatter: function () {
                            return '<b>' + this.series.name + '</b><br/>' +
                                    this.x + ': ' + Highcharts.numberFormat(this.y, 2);
                        }
                    },
                    series: []
                };
                $.ajax({
                    url: 'globaldata/dashboardgraph_historicalitemscores.php',
                    data: {"userid": userid, "itemnum": itemnum},
                    type: 'GET',
                    dataType: 'json',
                    async: 'true',
                    success: function (json) {
                        options3.xAxis.categories = json[0]['data'];
                        options3.series[0] = json[1];
                        options3.series[1] = json[2];
                        options3.series[2] = json[3];
                        options3.series[3] = json[4];
                        options3.series[4] = json[5];
                        options3.series[5] = json[6];

                        options3.series[1].visible = false;
                        options3.series[2].visible = false;
                        options3.series[3].visible = false;
                        options3.series[4].visible = false;
                        options3.series[5].visible = false;

                        chart = new Highcharts.Chart(options3);
                        series = chart.series;
                        $(window).resize();
                    }
                });
                filliteminputval(itemnum);  //file the item input field
                cleanurl();  //clean the URL of post data        

                $('#masterhider').removeClass('hidden');


            }

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

            function filliteminputval(itemnum) {  //fill item input text
                document.getElementById("itemnum").value = itemnum;
            }

            function cleanurl() { //clean the URL if called from another page
                var clean_uri = location.protocol + "//" + location.host + location.pathname;
                window.history.replaceState({}, document.title, clean_uri);
            }

            $(document).ready(function () {
                if (window.location.href.indexOf("itemnum") > -1) {
                    debugger;
                    //Place this in the document ready function to determine if there is search variables in the URL.  
                    //Must clean the URL after load to prevent looping
                    var itemnum = GetUrlValue('itemnum');

                    gettable(itemnum);  //pass the 
                }
            });

            //On close of action modal, clear all fields and toggle hidden
            $('.modal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });

        </script>
        <script>
            $("#reports").addClass('active');
        </script>
    </body>
</html>
