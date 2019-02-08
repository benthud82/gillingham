<?php

//haven't started on this.  This is pulled from the US main update php file

$whssel = 'GB0001';


ini_set('max_execution_time', 99999);
ini_set('memory_limit', '-1');
//main core file to update slotting recommendation file --MY_NPFMVC--
//global includes

include_once '../globalfunctions/slottingfunctions.php';
include_once '../globalfunctions/newitem.php';

include_once 'sql_dailypick.php';  //pulls in variable $sql_dailypick to calculate daily pick quantites
//assign columns variable for my_npfmvc table
$columns = 'WAREHOUSE, ITEM_NUMBER, PACKAGE_UNIT, PACKAGE_TYPE, CUR_LOCATION, DAYS_FRM_SLE, AVGD_BTW_SLE, AVG_INV_OH, NBR_SHIP_OCC, PICK_QTY_MN, PICK_QTY_SD, SHIP_QTY_MN, SHIP_QTY_SD,CPCEPKU,CPCCPKU,CPCFLOW,CPCTOTE,CPCSHLF,CPCROTA,CPCESTK,CPCLIQU,CPCELEN,CPCEHEI,CPCEWID,CPCCLEN,CPCCHEI,CPCCWID,LMHIGH,LMDEEP,LMWIDE,LMVOL9,LMTIER,LMGRD5,DLY_CUBE_VEL,DLY_PICK_VEL,SUGGESTED_TIER,SUGGESTED_GRID5,SUGGESTED_DEPTH,SUGGESTED_MAX,SUGGESTED_MIN,SUGGESTED_SLOTQTY,SUGGESTED_IMPMOVES,CURRENT_IMPMOVES,SUGGESTED_NEWLOCVOL,SUGGESTED_DAYSTOSTOCK, AVG_DAILY_PICK, AVG_DAILY_UNIT, VCBAY, JAX_ENDCAP';

include_once '../connection/connection_details.php';
//$whsearray = array(2, 3, 6, 7, 9, 11, 12, 16);
//Delete inventory restricted items
$sqldelete3 = "DELETE FROM gillingham.inventory_restricted WHERE WHSE_INV_REST = '$whssel';";
$querydelete3 = $conn1->prepare($sqldelete3);
$querydelete3->execute();

$sqldelete = "DELETE FROM gillingham.my_npfmvc WHERE PACKAGE_TYPE in ('EA')";
$querydelete = $conn1->prepare($sqldelete);
$querydelete->execute();

//--pull in available tiers--
$alltiersql = $conn1->prepare("SELECT 
                                                            slotmaster_branch AS TIER_WHS,
                                                            slotmaster_tier AS TIER_TIER,
                                                            COUNT(*) AS TIER_COUNT
                                                        FROM
                                                            gillingham.slotmaster
                                                        WHERE
                                                            slotmaster_branch = '$whssel'
                                                                AND slotmaster_allowpick = 'Y'
                                                        GROUP BY slotmaster_branch , slotmaster_tier; ");  //$orderby pulled from: include 'slopecat_switch_orderby.php';
$alltiersql->execute();
$alltierarray = $alltiersql->fetchAll(pdo::FETCH_ASSOC);

//--pull in volume by tier--
$allvolumesql = $conn1->prepare("SELECT 
                                                                    slotmaster_branch AS LMWHSE,
                                                                    slotmaster_tier AS LMTIER,
                                                                    sum(slotmaster_usecube) * 1000 AS TIER_VOL
                                                                FROM
                                                                    gillingham.slotmaster
                                                                WHERE
                                                                    slotmaster_branch = '$whssel'
                                                                        AND slotmaster_allowpick = 'Y'
                                                                GROUP BY slotmaster_branch , slotmaster_tier; ");  //$orderby pulled from: include 'slopecat_switch_orderby.php';
$allvolumesql->execute();
$allvolumearray = $allvolumesql->fetchAll(pdo::FETCH_ASSOC);


//Assign items on hold
include_once 'itemsonhold.php'; 

//call L01 Update logic
$L01key = array_search('L01', array_column($alltierarray, 'TIER_TIER')); //Find 'L01' associated key   ******NEED TO CORRECT ITEMSONHOLD LOGIC CALL ********
$L01onholdkey = array_search('L01', array_column($holdvolumearray, 'SUGGESTED_TIER'));
if ($L01onholdkey !== FALSE) {
    $L01onholdcount = intval($holdvolumearray[$L01onholdkey]['ASSCOUNT']);
}
if ($L01key !== FALSE) {
    include 'L01update.php';
}

//call L02 Update logic
include 'L02update.php';

//For Gillingham, have to recommend different sizes for endcaps with 40 cm deep shelves.  Call L04 endcaps first, and assign based off available volume for endcaps
include 'L04gilendcap.php';

//Standard Blue bin update
include 'L04update.php';

//Slot any item that currently has a location but no sales data
include 'nosalesupdate.php';


