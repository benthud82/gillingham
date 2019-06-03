
<?php
include_once '../connection/NYServer.php';

$vectorid = intval($_POST['vectorid']);
$baymodal = $_POST['baymodal'];
$yposmodal = intval($_POST['yposmodal']);
$xposmodal = intval($_POST['xposmodal']);
$bayheightmodal = intval($_POST['bayheightmodal']);
$baywidthmodal = intval($_POST['baywidthmodal']);
$walkmodal = intval($_POST['walkmodal']);
$cselsemodal = ($_POST['cselsemodal']);
$tiermodal = $_POST['tiermodal'];
$whse = intval($_POST['whse']);

if ($vectorid == 0) {
    $sql = "INSERT INTO gillingham.vectormap (ID, VECTWHSE, BAY, YPOS, XPOS, BAYHEIGHT, BAYWIDTH, WALKFEET, CSLS, TIER) VALUES ( $vectorid, $whse, '$baymodal', $yposmodal, $xposmodal, $bayheightmodal, $baywidthmodal, $walkmodal,  '$cselsemodal', '$tiermodal')";
    $query = $conn1->prepare($sql);
    $query->execute();
    $masterinsertsuccess = 1;
} else {
    $sql = "UPDATE gillingham.vectormap SET VECTWHSE = $whse, BAY = '$baymodal', YPOS = $yposmodal, XPOS = $xposmodal, BAYHEIGHT = $bayheightmodal, BAYWIDTH = $baywidthmodal, WALKFEET = $walkmodal,  CSLS = '$cselsemodal', TIER = '$tiermodal' WHERE ID = $vectorid";
    $query = $conn1->prepare($sql);
    $query->execute();
    $masterinsertsuccess = 1;
}


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