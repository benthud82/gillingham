
<?php
ini_set('max_execution_time', 99999);
ini_set('memory_limit', '-1');

include_once '../connection/NYServer.php';
$var_userid = $_POST['userid'];
$var_item = $_POST['itemcode'];


$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];

$startdate = date('Y-m-d', strtotime('-90 days'));


//detail data query
$result1 = $conn1->prepare("SELECT 
                                                    replen_item,
                                                    replen_date,
                                                    replen_code,
                                                    replen_fromloc,
                                                    replen_toloc,
                                                    replen_qty
                                                FROM
                                                    gillingham.replen
                                                WHERE
                                                    replen_pkgu = 'EA'
                                                        AND replen_toloc < '69*'
                                                        AND replen_date >= '$startdate'
                                                        AND replen_item = $var_item
                                                ORDER BY replen_date desc");
$result1->execute();
$result1array = $result1->fetchAll(pdo::FETCH_ASSOC);


//summary data query
$result2 = $conn1->prepare("SELECT 
                                                    count(*) as TOTAL
                                                    FROM
                                                        gillingham.replen
                                                    WHERE
                                                        replen_pkgu = 'EA'
                                                            AND replen_toloc < '69*'
                                                            AND replen_date >= '$startdate'
                                                            AND replen_item = $var_item");
$result2->execute();
$result2array = $result2->fetchAll(pdo::FETCH_ASSOC);
?>
<div class="" id="divtablecontainer">
    <!--start of div for summary data-->
    <div class="row">
        <div class="col-lg-6"> <div class="h3"><?php echo 'Est. Yearly Moves: ' . $result2array[0]['TOTAL'] * 4; ?> </div> </div>
    </div>

    <!--start of div table for detail data-->

    <div  class='col-sm-12 col-md-12 col-lg-12 print-1wide'  style="float: none;">

        <div class='widget-content widget-table'  style="position: relative;">
            <div class='divtable'>
                <div class='divtableheader'>
                    <div class='divtabletitle width16_66' style="cursor: default">Item</div>
                    <div class='divtabletitle width16_66' style="cursor: default">Date</div>
                    <div class='divtabletitle width16_66' style="cursor: default">Type</div>
                    <div class='divtabletitle width16_66' style="cursor: default">From Loc</div>
                    <div class='divtabletitle width16_66' style="cursor: default">To Loc</div>
                    <div class='divtabletitle width16_66' style="cursor: default">Quantity</div>
                </div>
                <?php foreach ($result1array as $key => $value) { ?>
                    <div class='divtablerow itemdetailexpand'>

                                                        <!--<div class='divtabledata width10 '><i class="fa fa-plus-square fa-lg " style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Toggle Detail"></i></div>-->
                        <div class='divtabledata width16_66'> <?php echo $result1array[$key]['replen_item']; ?> </div>
                        <div class='divtabledata width16_66'> <?php echo $result1array[$key]['replen_date']; ?> </div>
                        <div class='divtabledata width16_66'> <?php echo $result1array[$key]['replen_code']; ?> </div>
                        <div class='divtabledata width16_66'> <?php echo $result1array[$key]['replen_fromloc']; ?> </div>
                        <div class='divtabledata width16_66'> <?php echo $result1array[$key]['replen_toloc']; ?> </div>
                        <div class='divtabledata width16_66'> <?php echo $result1array[$key]['replen_qty']; ?> </div>

                    </div>

                <?php } ?>
            </div>
        </div>

    </div>    
</div>    

