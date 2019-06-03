<?php

//nclude_once '../globalincludes/google_connect.php';
include_once '../connection/NYServer.php';
include_once '../globalfunctions/slottingfunctions.php';

ini_set('memory_limit', '-1'); //max size 32m
ini_set('max_execution_time', 99999);

$truncatetables = array('curr_impliedmoves');
foreach ($truncatetables as $value) {
    $querydelete2 = $conn1->prepare("TRUNCATE gillingham.$value");
    $querydelete2->execute();
}


$sql = $conn1->prepare("SELECT 
                            slotmaster_loc,
                            slotmaster_item,
                            slotmaster_normreplen,
                            slotmaster_maxreplen,
                            AVG_DAILY_UNIT,
                            AVG_INVOH
                        FROM
                            gillingham.slotmaster
                                JOIN
                            gillingham.nptsld ON ITEM = slotmaster_item
                                AND PKTYPE = slotmaster_pkgu");
$sql->execute();
$movearray = $sql->fetchAll(pdo::FETCH_ASSOC);

$columns = 'impmoves_loc, impmoves_item, impmoves_impmoves';
$maxrange = 9999;
$counter = 0;
$rowcount = count($movearray);

do {
    if ($maxrange > $rowcount) {  //prevent undefined offset
        $maxrange = $rowcount - 1;
    }

    $data = array();
    $values = array();
    while ($counter <= $maxrange) {
        $slotmaster_loc = $movearray[$counter]['slotmaster_loc'];
        $slotmaster_item = $movearray[$counter]['slotmaster_item'];
        $slotmaster_normreplen = $movearray[$counter]['slotmaster_normreplen'];
        $slotmaster_maxreplen = $movearray[$counter]['slotmaster_maxreplen'];
        $max = $slotmaster_normreplen + $slotmaster_maxreplen;
        $daily_ship_qty = $movearray[$counter]['AVG_DAILY_UNIT'];
        $avginv = $movearray[$counter]['AVG_INVOH'];


        $impliedmoves = _implied_daily_moves_nomin($max, $daily_ship_qty, $avginv);




        $data[] = "('$slotmaster_loc', $slotmaster_item, '$impliedmoves')";
        $counter += 1;
    }


    $values = implode(',', $data);

    if (empty($values)) {
        break;
    }
    $sql = "INSERT INTO gillingham.curr_impliedmoves ($columns) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();
    $maxrange += 10000;
} while ($counter <= $rowcount);

//update implied moves in slotmaster table
$sqlmerge2 = "INSERT INTO gillingham.slotmaster (slotmaster_branch,
slotmaster_loc,
slotmaster_item,
slotmaster_grhigh,
slotmaster_grdeep,
slotmaster_grwide,
slotmaster_grcube,
slotmaster_usehigh,
slotmaster_usedeep,
slotmaster_usewide,
slotmaster_usecube,
slotmaster_pkgu,
slotmaster_chargroup,
slotmaster_pickzone,
slotmaster_dimgroup,
slotmaster_normreplen,
slotmaster_minreplen,
slotmaster_maxreplen,
slotmaster_allowpick,
slotmaster_allowreplen,
slotmaster_tier,
slotmaster_bay,
slotmaster_impmoves) 
(SELECT slotmaster_branch,
slotmaster_loc,
slotmaster_item,
slotmaster_grhigh,
slotmaster_grdeep,
slotmaster_grwide,
slotmaster_grcube,
slotmaster_usehigh,
slotmaster_usedeep,
slotmaster_usewide,
slotmaster_usecube,
slotmaster_pkgu,
slotmaster_chargroup,
slotmaster_pickzone,
slotmaster_dimgroup,
slotmaster_normreplen,
slotmaster_minreplen,
slotmaster_maxreplen,
slotmaster_allowpick,
slotmaster_allowreplen,
slotmaster_tier,
slotmaster_bay,
impmoves_impmoves FROM gillingham.curr_impliedmoves JOIN gillingham.slotmaster on slotmaster_loc = impmoves_loc and impmoves_item = slotmaster_item) 
ON DUPLICATE KEY UPDATE slotmaster_impmoves=VALUES(slotmaster_impmoves);";
$querymerge2 = $conn1->prepare($sqlmerge2);
$querymerge2->execute();



