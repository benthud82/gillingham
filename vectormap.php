<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    include_once '../globalfunctions/slottingfunctions.php';

    $var_whse = 'GB0001';
    ?>
    <head>
        <title>OSS - Vector Map</title>
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
                <h1>Vector Map</h1>

                <!--Add vector button-->
                <div class="row" style="padding: 30px;">
                    <button type="submit" class="btn btn-primary btn-lg pull-left" name="addvectorbtn" id="addvectorbtn">Add Vector</button>
                </div>

                <!--Vector map table-->
                <div id="vectormapcontainer" class="">
                    <table id="vectormaptable" class="table table-bordered" cellspacing="0" style="font-size: 11px; font-family: Calibri; cursor: pointer;">
                        <thead>
                            <tr>
                                <th>Modify</th>
                                <th>ID</th>
                                <th>Whse</th>
                                <th>Bay</th>
                                <th>Y-Position</th>
                                <th>X-Position</th>
                                <th>Bay Height</th>
                                <th>Bay Width</th>
                                <th>Walk MM</th>
                                <th>Case/Loose</th>
                                <th>Tier</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </section>
        </section>


        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});
            
            $("#modules").addClass('active');

            oTable2 = $('#vectormaptable').DataTable({
                dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'B><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                destroy: true,
                "scrollX": true,
                'sAjaxSource': "globaldata/vectormapdt.php",
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
                $('#modifyvectormodal').modal('toggle');
                $('#vectorid').val($(this).closest('tr').find('td:eq(1)').text());
                $('#baymodal').val($(this).closest('tr').find('td:eq(3)').text());
                $('#yposmodal').val($(this).closest('tr').find('td:eq(4)').text());
                $('#xposmodal').val($(this).closest('tr').find('td:eq(5)').text());
                $('#bayheightmodal').val($(this).closest('tr').find('td:eq(6)').text());
                $('#baywidthmodal').val($(this).closest('tr').find('td:eq(7)').text());
                $('#walkmodal').val($(this).closest('tr').find('td:eq(8)').text());
                $('#cselsemodal').val($(this).closest('tr').find('td:eq(9)').text());
                $('#tiermodal').val($(this).closest('tr').find('td:eq(10)').text());

            });

            //jquery to show modal to add vector map settings
            $(document).on("click", "#addvectorbtn", function (e) {
                $('#addvectormodal').modal('toggle');
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
                        $('#vectormaptable').DataTable().ajax.reload();
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

        <!-- Add Vector Map Modal -->
        <?php include 'globaldata/addvectormodal.php' ?>

        <!--modal to show if post was a success-->
        <div id="postsuccess"></div>

    </body>
</html>
