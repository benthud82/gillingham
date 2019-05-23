<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    include_once 'connection/NYServer.php';
    ?>
    <head>
        <title>OSS - Move Assistant</title>
        <?php include_once 'headerincludes.php'; ?>
    </head>

    <body style="">
        <!--include horz nav php file-->
        <?php include_once 'horizontalnav.php'; ?>
        <!--include vert nav php file-->
        <?php include_once 'verticalnav.php'; ?>


        <section id="content"> 
            <section class="main padder"> 
                <h2 style="padding-bottom: 0px;padding-top: 75px;">Move Assistant</h2>
                <div class="row" style="padding-bottom: 25px;"> 
                    <div class="col-md-3 col-sm-3 col-xs-12 col-lg-2 col-xl-2 text-center">
                        <label>Item:</label>
                        <input name='itemnum' class='selectstyle' id='itemnum'/>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 col-lg-2 col-xl-2 text-center">
                        <button id="loaddata" type="button" class="btn btn-primary" onclick="gettable();" >Load Data</button>
                    </div>
                </div>
                <div id="itemdetailcontainerloading" class="loading col-sm-12 text-center hidden" >
                    Data Loading <img src="../ajax-loader-big.gif"/>
                </div>

                <div id="masterhider" class="hidden">
                    <div id="whattodo_result"></div>

                    <!--masterhider close-->
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
                                    <div class="form-group hidden">
                                        <div class="col-md-9" id="itemnummodal" tabindex="100"></div>
                                    </div>
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


            </section>
        </section>


        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});



            //add item task to mysql table
            $(document).on("click", "#additemaction", function (event) {
                event.preventDefault();
                debugger;
                var itemmodal = $('#itemnummodal').val();
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
            $(document).on("click", ".addaction", function (e) {
                debugger;
                var itemnum = this.id;
                $('#itemnummodal').val(itemnum);
                $('#addactionmodal').modal('toggle');
            });


            function gettable(itemnumpost) {
                debugger;
                $('#masterhider').addClass('hidden');
                $('#itemdetailcontainerloading').removeClass('hidden');

                if (typeof itemnumpost !== 'undefined') {
                    var itemnum = itemnumpost;
                    var userid = $('#userid').text();

                } else {
                    var itemnum = $('#itemnum').val();
                    var userid = $('#userid').text();
                }


                //Call whattodo logic
                $.ajax({
                    url: 'globaldata/whattodo.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {userid: userid, itemnum: itemnum},
                    success: function (result) {
                        $("#whattodo_result").html(result);
                        $('#itemdetailcontainerloading').addClass('hidden');
                        $('#masterhider').removeClass('hidden');
                        autoslide();
                    }
                });


                filliteminputval(itemnum);  //file the item input field
                cleanurl();  //clean the URL of post data        


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

            $(document).on("click", ".clicktotoggle-chevron-test", function (e) {
                $(this).toggleClass('fa-chevron-circle-down fa-chevron-circle-up');
                $(this).closest('.media').next('.hiddencostdetail').slideToggle();
            });

            function autoslide() {
                $(".hiddencostdetail").slideToggle();
            }

        </script>
        <script>
            $("#reports").addClass('active');
        </script>
    </body>
</html>
