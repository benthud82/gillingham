<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    include_once '../globalfunctions/slottingfunctions.php';

    $var_whse = 'GB0001';
    ?>
    <head>
        <title>OSS - Bay Location Table</title>
        <!--        <link href="js/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css"/>-->
        <?php include_once 'headerincludes.php'; ?>
    </head>

    <body style="">
        <!--include horz nav php file-->
        <?php include_once 'horizontalnav.php'; ?>
        <!--include vert nav php file-->
        <?php include_once 'verticalnav.php'; ?>


        <section id="content"> 
            <section class="main padder" style="padding-top: 75px"> 
                <h1>Bay Location Table</h1>


                <div class="row" style="padding: 30px;">
                    <!--Add bay loc button-->
                    <button type="submit" class="btn btn-primary btn-lg pull-left" name="addbaylocbtn" id="addbaylocbtn" style="margin: 10px;">Add Bay Location</button>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <!--Vector map error table.  -->
                        <section class="panel hidewrapper" id="sec_baylocerror" style="margin-bottom: 50px; margin-top: 20px;"> 
                            <header class="panel-heading bg bg-inverse h2">Bay/Location Table</header>
                            <div id="tbl_baylocerror" class="panel-body">
                                <div id="baylocerrorcontainer" class="">
                                    <table id="baylocerrortable" class="table table-bordered" cellspacing="0" style="font-size: 11px; font-family: Calibri; cursor: pointer;">
                                        <thead>
                                            <tr>
                                                <th>Modify Row</th>
                                                <th>Location</th>
                                                <th>Dim Group</th>
                                                <th>Bay</th>
                                                <th>Walk Bay</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </section>


        <!-- Add Vector Map Modal -->
        <?php include 'globaldata/addbaylocmodal.php' ?>

        <!--modal to show if post was a success-->
        <div id="postsuccess"></div>

        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});

            $("#modules").addClass('active');

            oTable2 = $('#baylocerrortable').DataTable({
                dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'B><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                destroy: true,
                "scrollX": true,
                'sAjaxSource': "globaldata/dt_bayloc.php",
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $('td:eq(0)', nRow).append("<div class='text-center'><i class='fa fa-cog clickitemcheck' style='cursor: pointer;' data-toggle='tooltip' data-title='Modify Vector' data-placement='top' data-container='body'></i></div>");
                },
                buttons: [
                    'copyHtml5',
                    'excelHtml5'
                ]
            });

            //jquery to show modal to modify vector map settings
            $(document).on("click", ".clickitemcheck", function (e) {
                $('#modifybaylocmodal').modal('toggle');
                $('#locmodal_bayloc').val($(this).closest('tr').find('td:eq(1)').text());
                $('#dimgroupmodal_bayloc').val($(this).closest('tr').find('td:eq(2)').text());
                $('#baymodal_bayloc').val($(this).closest('tr').find('td:eq(3)').text());
                $('#waklbaymodal_bayloc').val($(this).closest('tr').find('td:eq(4)').text());
            });

            //jquery to show modal to add bay loc settings
            $(document).on("click", "#addbaylocbtn", function (e) {
                $('#modifybaylocmodal').modal('toggle');
            });

               //post bay lcoation modifications to table
            $(document).on("click", "#submititemaction_bayloc", function (event) {
                event.preventDefault();
                var locmodal_bayloc = $('#locmodal_bayloc').val();
                var dimgroupmodal_bayloc = $('#dimgroupmodal_bayloc').val();
                var baymodal_bayloc = $('#baymodal_bayloc').val();
                var waklbaymodal_bayloc = $('#waklbaymodal_bayloc').val();
                var whse = 1;
                _postbayloc(locmodal_bayloc, dimgroupmodal_bayloc, baymodal_bayloc, waklbaymodal_bayloc, whse);
            });

            //delete vector map from table
            $(document).on("click", "#deletebayloc", function (event) {
                event.preventDefault();
                var locid = $('#locmodal_bayloc').val();
                _deletebayloc(locid);

            });

            $('.modal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });
        </script>



    </body>
</html>
