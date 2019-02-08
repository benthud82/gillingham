
<?php
ini_set('max_execution_time', 99999);

include_once '../connection/connection_details.php';
$var_date = date('Y-m-d', strtotime($_POST['datesel']));
$baycode = ($_POST['baycode']);


$result1 = $conn1->prepare("SELECT 
                                                            ITEM, L.LOCATION, COUNT(*) AS TOTPICKS
                                                        FROM
                                                            gillingham.gill_raw
                                                                JOIN
                                                            gillingham.slotmaster S ON slotmaster_item = ITEM
                                                                JOIN
                                                            gillingham.bay_location L ON L.LOCATION = slotmaster_loc
                                                        WHERE
                                                            PICKDATE = '$var_date'
                                                                AND L.BAY = '$baycode'
                                                        GROUP BY ITEM , LOCATION
                                                        ORDER BY COUNT(*) DESC");
$result1->execute();
$result1array = $result1->fetchAll(pdo::FETCH_ASSOC);
?>

    
<!--start of div table-->
<div class="" id="divtablecontainer">
    <div  class='col-sm-12 col-md-12 col-lg-12 print-1wide'  style="float: none;">

        <div class='widget-content widget-table'  style="position: relative;">
            <div class='divtable'>
                <div class='divtableheader'>
                    <div class='divtabletitle width15' data-toggle='tooltip' title='Click on item for item query' data-placement='top' data-container='body' style="cursor: default">Item</div>
                    <div class='divtabletitle width15' style="cursor: default">Location</div>
                    <div class='divtabletitle width15' style="cursor: default">Pick Count</div>
                </div>
                <?php foreach ($result1array as $key => $value) { ?>
                    <div class='divtablerow itemdetailexpand'>

                            <!--<div class='divtabledata width10 '><i class="fa fa-plus-square fa-lg " style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Toggle Detail"></i></div>-->
                        <div class='divtabledata width15' ><a href="itemquery.php?itemnum=<?php echo $result1array[$key]['ITEM']; ?>&userid=<?php echo '' ?>" target="_blank"><?php echo $result1array[$key]['ITEM']; ?></a></div>
                        <div class='divtabledata width15'> <?php echo $result1array[$key]['LOCATION']; ?> </div>
                        <div class='divtabledata width15'> <?php echo $result1array[$key]['TOTPICKS']; ?> </div>
                    </div>

                <?php } ?>
            </div>
        </div>

    </div>    
</div>    

