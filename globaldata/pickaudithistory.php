
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
                                                        PICKDATE, LOCATION, PKTYPE, UNITS
                                                        FROM
                                                            gillingham.gill_raw
                                                        WHERE
                                                            ITEM = $var_item
                                                                AND PICKDATE >= DATE_ADD(CURDATE(), INTERVAL - 90 DAY)
                                                                and PKTYPE = 'EA'
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
        and PKTYPE = 'EA'
        AND PICKDATE >= DATE_ADD(CURDATE(), INTERVAL - 90 DAY);");
$result2->execute();
$result2array = $result2->fetchAll(pdo::FETCH_ASSOC);
?>
<div class="" id="divtablecontainer_pick">
    <!--start of div for summary data-->
    <div class="row">
        <div class="col-lg-3"> <div class="h5"><?php echo 'Pick Count: ' . $result2array[0]['PICKCOUNT']; ?> </div> </div>
        <div class="col-lg-3"> <div class="h5"><?php echo 'Est. Yearly Picks: ' . ($result2array[0]['PICKCOUNT']) * 4; ?> </div> </div>
        <div class="col-lg-3"> <div class="h5"><?php echo 'Est. Daily Picks: ' . number_format(($result2array[0]['PICKCOUNT']) / (64.28), 2); ?> </div> </div>
    </div>


    <!--start of div table for detail data-->

    <div  class='col-sm-12 col-md-12 col-lg-12 print-1wide'  style="float: none;">

        <div class='widget-content widget-table'  style="position: relative;">
            <div class='divtable'>
                <div class='divtableheader'>
                    <div class='divtabletitle width25' style="cursor: default">Pick Date</div>
                    <div class='divtabletitle width25' style="cursor: default">Location</div>
                    <div class='divtabletitle width25' style="cursor: default">Package Type</div>
                    <div class='divtabletitle width25' style="cursor: default">Units</div>

                </div>
                <?php foreach ($result1array as $key => $value) { ?>
                    <div class='divtablerow itemdetailexpand'>

                                    <!--<div class='divtabledata width10 '><i class="fa fa-plus-square fa-lg " style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Toggle Detail"></i></div>-->
                        <div class='divtabledata width25'> <?php echo trim($result1array[$key]['PICKDATE']); ?> </div>
                        <div class='divtabledata width25'> <?php echo trim($result1array[$key]['LOCATION']); ?> </div>
                        <div class='divtabledata width25'> <?php echo trim($result1array[$key]['PKTYPE']); ?> </div>
                        <div class='divtabledata width25'> <?php echo trim($result1array[$key]['UNITS']); ?> </div>

                    </div>

                <?php } ?>
            </div>
        </div>

    </div>    
</div>    

