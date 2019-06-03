
<?php
include_once '../connection/NYServer.php';

$location = ($_POST['locid']);

    $sql = "DELETE FROM gillingham.bay_location WHERE LOCATION = '$location'";
    $query = $conn1->prepare($sql);
    $query->execute();
    $masterinsertsuccess = 1;



if ($masterinsertsuccess == 1) {
    ?>
    <!-- Progress/Success Modal-->
    <div id="progressmodal_salesplanall" class="modal fade " role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <!--                                <h4 class="modal-title">Mark Salesplan as Audited</h4>-->
                </div>
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>
                <div class="h4"  style="text-align: center">Changes successful!</div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div id="progressmodal_salesplanall" class="modal fade " role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <!--                                <h4 class="modal-title">Mark Salesplan as Audited</h4>-->
                </div>
                <div class="h4"  style="text-align: center">There has been an error!</div>
            </div>
        </div>
    </div>


<?php } ?>
<script>  $('#progressmodal_salesplanall').modal('toggle');</script>