<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    ?>
    <head>
        <title>OSS - Dashboard</title>
        <?php include_once 'headerincludes.php'; ?>

        <script src="js/snap.svg.js" type="text/javascript"></script>

    </head>

    <body style="">
        <!--include horz nav php file-->
        <?php include_once 'horizontalnav.php'; ?>
        <!--include vert nav php file-->
        <?php
        include_once 'verticalnav.php';
        include_once 'globaldata/dashboard_scores_case.php';
        include_once 'globaldata/dashboard_scores_loose.php';
        ?>

        <section id="content"> 
            <section class="main padder"> 
                <div class="row" style="padding-top: 75px;">

                    <!--Loose Stats-->
                    <div class="col-lg-6"> 
                        <div class="hidewrapper">
                            <section class="panel portlet-item" style="opacity: 1; z-index: 0;"> 
                                <header class="panel-heading bg-success"><h4> Loose Slotting Statistics <i class="fa fa-close pull-right closehidden" style="cursor: pointer;" id="close_loosestats"></i><i class="fa fa-chevron-up pull-right clicktotoggle-chevron" style="cursor: pointer;"></i></h4> </header> 
                                <div class="panel-body">
                                    <div class="row"> 
                                        <div class="col-sm-6"> 
                                            <section class="panel"> 
                                                <header class="panel-heading bg-white"> 
                                                    <div class="text-center h5"><strong>Loose Score - Bottom 100</strong></div> 
                                                </header> <div class="panel-body pull-in text-center"> 
                                                    <div class="inline"> 
                                                        <div class="easypiechart easyPieChart" data-percent="<?php echo $loosescore_bottom100; ?>" style="width: 130px; height: 130px; line-height: 130px;"> 
                                                            <span class="h2 text-center" style="margin-top:10px;display:inline-block;font-size: 24px;"><?php echo $loosescore_bottom100; ?></span> 
                                                            <div class="easypie-text text-muted text-center">score</div> 
                                                            <canvas width="130" height="130"></canvas>
                                                        </div> 
                                                    </div>
                                                </div> 
                                            </section> 
                                        </div>
                                        <div class="col-sm-6"> 
                                            <section class="panel"> 
                                                <header class="panel-heading bg-white"> 
                                                    <div class="text-center h5"><strong>Loose Score - Bottom 1,000</strong></div> 
                                                </header> <div class="panel-body pull-in text-center"> 
                                                    <div class="inline"> 
                                                        <div class="easypiechart easyPieChart" data-percent="<?php echo $loosescore_bottom1000; ?>" style="width: 130px; height: 130px; line-height: 130px;"> 
                                                            <span class="h2 text-center" style="margin-top:10px;display:inline-block;font-size: 24px;"><?php echo $loosescore_bottom1000; ?></span> 
                                                            <div class="easypie-text text-muted text-center">score</div> 
                                                            <canvas width="130" height="130"></canvas>
                                                        </div> 
                                                    </div>
                                                </div> 
                                            </section> 
                                        </div>

                                        <!--Implied Moves through ajax-->
                                        <div class="col-sm-6 col-md-6 col-lg-6"> 
                                            <section id="panel_impmoves_loose"class="panel text-center"> 
                                                <div class="panel-body bg-danger"> 
                                                    <i id="icon_impmoves_loose" class="fa fa-2x"></i><div class="h5">Daily Replen<br>Reduction Opportunity</div>
                                                    <div class="line m-l m-r"></div> 
                                                    <!--Loading spinner div-->
                                                    <div id="loading_impmoves_loose" class="loading col-sm-12 hidden" style="margin: 0 auto;">
                                                        <img class="bootstrapcol-centered" src="../ajax-loader-big.gif"/>
                                                    </div>
                                                    <!--Result div-->
                                                    <div id="result_impmoves_loose" class="h4"></div>
                                                </div>
                                            </section> 
                                        </div>

                                        <!--Implied Walk Time through ajax-->
                                        <div class="col-sm-6 col-md-6 col-lg-6"> 
                                            <section id="panel_impwalk_loose"class="panel text-center"> 
                                                <div class="panel-body bg-danger"> 
                                                    <i id="icon_impwalk_loose" class="fa fa-2x"></i><div class="h5">Daily Walk Distance<br>Reduction Opportunity</div>
                                                    <div class="line m-l m-r"></div> 
                                                    <!--Loading spinner div-->
                                                    <div id="loading_impwalk_loose" class="loading col-sm-12 hidden" style="margin: 0 auto;">
                                                        <img class="bootstrapcol-centered" src="../ajax-loader-big.gif"/>
                                                    </div>
                                                    <!--Result div-->
                                                    <div id="result_impwalk_loose" class="h4"></div>
                                                </div>
                                            </section> 
                                        </div>
                                    </div> 
                                </div> 
                            </section>
                        </div>
                    </div>

                    <!--Case Stats-->
                    <div class="col-lg-6"> 
                        <div class="hidewrapper">
                            <section class="panel portlet-item" style="opacity: 1; z-index: 0;"> 
                                <header class="panel-heading bg-success"><h4> Case Slotting Statistics <i class="fa fa-close pull-right closehidden" style="cursor: pointer;" id="close_loosestats"></i><i class="fa fa-chevron-up pull-right clicktotoggle-chevron" style="cursor: pointer;"></i></h4></header> 
                                <div class="panel-body">
                                    <div class="row"> 
                                        <div class="col-sm-6"> 
                                            <section class="panel"> 
                                                <header class="panel-heading bg-white"> 
                                                    <div class="text-center h5"><strong>Case Score - Bottom 100</strong></div> 
                                                </header> <div class="panel-body pull-in text-center"> 
                                                    <div class="inline"> 
                                                        <div class="easypiechart easyPieChart" data-percent="<?php echo $casescore_bottom100; ?>" style="width: 130px; height: 130px; line-height: 130px;"> 
                                                            <span class="h2 text-center" style="margin-top:10px;display:inline-block;font-size: 24px;"><?php echo $casescore_bottom100; ?></span> 
                                                            <div class="easypie-text text-muted text-center">score</div> 
                                                            <canvas width="130" height="130"></canvas>
                                                        </div> 
                                                    </div>
                                                </div> 
                                            </section> 
                                        </div>
                                        <div class="col-sm-6"> 
                                            <section class="panel"> 
                                                <header class="panel-heading bg-white"> 
                                                    <div class="text-center h5"><strong>Case Score - Bottom 1,000</strong></div> 
                                                </header> <div class="panel-body pull-in text-center"> 
                                                    <div class="inline"> 
                                                        <div class="easypiechart easyPieChart" data-percent="<?php echo $casescore_bottom1000; ?>" style="width: 130px; height: 130px; line-height: 130px;"> 
                                                            <span class="h2 text-center" style="margin-top:10px;display:inline-block;font-size: 24px;"><?php echo $casescore_bottom1000; ?></span> 
                                                            <div class="easypie-text text-muted text-center">score</div> 
                                                            <canvas width="130" height="130"></canvas>
                                                        </div> 
                                                    </div>
                                                </div> 
                                            </section> 
                                        </div>


                                        <!--Implied Moves through ajax-->
                                        <div class="col-sm-6 col-md-6 col-lg-6"> 
                                            <section id="panel_impmoves_case"class="panel text-center"> 
                                                <div class="panel-body bg-danger"> 
                                                    <i id="icon_impmoves_case" class="fa fa-2x"></i><div class="h5">Daily Replen<br>Reduction Opportunity</div>
                                                    <div class="line m-l m-r"></div> 
                                                    <!--Loading spinner div-->
                                                    <div id="loading_impmoves_case" class="loading col-sm-12 hidden" style="margin: 0 auto;">
                                                        <img class="bootstrapcol-centered" src="../ajax-loader-big.gif"/>
                                                    </div>
                                                    <!--Result div-->
                                                    <div id="result_impmoves_case" class="h4"></div>
                                                </div>
                                            </section> 
                                        </div>

                                        <!--Implied Walk Time through ajax-->
                                        <div class="col-sm-6 col-md-6 col-lg-6"> 
                                            <section id="panel_impwalk_case"class="panel text-center"> 
                                                <div class="panel-body bg-danger"> 
                                                    <i id="icon_impwalk_case" class="fa fa-2x"></i><div class="h5">Daily Hour<br>Reduction Opportunity</div>
                                                    <div class="line m-l m-r"></div> 
                                                    <!--Loading spinner div-->
                                                    <div id="loading_impwalk_case" class="loading col-sm-12 hidden" style="margin: 0 auto;">
                                                        <img class="bootstrapcol-centered" src="../ajax-loader-big.gif"/>
                                                    </div>
                                                    <!--Result div-->
                                                    <div id="result_impwalk_case" class="h4"></div>
                                                </div>
                                            </section> 
                                        </div>
                                    </div> 
                                </div> 
                            </section>
                        </div>
                    </div> 
                </div> 

                <!--Historical Actual Replens graph-->
                <section class="panel hidewrapper" id="graph_historicalreplens_actual" style="margin-bottom: 50px; margin-top: 20px;"> 
                    <header class="panel-heading bg bg-inverse h2">Historical Completed Replens<i class="fa fa-close pull-right closehidden" style="cursor: pointer;" id="close_replengraph_actual"></i><i class="fa fa-chevron-up pull-right clicktotoggle-chevron" style="cursor: pointer;"></i></header>
                    <div id="historicalreplens_actual" class="panel-body" style="background: #efefef">
                        <div id="chartpage_replen_actual"  class="page-break" style="width: 100%">
                            <div id="charts padded">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-info " style="font-size: 100%;"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <i class="fa fa-info-circle fa-lg"></i><span> Actual replenishments <strong>COMPLETED</strong> by <strong>REQUESTED </strong>date. Move must be completed before it will be recorded.</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-success" style="font-size: 100%;"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <i class="fa fa-arrow-down fa-lg"></i><span> Positive improvement indicated by <strong>downward</strong> trending graph. </span></div>
                                    </div>
                                </div>
                                <div id="container_replens_actual" class="dashboardstyle printrotate"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!--Historical Replens to Invoice Lines graph-->
                <section class="panel hidewrapper" id="graph_historicalreplenstolines_actual" style="margin-bottom: 50px; margin-top: 20px;"> 
                    <header class="panel-heading bg bg-inverse h2">Historical Completed Replens per 1000 Invoice Lines<i class="fa fa-close pull-right closehidden" style="cursor: pointer;" id="close_replenstolinesgraph_actual"></i><i class="fa fa-chevron-up pull-right clicktotoggle-chevron" style="cursor: pointer;"></i></header>
                    <div id="historicalreplenstolines_actual" class="panel-body" style="background: #efefef">
                        <div id="chartpage_replenstolines_actual"  class="page-break" style="width: 100%">
                            <div id="charts padded">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-info " style="font-size: 100%;"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <i class="fa fa-info-circle fa-lg"></i><span> Actual replenishments <strong>COMPLETED</strong> per <strong>1000 </strong>invoice lines. Move must be completed before it will be recorded.</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-success" style="font-size: 100%;"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <i class="fa fa-arrow-down fa-lg"></i><span> Positive improvement indicated by <strong>downward</strong> trending graph. </span></div>
                                    </div>
                                </div>
                                <div id="container_replenstolines_actual" class="dashboardstyle printrotate"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!--Historical Scores graph-->
                <section class="panel hidewrapper hidden-xs" id="graph_historicalscores" style="margin-bottom: 50px; margin-top: 20px;"> 
                    <header class="panel-heading bg bg-inverse h2">Historical Scores<i class="fa fa-close pull-right closehidden" style="cursor: pointer;" id="close_scoresgraph"></i><i class="fa fa-chevron-up pull-right clicktotoggle-chevron" style="cursor: pointer;"></i></header>
                    <div id="historicalscores" class="panel-body" style="background: #efefef">
                        <div id="chartpage_scores"  class="page-break" style="width: 100%">
                            <div id="charts padded">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-success" style="font-size: 100%;"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <i class="fa fa-arrow-up fa-lg"></i><span> Positive improvement indicated by <strong>upward</strong> trending graph. </span></div>
                                    </div>
                                </div>
                                <div id="container_scores" class="dashboardstyle printrotate"> </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!--Historical Replen Opporuntiyt graph-->
                <section class="panel hidewrapper" id="graph_historicalreplens" style="margin-bottom: 50px; margin-top: 20px;"> 
                    <header class="panel-heading bg bg-inverse h2">Historical Replen Reduction Opportunity<i class="fa fa-close pull-right closehidden" style="cursor: pointer;" id="close_replengraph"></i><i class="fa fa-chevron-up pull-right clicktotoggle-chevron" style="cursor: pointer;"></i></header>
                    <div id="historicalreplens" class="panel-body" style="background: #efefef">
                        <div id="chartpage_replen"  class="page-break" style="width: 100%">
                            <div id="charts padded">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-info " style="font-size: 100%;"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <i class="fa fa-info-circle fa-lg"></i><span> Potential replenishment reduction <strong>per day</strong> if slotted in the optimal location. </span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-success" style="font-size: 100%;"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <i class="fa fa-arrow-down fa-lg"></i><span> Positive improvement indicated by <strong>downward</strong> trending graph. </span></div>
                                    </div>
                                </div>
                                <div id="container_replens" class="dashboardstyle printrotate"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!--Historical Feet per Pick graph-->
                <section class="panel hidewrapper" id="graph_fpp" style="margin-bottom: 50px; margin-top: 20px;"> 
                    <header class="panel-heading bg bg-inverse h2">Historical Meters Per Pick<i class="fa fa-close pull-right closehidden" style="cursor: pointer;" id="close_fpp"></i><i class="fa fa-chevron-up pull-right clicktotoggle-chevron" style="cursor: pointer;"></i></header>
                    <div id="historicalfpp" class="panel-body" style="background: #efefef">
                        <div id="chartpage_fpp"  class="page-break" style="width: 100%">
                            <div id="charts padded">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-info " style="font-size: 100%;"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <i class="fa fa-info-circle fa-lg"></i><span> On average, how many aisle meters are walked per item picked. </span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-success" style="font-size: 100%;"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <i class="fa fa-arrow-down fa-lg"></i><span> Positive improvement indicated by <strong>downward</strong> trending graph. </span></div>
                                    </div>
                                </div>
                                <div id="container_fpp" class="dashboardstyle printrotate"></div>
                            </div>
                        </div>
                    </div>
                </section>

            </section>
        </section>

        <script>
            $("#dash").addClass('active');
            $(document).on("click", ".clicktotoggle-chevron", function (e) {
                $(this).toggleClass('fa-chevron-down fa-chevron-up');
                $(this).closest('.panel').find('.panel-body:first').slideToggle();
            });
            $(document).on("click", ".closehidden", function (e) {
                var clickedid = (this.id);
                $(this).closest('.hidewrapper').hide("slow");
                $('#show' + clickedid).show("slow");
                $('#show' + clickedid).removeClass('hidden');
            });

            $(document).ready(function () {

                var userid = $('#userid').text();
                //ajax for loose replen reduction opportunity
                $.ajax({
                    url: 'globaldata/replenred_loose.php', //url for the ajax.  Variable numtype is either salesplan, billto, shipto
                    data: {userid: userid}, //pass salesplan, billto, shipto all through billto
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $("#result_impmoves_loose").html(ajaxresult);
                    }
                });

                //ajax for loose walk time reduction opportunity
                $.ajax({
                    url: 'globaldata/walktime_loose.php',
                    data: {userid: userid},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $("#result_impwalk_loose").html(ajaxresult);
                    }
                });

                //ajax for case replen reduction opportunity
                $.ajax({
                    url: 'globaldata/replenred_case.php', //url for the ajax.  Variable numtype is either salesplan, billto, shipto
                    data: {userid: userid}, //pass salesplan, billto, shipto all through billto
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $("#result_impmoves_case").html(ajaxresult);
                    }
                });

                //ajax for case walk time reduction opportunity
                $.ajax({
                    url: 'globaldata/walktime_case.php',
                    data: {userid: userid},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $("#result_impwalk_case").html(ajaxresult);
                    }
                });

                //options for historiccal Scores highchart
                var options = {
                    chart: {
                        marginTop: 50,
                        marginBottom: 135,
                        renderTo: 'container_scores',
                        type: 'spline',
                        zoomType: 'x'
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
                            text: 'Historical Scores'
                        },
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }],
                        opposite: true,
                        min: 0
                    }, tooltip: {
                        formatter: function () {
                            return '<b>' + this.series.name + '</b><br/>' +
                                    this.x + ': ' + Highcharts.numberFormat(this.y, 1);
                        }
                    },
                    series: []
                };
                $.ajax({
                    url: 'globaldata/dashboardgraph_scores.php',
                    data: {"userid": userid},
                    type: 'GET',
                    dataType: 'json',
                    async: 'true',
                    success: function (json) {
                        options.xAxis.categories = json[0]['data'];
                        options.series[0] = json[1];
                        options.series[1] = json[2];
                        options.series[2] = json[3];
                        options.series[3] = json[4];
                        chart = new Highcharts.Chart(options);
                        series = chart.series;
                        $(window).resize();
                    }
                });

                //options for historiccal replens highchart
                var options2 = {
                    chart: {
                        marginTop: 50,
                        marginBottom: 135,
                        renderTo: 'container_replens',
                        type: 'spline',
                        zoomType: 'x'
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
                            text: 'Replen Reduction'
                        },
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }],
                        opposite: true,
                        min: 0
                    }, tooltip: {
                        formatter: function () {
                            return '<b>' + this.series.name + '</b><br/>' +
                                    this.x + ': ' + Highcharts.numberFormat(this.y, 0);
                        }
                    },
                    series: []
                };
                $.ajax({
                    url: 'globaldata/dashboardgraph_replens.php',
                    data: {"userid": userid},
                    type: 'GET',
                    dataType: 'json',
                    async: 'true',
                    success: function (json) {
                        options2.xAxis.categories = json[0]['data'];
                        options2.series[0] = json[1];
                        options2.series[1] = json[2];

                        chart = new Highcharts.Chart(options2);
                        series = chart.series;
                        $(window).resize();
                    }
                });

                //options for actual replens highchart
                var options3 = {
                    chart: {
                        marginTop: 50,
                        marginBottom: 135,
                        renderTo: 'container_replens_actual',
                        type: 'spline',
                        zoomType: 'x'
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
                                             window.open('movesdetail.php?startdate=' + this.category + '&enddate=' + this.category + '&formSubmit=Submit');
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
                            text: 'Completed Replenshments'
                        },
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }],
                        opposite: true,
                        min: 0
                    }, tooltip: {
                        formatter: function () {
                            return '<b>' + this.series.name + '</b><br/>' +
                                    this.x + ': ' + Highcharts.numberFormat(this.y, 0);
                        }
                    },
                    series: []
                };
                $.ajax({
                    url: 'globaldata/dashboardgraph_replens_actual.php',
                    data: {"userid": userid},
                    type: 'GET',
                    dataType: 'json',
                    async: 'true',
                    success: function (json) {
                        options3.xAxis.categories = json[0]['data'];
                        options3.series[0] = json[1];
//                        options3.series[1] = json[2];
//                        options3.series[2] = json[3];

                        chart = new Highcharts.Chart(options3);
                        series = chart.series;
                        $(window).resize();
                    }
                });

                //options for feet per pick highchart
                var options4 = {
                    chart: {
                        marginTop: 50,
                        marginBottom: 135,
                        renderTo: 'container_fpp',
                        type: 'spline',
                        zoomType: 'x'
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
                                        location.href = 'picksbybay.php?date=' + this.category + '&type=' + this.series.name + '&formSubmit=Submit';
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
                            text: 'Average Meters per Pick'
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
                                    this.x + ': ' + Highcharts.numberFormat(this.y, 1);
                        }
                    },
                    series: []
                };
                $.ajax({
                    url: 'globaldata/dashboardgraph_fpp.php',
                    data: {"userid": userid},
                    type: 'GET',
                    dataType: 'json',
                    async: 'true',
                    success: function (json) {
                        options4.xAxis.categories = json[0]['data'];
                        options4.series[0] = json[1];


                        chart = new Highcharts.Chart(options4);
                        series = chart.series;
                        $(window).resize();
                    }
                });

                //options for replens per 1000 invoic lines highchart
                var options5 = {
                    chart: {
                        marginTop: 50,
                        marginBottom: 135,
                        renderTo: 'container_replenstolines_actual',
                        type: 'spline',
                        zoomType: 'x'
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
                                             window.open('movesdetail.php?startdate=' + this.category + '&enddate=' + this.category + '&formSubmit=Submit');
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
                            text: 'Replens per 1000 Lines'
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
                                    this.x + ': ' + Highcharts.numberFormat(this.y, 1);
                        }
                    },
                    series: []
                };
                $.ajax({
                    url: 'globaldata/dashboardgraph_replensperthousand.php',
                    data: {"userid": userid},
                    type: 'GET',
                    dataType: 'json',
                    async: 'true',
                    success: function (json) {
                        options5.xAxis.categories = json[0]['data'];
                        options5.series[0] = json[1];


                        chart = new Highcharts.Chart(options5);
                        series = chart.series;
                        $(window).resize();
                    }
                });



            });

            $(function () {

                var polar_to_cartesian, svg_circle_arc_path, animate_arc;

                polar_to_cartesian = function (cx, cy, radius, angle) {
                    var radians;
                    radians = (angle - 90) * Math.PI / 180.0;
                    return [Math.round((cx + (radius * Math.cos(radians))) * 100) / 100, Math.round((cy + (radius * Math.sin(radians))) * 100) / 100];
                };

                svg_circle_arc_path = function (x, y, radius, start_angle, end_angle) {
                    var end_xy, start_xy;
                    start_xy = polar_to_cartesian(x, y, radius, end_angle);
                    end_xy = polar_to_cartesian(x, y, radius, start_angle);
                    return "M " + start_xy[0] + " " + start_xy[1] + " A " + radius + " " + radius + " 0 0 0 " + end_xy[0] + " " + end_xy[1];
                };

                animate_arc = function (ratio, svg, perc) {
                    var arc, center, radius, startx, starty;
                    arc = svg.path('');
                    center = 500;
                    radius = 450;
                    startx = 0;
                    starty = 450;
                    return Snap.animate(0, ratio, (function (val) {
                        var path;
                        arc.remove();
                        path = svg_circle_arc_path(500, 500, 450, -90, val * 180.0 - 90);
                        arc = svg.path(path);
                        arc.attr({
                            class: 'data-arc'
                        });
                        perc.text(Math.round(val * 100) + '%');
                    }), Math.round(2000 * ratio), mina.easeinout);
                };

                $('.metric').each(function () {
                    var ratio, svg, perc;
                    ratio = $(this).data('ratio');
                    svg = Snap($(this).find('svg')[0]);
                    perc = $(this).find('text.percentage');
                    animate_arc(ratio, svg, perc);
                });
            });

        </script>


    </body>
</html>
