<!DOCTYPE html>
<!--The data returned are locations in the slotmaster that are not mapped.-->
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    include_once '../globalfunctions/slottingfunctions.php';

    $var_whse = 'GB0001';
    ?>
    <head>
        <title>OSS - Mapping Errors</title>
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
                <h1>Mapping Errors</h1>


                <div class="row" style="padding: 30px;">
                    <!--Add vector button-->
                    <button type="submit" class="btn btn-primary btn-lg pull-left" name="addvectorbtn" id="addvectorbtn"style="margin: 10px;">Add Vector</button>
                    <!--Add bay loc button-->
                    <button type="submit" class="btn btn-primary btn-lg pull-left" name="addbaylocbtn" id="addbaylocbtn" style="margin: 10px;">Add Bay Location</button>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <!--Vector map error table.  -->
                        <section class="panel hidewrapper" id="sec_baylocerror" style="margin-bottom: 50px; margin-top: 20px;"> 
                            <header class="panel-heading bg bg-inverse h2">Bay/Location Map Errors</header>
                            <div id="tbl_baylocerror" class="panel-body">
                                <div id="baylocerrorcontainer" class="">
                                    <table id="baylocerrortable" class="table table-bordered" cellspacing="0" style="font-size: 11px; font-family: Calibri; cursor: pointer;">
                                        <thead>
                                            <tr>
                                                <th>Add Bay/Loc</th>
                                                <th>Location</th>
                                                <th>Dim Group</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-6">
                        <!--Vector map error table.  -->
                        <section class="panel hidewrapper" id="sec_vectorerror" style="margin-bottom: 50px; margin-top: 20px;"> 
                            <header class="panel-heading bg bg-inverse h2">Vector Map Errors</header>
                            <div id="tbl_vectorerror" class="panel-body">
                                <div id="maperrorcontainer" class="">
                                    <table id="maperrortable" class="table table-bordered" cellspacing="0" style="font-size: 11px; font-family: Calibri; cursor: pointer;">
                                        <thead>
                                            <tr>
                                                <th>Add Vector</th>
                                                <th>Bay</th>
                                                <th>Tier</th>
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


        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});

            $("#modules").addClass('active');

            oTable2 = $('#maperrortable').DataTable({
                dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'B><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                destroy: true,
                "scrollX": true,
                'sAjaxSource': "globaldata/maperror.php",
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $('td:eq(0)', nRow).append("<div class='text-center'><i class='fa fa-cog clickitemcheck' style='cursor: pointer;' data-toggle='tooltip' data-title='Add Vector' data-placement='top' data-container='body'></i></div>");
                },
                buttons: [
                    'copyHtml5',
                    'excelHtml5'
                ]
            });

            oTable3 = $('#baylocerrortable').DataTable({
                dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'B><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                destroy: true,
                "scrollX": true,
                'sAjaxSource': "globaldata/baylocerror.php",
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $('td:eq(0)', nRow).append("<div class='text-center'><i class='fa fa-cog clickitemcheck_bayloc' style='cursor: pointer;' data-toggle='tooltip' data-title='Add Bay Location' data-placement='top' data-container='body'></i></div>");
                },
                buttons: [
                    'copyHtml5',
                    'excelHtml5'
                ]
            });

            //jquery to show modal to modify vector map settings
            $(document).on("click", ".clickitemcheck", function (e) {
                $('#modifyvectormodal').modal('toggle');
                $('#vectorid').val(0);
                $('#baymodal').val($(this).closest('tr').find('td:eq(1)').text());
                $('#tiermodal').val($(this).closest('tr').find('td:eq(2)').text());

            });


            //jquery to show modal to modify vector map settings
            $(document).on("click", ".clickitemcheck_bayloc", function (e) {
                $('#modifybaylocmodal').modal('toggle');
                $('#baylocid').val(0);
                $('#locmodal_bayloc').val($(this).closest('tr').find('td:eq(1)').text());
                $('#dimgroupmodal_bayloc').val($(this).closest('tr').find('td:eq(2)').text());

            });

            //jquery to show modal to add vector map settings
            $(document).on("click", "#addvectorbtn", function (e) {
                $('#addvectormodal').modal('toggle');
            });
            
            //jquery to show modal to add bay loc settings
            $(document).on("click", "#addbaylocbtn", function (e) {
                $('#modifybaylocmodal').modal('toggle');
            });

            //post vector map modifications to table
            $(document).on("click", "#submititemaction", function (event) {
                event.preventDefault();
                var vectorid = $('#vectorid').val();
                var baymodal = $('#baymodal').val();
                var yposmodal = $('#yposmodal').val();
                var xposmodal = $('#xposmodal').val();
                var bayheightmodal = $('#bayheightmodal').val();
                var baywidthmodal = $('#baywidthmodal').val();
                var walkmodal = $('#walkmodal').val();
                var cselsemodal = $('#cselsemodal').val();
                var tiermodal = $('#tiermodal').val();
                var whse = 1;

                var formData = 'vectorid=' + vectorid + '&baymodal=' + baymodal + '&yposmodal=' + yposmodal + '&xposmodal=' + xposmodal + '&bayheightmodal=' + bayheightmodal + '&baywidthmodal=' + baywidthmodal + '&walkmodal=' + walkmodal
                        + '&cselsemodal=' + cselsemodal + '&tiermodal=' + tiermodal + '&whse=' + whse;
                $.ajax({
                    url: 'formpost/postvectormodify.php',
                    type: 'POST',
                    data: formData,
                    success: function (result) {
                        $("#postsuccess").html(result);
                        $('#modifyvectormodal').modal('hide');
                        $('#maperrortable').DataTable().ajax.reload();
                    }
                });
            });

            //post bay lcoation modifications to table
            $(document).on("click", "#submititemaction_bayloc", function (event) {
                event.preventDefault();
                var baylocid = $('#baylocid').val();
                var locmodal_bayloc = $('#locmodal_bayloc').val();
                var dimgroupmodal_bayloc = $('#dimgroupmodal_bayloc').val();
                var baymodal_bayloc = $('#baymodal_bayloc').val();
                var waklbaymodal_bayloc = $('#waklbaymodal_bayloc').val();
                var whse = 1;

                var formData = 'baylocid=' + baylocid + '&locmodal_bayloc=' + locmodal_bayloc + '&dimgroupmodal_bayloc=' + dimgroupmodal_bayloc + '&baymodal_bayloc=' + baymodal_bayloc + '&waklbaymodal_bayloc=' + waklbaymodal_bayloc + '&whse=' + whse;
                $.ajax({
                    url: 'formpost/postbaylocmodify.php',
                    type: 'POST',
                    data: formData,
                    success: function (result) {
                        $("#postsuccess").html(result);
                        $('#modifybaylocmodal').modal('hide');
                        $('#baylocerrortable').DataTable().ajax.reload();
                    }
                });
            });


            //post add vector map to table
            $(document).on("click", "#add_submititemaction", function (event) {
                event.preventDefault();
                var vectorid = 0;
                var baymodal = $('#add_baymodal').val();
                var yposmodal = $('#add_yposmodal').val();
                var xposmodal = $('#add_xposmodal').val();
                var bayheightmodal = $('#add_bayheightmodal').val();
                var baywidthmodal = $('#add_baywidthmodal').val();
                var walkmodal = $('#add_walkmodal').val();
                var cselsemodal = $('#add_cselsemodal').val();
                var tiermodal = $('#add_tiermodal').val();
                var whse = 1;

                var formData = 'vectorid=' + vectorid + '&baymodal=' + baymodal + '&yposmodal=' + yposmodal + '&xposmodal=' + xposmodal + '&bayheightmodal=' + bayheightmodal + '&baywidthmodal=' + baywidthmodal + '&walkmodal=' + walkmodal
                        + '&cselsemodal=' + cselsemodal + '&tiermodal=' + tiermodal + '&whse=' + whse;
                $.ajax({
                    url: 'formpost/postvectormodify.php',
                    type: 'POST',
                    data: formData,
                    success: function (result) {
                        $("#postsuccess").html(result);
                        $('#addvectormodal').modal('hide');
                        $('#vectormaptable').DataTable().ajax.reload();
                    }
                });
            });

            //delete vector map from table
            $(document).on("click", "#deletevector", function (event) {
                event.preventDefault();
                var vectorid = $('#vectorid').val();

                var formData = 'vectorid=' + vectorid;
                $.ajax({
                    url: 'formpost/postdeletevector.php',
                    type: 'POST',
                    data: formData,
                    success: function (result) {
                        $("#postsuccess").html(result);
                        $('#modifyvectormodal').modal('hide');
                        $('#vectormaptable').DataTable().ajax.reload();
                    }
                });
            });

            $('.modal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });
        </script>

        <!-- Modify Vector Map Modal -->
        <div id="modifyvectormodal" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modify Vector Map</h4>
                    </div>
                    <form class="form-horizontal" id="postitemaction">
                        <div class="modal-body">
                            <div class="form-group hidden">
                                <div class="col-md-3">
                                    <input type="text" name="vectorid" id="vectorid" class="form-control" />  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Bay</label>
                                <div class="col-sm-3">
                                    <input type="text" name="baymodal" id="baymodal" class="form-control" placeholder="" tabindex="1" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Y-Position</label>
                                <div class="col-sm-3">
                                    <input type="text" name="yposmodal" id="yposmodal" class="form-control" placeholder="" tabindex="2" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">X-Position</label>
                                <div class="col-sm-3">
                                    <input type="text" name="xposmodal" id="xposmodal" class="form-control" placeholder="" tabindex="3" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Bay Height</label>
                                <div class="col-sm-3">
                                    <input type="text" name="bayheightmodal" id="bayheightmodal" class="form-control" placeholder="" tabindex="4" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Bay Width</label>
                                <div class="col-sm-3">
                                    <input type="text" name="baywidthmodal" id="baywidthmodal" class="form-control" placeholder="" tabindex="5" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Walk MM</label>
                                <div class="col-sm-3">
                                    <input type="text" name="walkmodal" id="walkmodal" class="form-control" placeholder="" tabindex="6" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Case/Loose</label>
                                <div class="col-sm-3">
                                    <input type="text" name="cselsemodal" id="cselsemodal" class="form-control" placeholder="" tabindex="7" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tier</label>
                                <div class="col-sm-3">
                                    <input type="text" name="tiermodal" id="tiermodal" class="form-control" placeholder="" tabindex="8" />
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div class="">
                                <button type="submit" class="btn btn-primary btn-lg pull-left" name="submititemaction" id="submititemaction">Modify Vector Settings</button>
                                <button type="submit" class="btn btn-danger btn-lg pull-right" name="deletevector" id="deletevector">Delete Vector</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Modify Bay Location Modal -->
        <div id="modifybaylocmodal" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modify Bay Location</h4>
                    </div>
                    <form class="form-horizontal" id="postitemaction_bayloc">
                        <div class="modal-body">
                            <div class="form-group hidden">
                                <div class="col-md-3">
                                    <input type="text" name="baylocid" id="baylocid" class="form-control" />  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Location</label>
                                <div class="col-sm-3">
                                    <input type="text" name="locmodal_bayloc" id="locmodal_bayloc" class="form-control" placeholder="" tabindex="1" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Dim Group</label>
                                <div class="col-sm-3">
                                    <input type="text" name="dimgroupmodal_bayloc" id="dimgroupmodal_bayloc" class="form-control" placeholder="" tabindex="2" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Bay</label>
                                <div class="col-sm-5">
                                    <input type="text" name="baymodal_bayloc" id="baymodal_bayloc" maxlength="4" class="form-control" placeholder="To match with vector map (3207, 5208, etc.)" tabindex="3" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Walk Bay</label>
                                <div class="col-sm-5">
                                    <input type="text" name="walkbaymodal_bayloc" id="waklbaymodal_bayloc" class="form-control" maxlength="2" placeholder="Standard walk bay (01, 05, etc.)" tabindex="4" />
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div class="">
                                <button type="submit" class="btn btn-primary btn-lg pull-left" name="submititemaction_bayloc" id="submititemaction_bayloc">Modify Bay Location Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Vector Map Modal -->
        <?php include 'globaldata/addvectormodal.php' ?>

        <!--modal to show if post was a success-->
        <div id="postsuccess"></div>

    </body>
</html>
