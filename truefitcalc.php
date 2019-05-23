<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    ?>
    <head>
        <title>True Fit Calculator</title>
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

                    <div class="pull-left  col-lg-3 col-sm-6 col-xl-2" >
                        <label>Enter Item:</label>
                        <input name='itemnum' class='selectstyle' id='itemnum'/>
                    </div>
                    <div class="pull-left  col-lg-2">
                        <label>Select Tier:</label>
                        <select class="selectstyle" id="tiersel" name="tiersel" style="width: 100px;padding: 5px; margin-right: 10px;"onchange="getgrid5data(this.value);">
                            <option value=0></option>
                            <option value="BIN">BIN</option>
                            <option value="FLOW">FLOW</option>
                            <option value="PALL">PALL</option>
                        </select>

                    </div>
                    <div class="pull-left col-lg-2">
                        <label>Select Loc Dim:</label>
                        <span id="grid5dropdownajax_suggested"></span>
                    </div>

                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-lg-2" >
                            <button id="loaddata" type="button" class="btn btn-primary" onclick="gettable();" style="margin-bottom: 5px;">Load Data</button>
                        </div>
                    </div>
                </div>

                <div id="itemdetailcontainerloading" class="loading col-sm-12 text-center hidden" >
                    Data Loading <img src="../ajax-loader-big.gif"/>
                </div>
                <div id="postrequest" style="margin: 15px; min-width: 0px; font-family: calibri; font-size: 20px; " class="hidden"></div>

            </section>
        </section>


        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});

            function getgrid5data(tiersel) {
                var userid = $('#userid').text();
                var tiersel = tiersel;
                $.ajax({
                    url: 'globaldata/dropdown_grid5.php', //url for the ajax.  Variable numtype is either salesplan, billto, shipto
                    data: {tiersel: tiersel, userid: userid}, //pass salesplan, billto, shipto all through billto
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $("#grid5dropdownajax_suggested").html(ajaxresult);
                    }
                });
                $.ajax({
                    url: 'globaldata/dropdown_grid5_current.php', //url for the ajax.  Variable numtype is either salesplan, billto, shipto
                    data: {tiersel: tiersel, userid: userid}, //pass salesplan, billto, shipto all through billto
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $("#grid5dropdownajax_current").html(ajaxresult);
                    }
                });

            }

            function gettable() {
                $('#postrequest').addClass('hidden');
                $('#itemdetailcontainerloading').removeClass('hidden');
                var userid = $('#userid').text();
                var itemnum = $('#itemnum').val();
                var tiersel = $('#tiersel').val();
                var grid5sel = $('#grid5sel').val();

            $.ajax({
                    url: 'globaldata/tfitemgriddata.php',
                    data: {userid: userid, itemnum: itemnum, tiersel: tiersel, grid5sel: grid5sel},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $('#postrequest').removeClass('hidden');
                        $('#itemdetailcontainerloading').addClass('hidden');
                        $("#postrequest").html(ajaxresult);
                    }
                });
            }
        </script>

        <script>
            $("#reports").addClass('active');

        </script>
    </body>
</html>
