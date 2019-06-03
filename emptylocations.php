<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    ?>
    <head>
        <title>OSS - Empty Locations</title>
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
                    <div class="pull-left  col-lg-2">
                        <label>Select Tier:</label>
                        <select class="selectstyle" id="tiersel" name="tiersel" style="width: 100px;padding: 5px; margin-right: 10px;"onchange="getgrid5data(this.value);">
                            <option value="BIN">BIN</option>
                            <option value="ECAP">End Cap</option>
                            <option value="FLOW">FLOW</option>
                            <option value="PALL">PALL</option>
                        </select>

                    </div>
                    <div class="pull-left" >
                        <button id="loaddata" type="button" class="btn btn-primary" onclick="gettable();" style="margin-bottom: 5px;">Load Data</button>
                    </div>
                </div>

                <div id="mastercontainer" class=" hidden"style="" >
                    <section class="panel hidewrapper" id="tbl_historicalscores" style="margin-bottom: 50px; margin-top: 20px;"> 
                        <header class="panel-heading bg bg-inverse h2">Table - Empty Locations<i class="fa fa-close pull-right closehidden" style="cursor: pointer;" id="close_tblitemsonhold"></i><i class="fa fa-chevron-up pull-right clicktotoggle-chevron" style="cursor: pointer;"></i></header>
                        <div id="tbl_itemsonhold" class="panel-body">
                            <div id="tablecontainer" class="">
                                <table id="ptbtable" class="table table-bordered" cellspacing="0" style="font-size: 11px; font-family: Calibri;">
                                    <thead>
                                        <tr>
                                            <th>Location</th>
                                            <th>Tier</th>
                                            <th>Grid5</th>
                                            <th>Pick Zone</th>
                                            <th>Use Height</th>
                                            <th>Use Depth</th>
                                            <th>Use Width</th>
                                            <th>Use Cube</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>


            </section>
        </section>


        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});


            function gettable() {
                $('#mastercontainer').addClass('hidden');

                var userid = $('#userid').text();
                var tier = $('#tiersel').val();

                //Empty Locations
                oTable = $('#ptbtable').dataTable({
                    dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'B><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                    destroy: true,
                    "scrollX": true,
                    'sAjaxSource': "globaldata/dt_emptylocation.php?userid=" + userid + "&tier=" + tier,
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5'
                    ]
                });

                $('#mastercontainer').removeClass('hidden');
            }

            $("#reports").addClass('active');
        </script>
    </body>
</html>
