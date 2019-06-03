<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    ?>
    <head>
        <title>OSS - Item Query</title>
        <?php include_once 'headerincludes.php'; ?>
    </head>

    <body style="">
        <!--include horz nav php file-->
        <?php include_once 'horizontalnav.php'; ?>
        <!--include vert nav php file-->
        <?php include_once 'verticalnav.php'; ?> 


        <section id="content"> 
            <section class="main padder"> 
                <div class="row" style="padding-bottom: 25px;padding-top: 75px;"> 
                    <div class="col-md-3 col-sm-3 col-xs-12 col-lg-2 col-xl-2 text-center">
                        <label>Item:</label>
                        <input name='itemnum' class='selectstyle' id='itemnum'/>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 col-lg-2 col-xl-2 text-center">
                        <label>Location:</label>
                        <input name='location' class='selectstyle' id='location'/>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 col-lg-2 col-xl-2 text-center">
                        <button id="loaddata" type="button" class="btn btn-primary" onclick="fillitemlocation();" >Load Data</button>
                    </div>
                </div>

                <!--Build itemhistory link-->
                <div id="gotoitemhistory"></div>

                <!--Build move assist link-->
                <div id="gotomoveassist"></div>

                <div id="itemdetailcontainer" class="col-md-12"> </div>


                <div id="itemsettingscontainer" class="col-md-12" style="display: block;padding-left: 0px;padding-right: 0px;"> </div>



                <!--OpenTasks Panel-->
                <div class="row">
                    <div class="hidden" id="tasktablecontainer">
                        <div class="col-md-12 col-lg-12 col-xl-6 ">
                            <div class="hidewrapper">
                                <section class="panel portlet-item" style="opacity: 1; z-index: 0;"> 
                                    <header class="panel-heading bg-danger" style="font-size: 24px;"> Item Open Tasks  <span class="pull-right" style="cursor: pointer; font-size: 16px;" id="additemtask">Add Item Task <i class="fa fa-plus-circle"></i></span> </header> 
                                    <div class="panel-body">
                                        <div id="asgntaskstablecontainer" class="">
                                            <table id="opentaskstable" class="table table-bordered table-striped" cellspacing="0" style="font-size: 11px; font-family: Calibri;">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Assigned By</th>
                                                        <th>Assigned To TSM</th>
                                                        <th>Assigned Date</th>
                                                        <th>Comment</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>

                        <!--completed Tasks Panel-->
                        <div class="col-md-12 col-lg-12 col-xl-6 ">
                            <div class="hidewrapper ">
                                <section class="panel portlet-item" style="opacity: 1; z-index: 0;"> 
                                    <header class="panel-heading bg-success" style="font-size: 24px;"> Item Completed Tasks </header> 
                                    <div class="panel-body">
                                        <div id="asgntasksgrouptablecontainer" class="">
                                            <table id="closedtaskstable" class="table table-bordered table-striped" cellspacing="0" style="font-size: 11px; font-family: Calibri;">
                                                <thead>
                                                    <tr>
                                                        <th>Assigned By</th>
                                                        <th>Completed By</th>
                                                        <th>Assigned Date</th>
                                                        <th>Completed Date</th>
                                                        <th>Assigned Comment</th>
                                                        <th>Completed Comment</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>

                    </div>
                </div>



            </section>
        </section>


        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});
            $('#itemdetailcontainer').hide();
            $('#itemsettingscontainer').hide();

            function fillitemlocation() {
                var itemnum = $('#itemnum').val();
                var location = $('#location').val();

                $.ajax({
                    url: 'globaldata/finditemlocation.php',
                    data: {itemnum: itemnum, location: location},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        debugger;
                        var result = $.parseJSON(ajaxresult);
                        var locreturned = result[0];
                        var itemreturned = result[1];
                        gettable(itemreturned);  //file the item input field
                    }
                });

            }

            function gettable(itemnumpost) {
                debugger;
                $('#tasktablecontainer').addClass('hidden');
                if (typeof itemnumpost !== 'undefined') {
                    var itemnum = itemnumpost;
                    var userid = $('#userid').text();

                } else {
                    var itemnum = $('#itemnum').val();
                    var userid = $('#userid').text();
                }


                //populate item detail data
                $.ajax({
                    url: 'globaldata/itemdetaildata.php',
                    data: {itemnum: itemnum, userid: userid},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $('#itemdetailcontainer').show();
                        $("#itemdetailcontainer").html(ajaxresult);
                    }
                });

                //populate item settings data
                $.ajax({
                    url: 'globaldata/itemsettingsdata.php',
                    data: {itemnum: itemnum, userid: userid},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $('#itemsettingscontainer').show();
                        $('#tasktablecontainer').show();
                        $("#itemsettingscontainer").html(ajaxresult);
                        filliteminputval(itemnum);  //file the item input field
                        cleanurl();  //clean the URL of post data
                    }
                });
                $('#tasktablecontainer').toggleClass('hidden');

                //fill open task datatable
                oTable3 = $('#opentaskstable').DataTable({
                    dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                    destroy: true,
                    "order": [[3, "asc"]],
                    "scrollX": true,
                    "columnDefs": [
                        {
                            "targets": [0],
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    'sAjaxSource': "globaldata/itemquery_opentasks.php?userid=" + userid + "&itemnum=" + itemnum,
                    "fnCreatedRow": function (nRow, aData, iDataIndex) {
                        $('td:eq(4)', nRow).append("<div class='text-center'><i id='showitemcompletemodal' class='fa fa-times-circle' style='cursor: pointer;' data-toggle='tooltip' data-title='Mark as Complete' data-placement='top' data-container='body' ></i> </div>");
                    }
                });

                //fill completed task datatable
                oTable4 = $('#closedtaskstable').DataTable({
                    dom: "<'row'<'col-sm-4 pull-left'l><'col-sm-4 text-center'><'col-sm-4 pull-right'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-4 pull-left'i><'col-sm-8 pull-right'p>>",
                    destroy: true,
                    "order": [[3, "desc"]],
                    "scrollX": true,

                    'sAjaxSource': "globaldata/itemquery_closedtasks.php?userid=" + userid + "&itemnum=" + itemnum

                });
                //Build itemquery link
                $.ajax({
                    url: 'globaldata/itemhistorylink.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {userid: userid, itemnum: itemnum},
                    success: function (result) {
                        $("#gotoitemhistory").html(result);
                    }
                });

                //Build moveassist link
                $.ajax({
                    url: 'globaldata/moveassistlink.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {userid: userid, itemnum: itemnum},
                    success: function (result) {
                        $("#gotomoveassist").html(result);
                    }
                });

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

            $(document).on("click", ".clicktotoggle-chevron", function (e) {
                $(this).toggleClass('fa-chevron-circle-down fa-chevron-circle-up');
                $(this).closest('.panel').find('.hiddencostdetail').slideToggle();
            });

            $(document).ready(function () {
                if (window.location.href.indexOf("itemnum") > -1) {
                    debugger;
                    //Place this in the document ready function to determine if there is search variables in the URL.  
                    //Must clean the URL after load to prevent looping
                    var itemnum = GetUrlValue('itemnum');

                    gettable(itemnum);  //pass the 
                }
            });

            //modal show add item task
            $(document).on("click", "#additemtask", function (e) {
                $('#addactionmodal').modal('toggle');
            });

            //show move audit modal
            $(document).on("click touchstart", ".moveauditclick", function (e) {
                $('#actualmovemodal').modal('toggle');
                $('#itemdetailcontainerloading').toggleClass('hidden');
                $('#divtablecontainer').addClass('hidden');
                var lseorcse = $(this).attr('id');

                var itemcode = $('#itemnum').val();
                var userid = $('#userid').text();
                debugger;
                $.ajax({
                    url: 'globaldata/moveaudithistory.php',
                    data: {itemcode: itemcode, userid: userid, lseorcse: lseorcse},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $('#itemdetailcontainerloading').toggleClass('hidden');
                        $('#divtablecontainer').removeClass('hidden');
                        $("#movedetaildata").html(ajaxresult);
                    }
                });
            });

            //show pick audit modal
            $(document).on("click touchstart", ".pickauditclick", function (e) {
                $('#actualpickmodal').modal('toggle');
                $('#itemdetailcontainerloading_pick').toggleClass('hidden');
                $('#divtablecontainer').addClass('hidden');
                var lseorcse = $(this).attr('id');

                var itemcode = $('#itemnum').val();
                var userid = $('#userid').text();
                debugger;
                $.ajax({
                    url: 'globaldata/pickaudithistory.php',
                    data: {itemcode: itemcode, userid: userid, lseorcse: lseorcse},
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $('#itemdetailcontainerloading_pick').toggleClass('hidden');
                        $('#divtablecontainer').removeClass('hidden');
                        $("#pickdetaildata").html(ajaxresult);
                    }
                });
            });

            //modal to show complete item action
            $(document).on("click", "#showitemcompletemodal", function (e) {
                debugger;
                var tr = $(this).closest("tr");
                var rowindex = tr.index();
                var assigntask_id = oTable3.row(rowindex).data()[0];
                $('#assid').val(assigntask_id);
                $('#itemactioncompletemodal').modal('toggle');
            });

            //show modal to toggle modifysettingsclick
            $(document).on("click", "#modifysettingsclick", function (e) {
                //toggle current setting for modal
                var casetf = $('#casetf').text();
                var holdtier = $('#holdtier').text();
                var holdgrid = $('#holdgrid').text();
                var holdlocation = $('#holdlocation').text();
                var $radios = $('input:radio[name=switch_2]');
                if (casetf === 'Y') {
                    $radios.filter('[value=yes]').prop('checked', true);
                } else {
                    $radios.filter('[value=no]').prop('checked', true);
                }

                if (holdtier !== null) {
                    $('#holdtiermodal').val(holdtier);
                }

                if (holdgrid !== null) {
                    $('#grid5sel').val(holdgrid);
                }


                if (holdlocation !== null) {
                    $('#holdlocmodal').val(holdlocation);
                }


                $('#itemsettingsmodal').modal('toggle');
            });

            //update item settings post to mysql
            $(document).on("click", "#updatesettings", function (event) {
                event.preventDefault();


                if (document.getElementById('switch_left').checked) {
                    var casetf = 'Y';
                } else {
                    var casetf = 'N';
                }
                var itemmodal = $('#itemnum').val();
                var tiermodal = $('#holdtiermodal').val();
                var gridmodal = $('#grid5sel').val();
                var locmodal = $('#holdlocmodal').val();
                var pkgu = 1;

                var formData = 'casetf=' + casetf + '&itemmodal=' + itemmodal + '&pkgu=' + pkgu + '&tiermodal=' + tiermodal + '&gridmodal=' + gridmodal + '&locationmodal=' + locmodal;
                $.ajax({
                    url: 'formpost/modifysettings.php',
                    type: 'POST',
                    data: formData,
                    success: function (result) {
                        $('#itemsettingsmodal').modal('hide');
                        gettable(itemmodal);
                    }
                });
            });

            //add item task to mysql table
            $(document).on("click", "#additemaction", function (event) {
                event.preventDefault();
                debugger;
                var itemmodal = $('#itemnum').val();
                var commentmodal = $('#actioncommentmodal').val();
                var assignedtsm = $('#tsmlist').val();
                var formData = 'itemmodal=' + itemmodal + '&commentmodal=' + commentmodal + '&assignedtsm=' + assignedtsm;
                $.ajax({
                    url: 'formpost/itemactionadd.php',
                    type: 'POST',
                    data: formData,
                    success: function (result) {
                        $('#addactionmodal').modal('hide');
                        gettable(itemmodal);
                    }
                });
            });

            //show modal to add action for item
            $(document).on("click", "#addaction", function (e) {
                $('#addactionmodal').modal('toggle');
            });

            //complete item action to post to mysql
            $(document).on("click", "#btn_completeaction", function (event) {
                event.preventDefault();
                var commentmodal = $('#commentmodal_action').val();
                var itemmodal = $('#itemnum').val();
                var assigntask_id = $('#assid').val();

                var formData = 'commentmodal=' + commentmodal + '&itemmodal=' + itemmodal + '&assigntask_id=' + assigntask_id;
                $.ajax({
                    url: 'formpost/itemactioncomplete.php',
                    type: 'POST',
                    data: formData,
                    success: function (result) {
                        $('#itemactioncompletemodal').modal('hide');
                        gettable(itemmodal);
                    }
                });
            });

            //ajax to load grid5 by tier
            function getgrid5data(tiersel) {
                debugger;
                //submit button check

                buttonstatuscheck();

                var userid = $('#userid').text();
                var tiersel = tiersel;
                $.ajax({
                    url: 'globaldata/dropdown_holdgrid.php', //url for the ajax.  Variable numtype is either salesplan, billto, shipto
                    data: {tiersel: tiersel, userid: userid}, //pass salesplan, billto, shipto all through billto
                    type: 'POST',
                    dataType: 'html',
                    success: function (ajaxresult) {
                        $("#grid5dropdownajax_suggested").html(ajaxresult);

                    }
                });
            }



            $(document).on("change", "#grid5sel", function () {
                //call the function for dynamically loaded grid5
                buttonstatuscheck();
            });

            //On close of action modal, clear all fields and toggle hidden
            $('.modal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });

            //key press enter, submit item action
            $('#email').keypress(function (e) {
                if (e.which === '13') {
                    submitLogin();
                }
            });

            //clear the other input box on click of other box
            $('#itemnum').click(function () {
                $('#location').val('');
            });
            $('#location').click(function () {
                $('#itemnum').val('');
            });
        </script>

        <!--Personal Script for showing and completing item comments-->
        <script src="js/itemcomments.js" type="text/javascript"></script>

        <script>
            $("#reports").addClass('active');
        </script>

        <!--Add comment modal.  Be sure to include itemcomments.js-->
        <?php include_once 'globaldata/addcommentmodal.php'; ?>

        <!--Include acutal move modal-->
        <?php include_once 'globaldata/actualmovemodal.php'; ?>
        <?php include_once 'globaldata/actualpickmodal.php'; ?>

        <!--Open Item actions table-->
        <!-- Complete Action Modal -->
        <div id="itemactioncompletemodal" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Complete Item Action</h4>
                    </div>
                    <form class="form-horizontal" id="postitemactioncomplete">
                        <div class="modal-body">
                            <div class="col-md-3 hidden">
                                <!--ID of the assigned comment to pass to postcompletetask.php-->
                                <input type="text" name="assid" id="assid" class="form-control" placeholder="" tabindex="0"/>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Enter Action Completed: </label>
                                <div class="col-md-9">
                                    <textarea rows="3" placeholder="" class="form-control" id="commentmodal_action" name="commentmodal_action" tabindex="1"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg pull-left" name="btn_completeaction" id="btn_completeaction"  tabindex="2">Complete Item Action</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Action Modal -->
        <div id="addactionmodal" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Item Action</h4>
                    </div>
                    <form class="form-horizontal" id="postitemaction">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Assign Task To: </label>
                                <div class="col-md-9" id="tsmiddropdownajax" tabindex="100"> <?php include_once 'globaldata/dropdown_tsmlist.php'; ?></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Enter Detailed Comment: </label>
                                <div class="col-md-9">
                                    <textarea rows="3" placeholder="" class="form-control" id="actioncommentmodal" name="actioncommentmodal" tabindex="101"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg pull-left" name="additemaction" id="additemaction" tabindex="102">Add Item Action</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Item Settings Modal -->
        <div id="itemsettingsmodal" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modify Item Settings</h4>
                    </div>
                    <form class="form-horizontal" id="postmodifysettings">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Use Case TF on Shelf: </label>
                                <div class="col-md-9">
                                    <div class="switch-field"style="padding-left: 0px;">
                                        <input type="radio" id="switch_left" name="switch_2" value="yes" />
                                        <label for="switch_left" class="greenbackground" data-toggle="tooltip" data-title="Keep in manufacturers case on shelf?" data-placement="bottom">Yes</label>
                                        <input type="radio" id="switch_right" name="switch_2" value="no"  />
                                        <label id="nolabel" for="switch_right" data-toggle="tooltip" data-title="Remove from manufacturers case on shelf?" data-placement="bottom">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Hold to Tier: </label>
                                <div class="col-md-9">
                                    <select name = 'holdtiermodal' class = "form-control" id = 'holdtiermodal' tabindex = "2" onchange="getgrid5data(this.value);">
                                        <option value="0">Do not hold tier</option>
                                        <option value="L01">L01 - Quick Pick Pallet</option>
                                        <option value="L02">L02 - Standard Flow Rack</option>
                                        <option value="L04">L04 - Standard Blue Bin</option>
                                        <option value="L06">L06 - Slow Moving Bin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Hold to Grid5: </label>
                                <div class="col-md-9" id="grid5dropdownajax_suggested"> </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Hold to Location: </label>
                                <div class="col-md-9">
                                    <input type="text" name="holdlocmodal" id="holdlocmodal" class="form-control" placeholder="Enter location to hold..." tabindex="4" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg pull-left" name="updatesettings" id="updatesettings" >Update Settings</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function buttonstatuscheck() {
                debugger;
                var tier = $('#holdtiermodal').val();
                var grid5 = $('#grid5sel').val();
//                var location = $('#holdlocmodal').val();
                var statuscount = 0;
                if (tier === "" || tier === "0") {
                    statuscount += 1;
                }
                if (grid5 === "" || grid5 === "0" || typeof grid5 === 'undefined') {
                    statuscount += 1;
                }
                var locstrcount = $('#holdlocmodal').val().replace(/ /g, '').length;
                if (locstrcount < 6 || locstrcount >= 7) {
                    statuscount += 1;
                }


                if (statuscount === 0 || statuscount === 3) {
                    $('#updatesettings').prop('disabled', false);
                } else {
                    $('#updatesettings').prop('disabled', true);
                }
            }

            $("#holdlocmodal").on('input', function () {
                buttonstatuscheck();
            });


        </script>

    </body>
</html>
