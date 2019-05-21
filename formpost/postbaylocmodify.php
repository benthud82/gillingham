
<?php
include_once '../connection/NYServer.php';

$baylocid = intval($_POST['baylocid']);
$locmodal_bayloc = $_POST['locmodal_bayloc'];
$dimgroupmodal_bayloc = ($_POST['dimgroupmodal_bayloc']);
$baymodal_bayloc = ($_POST['baymodal_bayloc']);
$waklbaymodal_bayloc = intval($_POST['waklbaymodal_bayloc']);
if ($waklbaymodal_bayloc < 10) {
    $waklbaymodal_insert = '0' . $waklbaymodal_bayloc;
} else {
    $waklbaymodal_insert = $waklbaymodal_bayloc;
}
$whse = 'GB0001';

$sql = "INSERT INTO gillingham.bay_location (WHSE, LOCATION, DIMGROUP, BAY, WALKBAY) VALUES ('$whse', '$locmodal_bayloc', '$dimgroupmodal_bayloc', '$baymodal_bayloc', '$waklbaymodal_insert') 
                ON DUPLICATE KEY UPDATE DIMGROUP=VALUES(DIMGROUP), BAY=VALUES(BAY), WALKBAY=VALUES(WALKBAY)";
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