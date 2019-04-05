<?php

//include_once '../globalincludes/google_connect.php';
include_once '../connection/NYServer.php';
include_once '../globalfunctions/slottingfunctions.php';

ini_set('memory_limit', '-1'); //max size 32m
ini_set('max_execution_time', 99999);

$sql = $conn1->prepare("SELECT 
                                                slotmaster_branch,
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
                                                slotmaster_impmoves,
                                                AVG_DAILY_UNIT,
                                                AVG_INVOH
                                            FROM
                                                gillingham.slotmaster
                                                    JOIN
                                                gillingham.nptsld ON ITEM = slotmaster_item
                                                    AND PKTYPE = slotmaster_pkgu");
$sql->execute();
$movearray = $sql->fetchAll(pdo::FETCH_ASSOC);

foreach ($movearray as $key => $value) {
    
    
    
    
    
    
    
    
    $impliedmoves = _implied_daily_moves_nomin($max, $daily_ship_qty, $avginv);
}


