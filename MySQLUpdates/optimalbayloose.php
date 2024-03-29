<?php

$whssel = 'GB0001';

ini_set('max_execution_time', 99999);
ini_set('memory_limit', '-1');
//include_once '../globalincludes/google_connect.php';
require '../../connections/conn_slotting.php';
include_once '../globalfunctions/slottingfunctions.php';
//include_once 'sql_dailypick.php';  //pulls in variable $sql_dailypick to calculate daily pick quantites

$OPT_BUILDING = intval(1);
$sqldelete = "TRUNCATE  gillingham.optimalbay";
$querydelete = $conn1->prepare($sqldelete);
$querydelete->execute();

$baycube = $conn1->prepare("SELECT 
                                                            WALKBAY AS BAY,
                                                            SUM(A.CUBE) AS GRIDVOL,
                                                            SUM(A.USE_CUBE) AS USEVOL
                                                        FROM
                                                            gillingham.location_master A
                                                                JOIN
                                                            gillingham.bay_location B ON A.LOCATION = B.LOCATION
                                                                JOIN
                                                            gillingham.vectormap C ON C.BAY = B.BAY
                                                        WHERE
                                                            A.TIER = 'BIN'
                                                                AND WALKBAY NOT IN ('CC' , 'L0', 'R0', 'R1', 'R2')
                                                        GROUP BY WALKBAY");
$baycube->execute();
$baycubearray = $baycube->fetchAll(pdo::FETCH_ASSOC);

//Result set for items to go to ECAP
$ppc_ecap = $conn1->prepare("SELECT DISTINCT
                                                    A.WAREHOUSE AS OPT_WHSE,
                                                    A.ITEM_NUMBER AS OPT_ITEM,
                                                    A.PACKAGE_UNIT AS OPT_PKGU,
                                                    A.CUR_LOCATION AS OPT_LOC,
                                                    A.PACKAGE_TYPE AS OPT_CSLS,
                                                    PPC_CALC AS OPT_PPCCALC,
                                                    V.WALKFEET AS CURWALKFEET,
                                                    A.SUGGESTED_GRID5 as OPT_NEWGRID,
                                                    A.SUGGESTED_DEPTH as OPT_NDEP,
                                                    A.AVG_DAILY_PICK as OPT_DAILYPICKS,
                                                    A.AVG_DAILY_PICK as OPT_DAILYPICKS,
                                                    SUGGESTED_NEWLOCVOL as OPT_NEWGRIDVOL,
                                                    HOLDTIER,
                                                    HOLDGRID,
                                                    HOLDLOCATION,
                                                    L.WALKBAY AS CURR_BAY
                                                FROM
                                                    gillingham.my_npfmvc A
                                                        JOIN
                                                    gillingham.bay_location L ON L.LOCATION = A.CUR_LOCATION
                                                        LEFT JOIN
                                                    gillingham.vectormap V ON L.BAY = V.BAY
                                                        LEFT JOIN
                                                    gillingham.item_settings S ON S.ITEM = A.ITEM_NUMBER
                                                WHERE
                                                    SUGGESTED_TIER = 'ECAP'
                                                ORDER BY PPC_CALC DESC , A.SUGGESTED_NEWLOCVOL ASC");
$ppc_ecap->execute();
$ppc_ecap_array = $ppc_ecap->fetchAll(pdo::FETCH_ASSOC);

foreach ($ppc_ecap_array as $key => $value) {
//is there a hold location?
    $testloc = $ppc_ecap_array[$key]['HOLDLOCATION'];

    $OPT_NEWGRID = $ppc_ecap_array[$key]['OPT_NEWGRID'];
    $OPT_NDEP = intval($ppc_ecap_array[$key]['OPT_NDEP']);
    $OPT_LOCATION = '';

    $OPT_Shouldwalkfeet = 0;

    $OPT_WHSE = intval($ppc_ecap_array[$key]['OPT_WHSE']);
    $OPT_ITEM = intval($ppc_ecap_array[$key]['OPT_ITEM']);
    $OPT_PKGU = intval($ppc_ecap_array[$key]['OPT_PKGU']);
    $OPT_LOC = $ppc_ecap_array[$key]['OPT_LOC'];
    $OPT_CSLS = $ppc_ecap_array[$key]['OPT_CSLS'];
    $OPT_DAILYPICKS = number_format($ppc_ecap_array[$key]['OPT_DAILYPICKS'], 2);
    $OPT_PPCCALC = $ppc_ecap_array[$key]['OPT_PPCCALC'];
    $currentfeetperpick = intval($ppc_ecap_array[$key]['CURWALKFEET']);
    $OPT_CURRBAY = intval($ppc_ecap_array[$key]['CURR_BAY']);
    $OPT_OPTBAY = intval(0);

    $walkcostarray = _walkcost_feet($currentfeetperpick, $OPT_Shouldwalkfeet, $OPT_DAILYPICKS);

    $OPT_CURRDAILYFT = ($walkcostarray['CURR_FT_PER_DAY']);
    $OPT_SHLDDAILYFT = ($walkcostarray['SHOULD_FT_PER_DAY']);
    $OPT_ADDTLFTPERPICK = ($walkcostarray['ADDTL_FT_PER_PICK']);
    $OPT_ADDTLFTPERDAY = ($walkcostarray['ADDTL_FT_PER_DAY']);
    $OPT_WALKCOST = $walkcostarray['ADDTL_COST_PER_YEAR'];

    $data[] = "($OPT_WHSE, $OPT_ITEM, $OPT_PKGU, '$OPT_LOC',  '$OPT_CSLS',  $OPT_PPCCALC, $OPT_OPTBAY, $OPT_CURRBAY, '$OPT_CURRDAILYFT', '$OPT_SHLDDAILYFT', '$OPT_ADDTLFTPERPICK', '$OPT_ADDTLFTPERDAY', $OPT_WALKCOST, '$OPT_LOCATION',$OPT_BUILDING)";
}
$columns = 'OPT_WHSE, OPT_ITEM, OPT_PKGU, OPT_LOC, OPT_CSLS, OPT_PPCCALC, OPT_OPTBAY, OPT_CURRBAY, OPT_CURRDAILYFT, OPT_SHLDDAILYFT, OPT_ADDTLFTPERPICK, OPT_ADDTLFTPERDAY, OPT_WALKCOST, OPT_LOCATION, OPT_BUILDING';
$valuesl01 = array();
$valuesl01 = implode(',', $data);

if (!empty($valuesl01)) {


    $sql = "INSERT IGNORE INTO gillingham.optimalbay ($columns) VALUES $valuesl01";
    $query = $conn1->prepare($sql);
    $query->execute();
}




//Result set for PPC sorted by highest PPC for items currently in BIN
$ppc = $conn1->prepare("SELECT DISTINCT
                                                    A.WAREHOUSE AS OPT_WHSE,
                                                    A.ITEM_NUMBER AS OPT_ITEM,
                                                    A.PACKAGE_UNIT AS OPT_PKGU,
                                                    A.CUR_LOCATION AS OPT_LOC,
                                                    A.PACKAGE_TYPE AS OPT_CSLS,
                                                    PPC_CALC AS OPT_PPCCALC,
                                                    V.WALKFEET AS CURWALKFEET,
                                                    A.SUGGESTED_GRID5 as OPT_NEWGRID,
                                                    A.SUGGESTED_DEPTH as OPT_NDEP,
                                                    A.AVG_DAILY_PICK as OPT_DAILYPICKS,
                                                    A.AVG_DAILY_PICK as OPT_DAILYPICKS,
                                                    SUGGESTED_NEWLOCVOL as OPT_NEWGRIDVOL,
                                                    HOLDTIER,
                                                    HOLDGRID,
                                                    HOLDLOCATION,
                                                    case when L.WALKBAY is null then 7 else L.WALKBAY end AS CURR_BAY
                                                FROM
                                                    gillingham.my_npfmvc A
                                                        JOIN
                                                    gillingham.bay_location L ON L.LOCATION = A.CUR_LOCATION
                                                        LEFT JOIN
                                                    gillingham.vectormap V ON L.BAY = V.BAY
                                                        LEFT JOIN
                                                    gillingham.item_settings S ON S.ITEM = A.ITEM_NUMBER
                                                WHERE
                                                    SUGGESTED_TIER = 'BIN'
                                                ORDER BY PPC_CALC DESC , A.SUGGESTED_NEWLOCVOL ASC");
$ppc->execute();
$ppcarray = $ppc->fetchAll(pdo::FETCH_ASSOC);

//Result set for PPC sorted by highest PPC for items currently in L01
$ppcL01 = $conn1->prepare("SELECT 
                                                            A.WAREHOUSE AS OPT_WHSE,
                                                            A.ITEM_NUMBER AS OPT_ITEM,
                                                            A.PACKAGE_UNIT AS OPT_PKGU,
                                                            A.CUR_LOCATION AS OPT_LOC,
                                                            A.PACKAGE_TYPE AS OPT_CSLS,
                                                            PPC_CALC AS OPT_PPCCALC,
                                                            V.WALKFEET AS CURWALKFEET,
                                                            A.SUGGESTED_GRID5 as OPT_NEWGRID,
                                                            A.SUGGESTED_DEPTH as OPT_NDEP,
                                                            A.AVG_DAILY_PICK as OPT_DAILYPICKS,
                                                            HOLDTIER,
                                                            HOLDGRID,
                                                            HOLDLOCATION,
                                                            L.WALKBAY AS CURR_BAY
                                                        FROM
                                                            gillingham.my_npfmvc A
                                                                JOIN
                                                            gillingham.bay_location L ON L.LOCATION = A.CUR_LOCATION
                                                                LEFT JOIN
                                                            gillingham.vectormap V ON L.BAY = V.BAY
                                                                LEFT JOIN
                                                            gillingham.item_settings S ON S.ITEM = A.ITEM_NUMBER
                                                        WHERE
                                                            SUGGESTED_TIER = 'PALL'
                                                        ORDER BY PPC_CALC DESC , A.SUGGESTED_NEWLOCVOL ASC");
$ppcL01->execute();
$ppcL01array = $ppcL01->fetchAll(pdo::FETCH_ASSOC);

//L01 Locations in ascending walkfeet to match with highest picked L01 Recs
$L01Locs = $conn1->prepare("SELECT 
                                                            M.LOCATION AS LMLOC,
                                                            V.WALKFEET,
                                                            M.LOC_DIM as LMGRD5,
                                                            M.USE_DEPTH AS LMDEEP
                                                        FROM
                                                            gillingham.location_master M
                                                                JOIN
                                                            gillingham.bay_location B ON B.LOCATION = M.LOCATION
                                                                JOIN
                                                            gillingham.vectormap V ON V.BAY = B.BAY
                                                        WHERE
                                                            M.TIER = 'PALL'
                                                                AND M.LOCATION NOT IN (SELECT 
                                                                    HOLDLOCATION
                                                                FROM
                                                                    gillingham.item_settings)");
$L01Locs->execute();
$L01Locsarray = $L01Locs->fetchAll(pdo::FETCH_ASSOC);
$data = array();
//assign L01s to specific location
foreach ($ppcL01array as $key => $value) {
//is there a hold location?
    $testloc = $ppcL01array[$key]['HOLDLOCATION'];

    $OPT_NEWGRID = $ppcL01array[$key]['OPT_NEWGRID'];
    $OPT_NDEP = intval($ppcL01array[$key]['OPT_NDEP']);

    if (!is_null($testloc) && $testloc <> '') {
        $OPT_LOCATION = $testloc;
    } else if (!empty($L01Locsarray)) {
        //need to verify the location size matches

        foreach ($L01Locsarray as $key2 => $value) {//loop through L01 non-assigned grids
            $l01grid = $L01Locsarray[$key2]['LMGRD5'];
            $l01depth = intval($L01Locsarray[$key2]['LMDEEP']);
            if ($OPT_NEWGRID == $l01grid && $l01depth == $OPT_NDEP) {
                $OPT_LOCATION = $L01Locsarray[$key2]['LMLOC'];
                $OPT_Shouldwalkfeet = $L01Locsarray[$key2]['WALKFEET'];  //Optimal walk feet per pick
                unset($L01Locsarray[$key2]);
                $L01Locsarray = array_values($L01Locsarray);
                break;
            }
        }
    } else {
        $OPT_LOCATION = '';
    }


    $OPT_WHSE = intval($ppcL01array[$key]['OPT_WHSE']);
    $OPT_ITEM = intval($ppcL01array[$key]['OPT_ITEM']);
    $OPT_PKGU = intval($ppcL01array[$key]['OPT_PKGU']);
    $OPT_LOC = $ppcL01array[$key]['OPT_LOC'];
    $OPT_CSLS = $ppcL01array[$key]['OPT_CSLS'];
    $OPT_DAILYPICKS = number_format($ppcL01array[$key]['OPT_DAILYPICKS'], 2);
    $OPT_PPCCALC = $ppcL01array[$key]['OPT_PPCCALC'];
    $currentfeetperpick = intval($ppcL01array[$key]['CURWALKFEET']);
    $OPT_CURRBAY = intval($ppcL01array[$key]['CURR_BAY']);
    $OPT_OPTBAY = intval(0);

    $walkcostarray = _walkcost_feet($currentfeetperpick, $OPT_Shouldwalkfeet, $OPT_DAILYPICKS);

    $OPT_CURRDAILYFT = ($walkcostarray['CURR_FT_PER_DAY']);
    $OPT_SHLDDAILYFT = ($walkcostarray['SHOULD_FT_PER_DAY']);
    $OPT_ADDTLFTPERPICK = ($walkcostarray['ADDTL_FT_PER_PICK']);
    $OPT_ADDTLFTPERDAY = ($walkcostarray['ADDTL_FT_PER_DAY']);
    $OPT_WALKCOST = $walkcostarray['ADDTL_COST_PER_YEAR'];

    $data[] = "($OPT_WHSE, $OPT_ITEM, $OPT_PKGU, '$OPT_LOC',  '$OPT_CSLS',  $OPT_PPCCALC, $OPT_OPTBAY, $OPT_CURRBAY, '$OPT_CURRDAILYFT', '$OPT_SHLDDAILYFT', '$OPT_ADDTLFTPERPICK', '$OPT_ADDTLFTPERDAY', $OPT_WALKCOST, '$OPT_LOCATION',$OPT_BUILDING)";
}
$columns = 'OPT_WHSE, OPT_ITEM, OPT_PKGU, OPT_LOC, OPT_CSLS, OPT_PPCCALC, OPT_OPTBAY, OPT_CURRBAY, OPT_CURRDAILYFT, OPT_SHLDDAILYFT, OPT_ADDTLFTPERPICK, OPT_ADDTLFTPERDAY, OPT_WALKCOST, OPT_LOCATION, OPT_BUILDING';
$valuesl01 = array();
$valuesl01 = implode(',', $data);

if (!empty($valuesl01)) {


    $sql = "INSERT IGNORE INTO gillingham.optimalbay ($columns) VALUES $valuesl01";
    $query = $conn1->prepare($sql);
    $query->execute();
}

$data = array();

//Result set for PPC sorted by highest PPC for items currently in FLOW
$ppcFLOW = $conn1->prepare("SELECT 
                                                            A.WAREHOUSE AS OPT_WHSE,
                                                            A.ITEM_NUMBER AS OPT_ITEM,
                                                            A.PACKAGE_UNIT AS OPT_PKGU,
                                                            A.CUR_LOCATION AS OPT_LOC,
                                                            A.PACKAGE_TYPE AS OPT_CSLS,
                                                            PPC_CALC AS OPT_PPCCALC,
                                                            V.WALKFEET AS CURWALKFEET,
                                                            A.SUGGESTED_GRID5 as OPT_NEWGRID,
                                                            A.SUGGESTED_DEPTH as OPT_NDEP,
                                                            A.AVG_DAILY_PICK as OPT_DAILYPICKS,
                                                            HOLDTIER,
                                                            HOLDGRID,
                                                            HOLDLOCATION,
                                                            L.WALKBAY AS CURR_BAY
                                                        FROM
                                                            gillingham.my_npfmvc A
                                                                JOIN
                                                            gillingham.bay_location L ON L.LOCATION = A.CUR_LOCATION
                                                                LEFT JOIN
                                                            gillingham.vectormap V ON L.BAY = V.BAY
                                                                LEFT JOIN
                                                            gillingham.item_settings S ON S.ITEM = A.ITEM_NUMBER
                                                        WHERE
                                                            SUGGESTED_TIER = 'FLOW'
                                                        ORDER BY PPC_CALC DESC , A.SUGGESTED_NEWLOCVOL ASC");
$ppcFLOW->execute();
$ppcFLOWarray = $ppcFLOW->fetchAll(pdo::FETCH_ASSOC);

foreach ($ppcFLOWarray as $key => $value) {
//is there a hold location?
    $testloc = $ppcFLOWarray[$key]['HOLDLOCATION'];

    $OPT_NEWGRID = $ppcFLOWarray[$key]['OPT_NEWGRID'];
    $OPT_NDEP = intval($ppcFLOWarray[$key]['OPT_NDEP']);
    $OPT_LOCATION = '';

    $OPT_WHSE = intval($ppcFLOWarray[$key]['OPT_WHSE']);
    $OPT_ITEM = intval($ppcFLOWarray[$key]['OPT_ITEM']);
    $OPT_PKGU = intval($ppcFLOWarray[$key]['OPT_PKGU']);
    $OPT_LOC = $ppcFLOWarray[$key]['OPT_LOC'];
    $OPT_CSLS = $ppcFLOWarray[$key]['OPT_CSLS'];
    $OPT_DAILYPICKS = number_format($ppcFLOWarray[$key]['OPT_DAILYPICKS'], 2);
    $OPT_PPCCALC = $ppcFLOWarray[$key]['OPT_PPCCALC'];
    $currentfeetperpick = intval($ppcFLOWarray[$key]['CURWALKFEET']);
    $OPT_CURRBAY = intval($ppcFLOWarray[$key]['CURR_BAY']);
    $OPT_OPTBAY = intval(0);

    $walkcostarray = _walkcost_feet($currentfeetperpick, $OPT_Shouldwalkfeet, $OPT_DAILYPICKS);

    $OPT_CURRDAILYFT = ($walkcostarray['CURR_FT_PER_DAY']);
    $OPT_SHLDDAILYFT = ($walkcostarray['SHOULD_FT_PER_DAY']);
    $OPT_ADDTLFTPERPICK = ($walkcostarray['ADDTL_FT_PER_PICK']);
    $OPT_ADDTLFTPERDAY = ($walkcostarray['ADDTL_FT_PER_DAY']);
    $OPT_WALKCOST = $walkcostarray['ADDTL_COST_PER_YEAR'];

    $data[] = "($OPT_WHSE, $OPT_ITEM, $OPT_PKGU, '$OPT_LOC',  '$OPT_CSLS',  $OPT_PPCCALC, $OPT_OPTBAY, $OPT_CURRBAY, '$OPT_CURRDAILYFT', '$OPT_SHLDDAILYFT', '$OPT_ADDTLFTPERPICK', '$OPT_ADDTLFTPERDAY', $OPT_WALKCOST, '$OPT_LOCATION',$OPT_BUILDING)";
}
$columns = 'OPT_WHSE, OPT_ITEM, OPT_PKGU, OPT_LOC, OPT_CSLS, OPT_PPCCALC, OPT_OPTBAY, OPT_CURRBAY, OPT_CURRDAILYFT, OPT_SHLDDAILYFT, OPT_ADDTLFTPERPICK, OPT_ADDTLFTPERDAY, OPT_WALKCOST, OPT_LOCATION, OPT_BUILDING';
$valuesl01 = array();
$valuesl01 = implode(',', $data);

if (!empty($valuesl01)) {


    $sql = "INSERT IGNORE INTO gillingham.optimalbay ($columns) VALUES $valuesl01";
    $query = $conn1->prepare($sql);
    $query->execute();
}




//if gillingham, assign endcaps
//foreach ($ppcarray_jaxendcap as $key => $value) {
//
//
//    $OPT_TOTIER = $ppcarray_jaxendcap[$key]['OPT_TOTIER'];
//    $OPT_WHSE = intval($ppcarray_jaxendcap[$key]['OPT_WHSE']);
//    $OPT_ITEM = intval($ppcarray_jaxendcap[$key]['OPT_ITEM']);
//    $OPT_PKGU = intval($ppcarray_jaxendcap[$key]['OPT_PKGU']);
//    $OPT_LOC = $ppcarray_jaxendcap[$key]['OPT_LOC'];
//    $OPT_ADBS = intval($ppcarray_jaxendcap[$key]['OPT_ADBS']);
//    $OPT_CSLS = $ppcarray_jaxendcap[$key]['OPT_CSLS'];
//    $OPT_CUBE = intval($ppcarray_jaxendcap[$key]['OPT_CUBE']);
//    $OPT_CURTIER = $ppcarray_jaxendcap[$key]['OPT_CURTIER'];
//    $OPT_NEWGRID = $ppcarray_jaxendcap[$key]['OPT_NEWGRID'];
//    $OPT_NDEP = intval($ppcarray_jaxendcap[$key]['OPT_NDEP']);
//    $OPT_AVGPICK = intval($ppcarray_jaxendcap[$key]['OPT_AVGPICK']);
//    $OPT_DAILYPICKS = number_format($ppcarray_jaxendcap[$key]['OPT_DAILYPICKS'], 2);
//    $OPT_NEWGRIDVOL = intval($ppcarray_jaxendcap[$key]['OPT_NEWGRIDVOL']);
//    $OPT_PPCCALC = $ppcarray_jaxendcap[$key]['OPT_PPCCALC'];
//    $CURRFEET = $ppcarray_jaxendcap[$key]['CURWALKFEET'];
//    $OPT_CURRBAY = intval($ppcarray_jaxendcap[$key]['CURR_BAY']);
//    $OPT_OPTBAY = intval(0);
//    $walkcostarray = _walkcost_GILL($OPT_CURRBAY, $OPT_OPTBAY, $OPT_DAILYPICKS, $CURRFEET);
//    $OPT_CURRDAILYFT = ($walkcostarray['CURR_FT_PER_DAY']);
//    $OPT_SHLDDAILYFT = ($walkcostarray['SHOULD_FT_PER_DAY']);
//    $OPT_ADDTLFTPERPICK = ($walkcostarray['ADDTL_FT_PER_PICK']);
//    $OPT_ADDTLFTPERDAY = ($walkcostarray['ADDTL_FT_PER_DAY']);
//    $OPT_WALKCOST = $walkcostarray['ADDTL_COST_PER_YEAR'];
//    $OPT_LOCATION = '';
//    $data[] = "($OPT_WHSE, $OPT_ITEM, $OPT_PKGU, '$OPT_LOC', $OPT_ADBS, '$OPT_CSLS', $OPT_CUBE, '$OPT_CURTIER', '$OPT_TOTIER', '$OPT_NEWGRID', $OPT_NDEP, $OPT_AVGPICK, '$OPT_DAILYPICKS', $OPT_NEWGRIDVOL, $OPT_PPCCALC, $OPT_OPTBAY, $OPT_CURRBAY, '$OPT_CURRDAILYFT', '$OPT_SHLDDAILYFT', '$OPT_ADDTLFTPERPICK', '$OPT_ADDTLFTPERDAY', $OPT_WALKCOST, '$OPT_LOCATION',$OPT_BUILDING)";
////    $counter += 1;
//}
//$values_jaxendcap = array();
//$values_jaxendcap = implode(',', $data);
//
//if (!empty($values_jaxendcap)) {
//
//    $sql = "INSERT IGNORE INTO gillingham.optimalbay ($columns) VALUES $values_jaxendcap";
//    $query = $conn1->prepare($sql);
//    $query->execute();
//}
//end of assigning jax endcaps



$values = array();
$data = array();
//$maxrange = 3999;
$maxrange = 99;
$counter = 0;
$rowcount = count($ppcarray);
$newgrid_runningvol = 0;
$baykey = 0;
$maxbaykey = count($baycubearray) - 1;
$baytotalvolume = intval($baycubearray[$baykey]['GRIDVOL']);

do {
    if ($maxrange > $rowcount) {  //prevent undefined offset
        $maxrange = $rowcount - 1;
    }

    $data = array();
    $values = array();
    while ($counter <= $maxrange) {

        $OPT_WHSE = intval($ppcarray[$counter]['OPT_WHSE']);
        $OPT_ITEM = intval($ppcarray[$counter]['OPT_ITEM']);
        $OPT_PKGU = intval($ppcarray[$counter]['OPT_PKGU']);
        $OPT_LOC = $ppcarray[$counter]['OPT_LOC'];
        $OPT_CSLS = $ppcarray[$counter]['OPT_CSLS'];
        $OPT_DAILYPICKS = number_format($ppcarray[$counter]['OPT_DAILYPICKS'], 2);
        $OPT_NEWGRIDVOL = ($ppcarray[$counter]['OPT_NEWGRIDVOL']);
        $OPT_PPCCALC = $ppcarray[$counter]['OPT_PPCCALC'];
        $OPT_CURRBAY = intval($ppcarray[$counter]['CURR_BAY']);
        $OPT_LOCATION = '';
        $CURRFEET = $ppcarray[$counter]['CURWALKFEET'];
        $HOLDLOC = $ppcarray[$counter]['HOLDLOCATION'];
        $holdloc_len = strlen($HOLDLOC);
        if (!is_null($HOLDLOC) && $holdloc_len >= 4) { //if location is held, the volume is already subtracted out of the available volume by bay
//            $newgrid_runningvol += $OPT_NEWGRIDVOL; //add newgrid vol to running total of newgrid vol
            $OPT_OPTBAY = intval($OPT_CURRBAY);
        } else { //no hold
            if ($newgrid_runningvol <= $baytotalvolume) {  //can next item volume fit into current available room?
                $OPT_OPTBAY = intval($baycubearray[$baykey]['BAY']);
                $newgrid_runningvol += $OPT_NEWGRIDVOL; //add newgrid vol to running total of newgrid vol
            } else {
                $baykey += 1; //add one to baykey to proceed to next bay
                $newgrid_runningvol = 0; //reset vol
                $OPT_OPTBAY = intval($baycubearray[$baykey]['BAY']);
            }
        }
        $walkcostarray = _walkcost_GILL($OPT_CURRBAY, $OPT_OPTBAY, $OPT_DAILYPICKS, $CURRFEET);
        $OPT_CURRDAILYFT = ($walkcostarray['CURR_FT_PER_DAY']);
        $OPT_SHLDDAILYFT = ($walkcostarray['SHOULD_FT_PER_DAY']);
        $OPT_ADDTLFTPERPICK = ($walkcostarray['ADDTL_FT_PER_PICK']);
        $OPT_ADDTLFTPERDAY = ($walkcostarray['ADDTL_FT_PER_DAY']);
        $OPT_WALKCOST = $walkcostarray['ADDTL_COST_PER_YEAR'];
        $data[] = "($OPT_WHSE, $OPT_ITEM, $OPT_PKGU, '$OPT_LOC',  '$OPT_CSLS',  $OPT_PPCCALC, $OPT_OPTBAY, $OPT_CURRBAY, '$OPT_CURRDAILYFT', '$OPT_SHLDDAILYFT', '$OPT_ADDTLFTPERPICK', '$OPT_ADDTLFTPERDAY', $OPT_WALKCOST, '$OPT_LOCATION',$OPT_BUILDING)";
        $counter += 1;
    }



    $values = implode(',', $data);

    if (empty($values)) {
        break;
    }

    $sql = "INSERT IGNORE INTO gillingham.optimalbay ($columns) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();
//    $maxrange += 4000;
    $maxrange += 100;
} while ($counter <= $rowcount);

//update history table

$sql_hist = "INSERT IGNORE INTO gillingham.optimalbay_hist(optbayhist_whse, optbayhist_tier, optbayhist_date, optbayhist_bay, optbayhist_pick, optbayhist_cost, optbayhist_count)
                 SELECT 
    OPT_WHSE,
    LMTIER,
    CURDATE(),
    L.BAY AS BAY,
    SUM(AVG_DAILY_PICK),
    AVG(ABS(OPT_WALKCOST)),
    COUNT(OPT_ITEM)
FROM
    gillingham.optimalbay
        JOIN
    gillingham.my_npfmvc ON OPT_ITEM = ITEM_NUMBER
        AND OPT_PKGU = PACKAGE_UNIT
        AND OPT_CSLS = PACKAGE_TYPE
        JOIN
    gillingham.bay_location L ON LOCATION = CUR_LOCATION
GROUP BY OPT_WHSE , LMTIER , CURDATE() , L.BAY";
$query_hist = $conn1->prepare($sql_hist);
$query_hist->execute();

