
<?php
include_once '../connection/NYServer.php';
ini_set('max_execution_time', 99999);
ini_set('memory_limit', '-1');
$var_userid = $_POST['userid'];
$var_item = $_POST['itemcode'];
//$var_lseorcse = $_POST['lseorcse'];
//if ($var_lseorcse == 'pickauditclicklse') {
//    $zonesql = " PDBXSZ <> 'CSE' ";
//} else {
//    $zonesql =  " PDBXSZ = 'CSE' ";
//}

$zonesql = 'LSE';
$startdate = date('Y-m-d', strtotime('-90 days'));

//detail data query
$result1 = $conn1->prepare("SELECT 
                                                                            '-' as PDWCS, '-' as PDWKNO, PICKDATE as ORDDATE, '-' as PDBXSZ, LOCATION as PDLOC, PKGU as PDPKGU, UNITS as PDPCKS
                                                                        FROM
                                                                            gillingham.gill_raw
                                                                        WHERE
                                                                            ITEM = $var_item
                                                                                AND PICKDATE >= DATE_ADD(CURDATE(), INTERVAL - 90 DAY)
                                                                                ORDER BY PICKDATE DESC;");
$result1->execute();
$result1array = $result1->fetchAll(pdo::FETCH_ASSOC);

//summary data query
$result2 = $conn1->prepare("SELECT 
    count(*) as PICKCOUNT
FROM
    gillingham.gill_raw
WHERE
    ITEM = $var_item
        AND PICKDATE >= DATE_ADD(CURDATE(), INTERVAL - 90 DAY);");
$result2->execute();
$result2array = $result2->fetchAll(pdo::FETCH_ASSOC);
?>
<div class="" id="divtablecontainer_pick">
<!--start of div for summary data-->
<div class="row">
    <div class="col-lg-3"> <div class="h5"><?php echo 'Pick Count: ' . $result2array[0]['PICKCOUNT']; ?> </div> </div>
    <div class="col-lg-3"> <div class="h5"><?php echo 'Est. Yearly Picks: ' . ($result2array[0]['PICKCOUNT']) * 4; ?> </div> </div>
    <div class="col-lg-3"> <div class="h5"><?php echo 'Est. Daily Picks: ' . number_format(($result2array[0]['PICKCOUNT']) / (64.28),2); ?> </div> </div>
</div>


<!--start of div table for detail data-->

    <div  class='col-sm-12 col-md-12 col-lg-12 print-1wide'  style="float: none;">

        <div class='widget-content widget-table'  style="position: relative;">
            <div class='divtable'>
                <div class='divtableheader'>
                    <div class='divtabletitle width12_5' style="cursor: default">WCS#</div>
                    <div class='divtabletitle width12_5' style="cursor: default">W/O#</div>
                    <div class='divtabletitle width12_5' style="cursor: default">Order Date</div>
                    <div class='divtabletitle width12_5' style="cursor: default">Box Size</div>
                    <div class='divtabletitle width12_5' style="cursor: default">Pick Location</div>
                    <div class='divtabletitle width12_5' style="cursor: default">Pkgu</div>
                    <div class='divtabletitle width12_5' style="cursor: default">Ship Quantity</div>
                </div>
<?php foreach ($result1array as $key => $value) { ?>
                    <div class='divtablerow itemdetailexpand'>

                                <!--<div class='divtabledata width10 '><i class="fa fa-plus-square fa-lg " style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Toggle Detail"></i></div>-->
                        <div class='divtabledata width12_5'> <?php echo trim($result1array[$key]['PDWCS']); ?> </div>
                        <div class='divtabledata width12_5'> <?php echo trim($result1array[$key]['PDWKNO']); ?> </div>
                        <div class='divtabledata width12_5'> <?php echo date('Y-m-d', strtotime(trim($result1array[$key]['ORDDATE']))); ?> </div>
                        <div class='divtabledata width12_5'> <?php echo trim($result1array[$key]['PDBXSZ']); ?> </div>
                        <div class='divtabledata width12_5'> <?php echo trim($result1array[$key]['PDLOC']); ?> </div>
                        <div class='divtabledata width12_5'> <?php echo trim($result1array[$key]['PDPKGU']); ?> </div>
                        <div class='divtabledata width12_5'> <?php echo trim($result1array[$key]['PDPCKS']); ?> </div>
                    </div>

<?php } ?>
            </div>
        </div>

    </div>    
</div>    

