<?php

$whssel = 'GB0001';


ini_set('max_execution_time', 99999);
ini_set('memory_limit', '-1');
include_once '../globalincludes/google_connect.php';
//include_once '../connection/NYServer.php';
include_once '../globalfunctions/slottingfunctions.php';
//include_once 'sql_dailypick.php';  //pulls in variable $sql_dailypick to calculate daily pick quantites

$OPT_BUILDING = intval(1);
$sqldelete = "TRUNCATE  gillingham.optimalbay";
$querydelete = $conn1->prepare($sqldelete);
$querydelete->execute();

$baycube = $conn1->prepare("SELECT 
                                WALKBAY AS BAY,
                                SUM(slotmaster_grcube) * 1000 AS GRIDVOL,
                                SUM(slotmaster_usecube) * 1000 AS USEVOL
                            FROM
                                gillingham.slotmaster
                                    JOIN
                                gillingham.bay_location ON LOCATION = slotmaster_loc
                            WHERE
                                slotmaster_tier = 'BIN'
                                    AND WALKBAY NOT IN ('CC' , 'L0', 'R0', 'R1', 'R2')
                            GROUP BY WALKBAY");
$baycube->execute();
$baycubearray = $baycube->fetchAll(pdo::FETCH_ASSOC);

////subtract cube from items on hold from L04 cube
//$holdcube = $conn1->prepare("SELECT 
//                                    substring(HOLDLOCATION, 4, 2) as HOLDBAY, sum(LMVOL9) as HOLDBAYVOL
//                                FROM
//                                    gillingham.item_settings
//                                 JOIN gillingham.slotmaster on LMWHSE = WHSE and LMLOC = HOLDLOCATION
//                                WHERE
//                                    WHSE = $whssel and HOLDTIER = 'L04'
//                                GROUP BY substring(HOLDLOCATION, 4, 2)");
//$holdcube->execute();
//$holdcubearray = $holdcube->fetchAll(pdo::FETCH_ASSOC);

//foreach ($holdcubearray as $key => $value) {
//    $bay = $holdcubearray[$key]['HOLDBAY'];
//    $baysubtractkey = array_search($bay, array_column($baycubearray, 'BAY'));
//    $baycubearray[$baysubtractkey]['BAYVOL'] = $baycubearray[$baysubtractkey]['BAYVOL'] - $holdcubearray[$key]['HOLDBAYVOL'];
//}



//Result set for PPC sorted by highest PPC for items currently in BIN
$ppc = $conn1->prepare("SELECT 
    A.WAREHOUSE AS OPT_WHSE,
    A.ITEM_NUMBER AS OPT_ITEM,
    A.PACKAGE_UNIT AS OPT_PKGU,
    A.CUR_LOCATION AS OPT_LOC,
    A.AVGD_BTW_SLE AS OPT_ADBS,
    A.PACKAGE_TYPE AS OPT_CSLS,
    CASE
        WHEN (X.EA_DEPTH * X.EA_HEIGHT * X.EA_WIDTH) > 0 THEN (X.EA_DEPTH * X.EA_HEIGHT * X.EA_WIDTH)
        ELSE (X.CA_DEPTH * X.CA_HEIGHT * X.CA_WIDTH)
    END AS OPT_CUBE,
    A.LMTIER AS OPT_CURTIER,
    A.SUGGESTED_TIER AS OPT_TOTIER,
    A.SUGGESTED_GRID5 AS OPT_NEWGRID,
    A.SUGGESTED_DEPTH AS OPT_NDEP,
    A.PICK_QTY_MN AS OPT_AVGPICK,
    AVG_DAILY_PICK AS OPT_DAILYPICKS,
    SUGGESTED_NEWLOCVOL AS OPT_NEWGRIDVOL,
    AVG_DAILY_PICK / (SUGGESTED_NEWLOCVOL) * 1000 AS OPT_PPCCALC,
    V.WALKFEET AS CURWALKFEET,
    HOLDTIER,
    HOLDGRID,
    HOLDLOCATION,
    L.WALKBAY AS CURR_BAY
FROM
    gillingham.my_npfmvc A
        JOIN
    gillingham.bay_location L ON LOCATION = CUR_LOCATION
        JOIN
    gillingham.item_master X ON X.ITEM = A.ITEM_NUMBER
        LEFT JOIN
    gillingham.vectormap V ON VCBAY = V.BAY
        LEFT JOIN
    gillingham.item_settings S ON S.ITEM = A.ITEM_NUMBER
        LEFT JOIN
    gillingham.slotmaster M ON M.slotmaster_item = A.ITEM_NUMBER
WHERE
    SUGGESTED_TIER = 'BIN'
ORDER BY AVG_DAILY_PICK / (SUGGESTED_NEWLOCVOL) DESC , A.SUGGESTED_NEWLOCVOL ASC");
$ppc->execute();
$ppcarray = $ppc->fetchAll(pdo::FETCH_ASSOC);


//Result set for PPC sorted by highest PPC for items currently in L01
//$ppcL01 = $conn1->prepare("SELECT 
//    A.WAREHOUSE AS OPT_WHSE,
//    A.ITEM_NUMBER AS OPT_ITEM,
//    A.PACKAGE_UNIT AS OPT_PKGU,
//    A.CUR_LOCATION AS OPT_LOC,
//    A.AVGD_BTW_SLE AS OPT_ADBS,
//    A.PACKAGE_TYPE AS OPT_CSLS,
//    CASE
//        WHEN (X.CPCELEN * X.CPCEHEI * X.CPCEWID) > 0 THEN (X.CPCELEN * X.CPCEHEI * X.CPCEWID)
//        ELSE (X.CPCCLEN * X.CPCCHEI * X.CPCCWID)
//    END AS OPT_CUBE,
//    A.LMTIER AS OPT_CURTIER,
//    A.SUGGESTED_TIER AS OPT_TOTIER,
//    A.SUGGESTED_GRID5 AS OPT_NEWGRID,
//    A.SUGGESTED_DEPTH AS OPT_NDEP,
//    A.PICK_QTY_MN AS OPT_AVGPICK,
//    CASE
//        WHEN A.AVGD_BTW_SLE >= 365 THEN 0
//        WHEN A.DAYS_FRM_SLE >= 180 THEN 0
//        WHEN
//            A.AVG_DAILY_PICK > A.AVG_DAILY_UNIT
//        THEN
//            (A.AVG_DAILY_UNIT / (CASE
//                WHEN X.CPCCPKU > 0 THEN X.CPCCPKU
//                ELSE 1
//            END)) / A.AVGD_BTW_SLE
//        WHEN
//            A.AVGD_BTW_SLE = 0
//                AND A.DAYS_FRM_SLE = 0
//        THEN
//            A.AVG_DAILY_PICK
//        WHEN A.AVGD_BTW_SLE = 0 THEN (A.AVG_DAILY_PICK / A.DAYS_FRM_SLE)
//        ELSE (A.AVG_DAILY_PICK / A.AVGD_BTW_SLE)
//    END AS OPT_DAILYPICKS,
//    SUGGESTED_NEWLOCVOL AS OPT_NEWGRIDVOL,
//    (CASE
//        WHEN A.AVGD_BTW_SLE >= 365 THEN 0
//        WHEN A.DAYS_FRM_SLE >= 180 THEN 0
//        WHEN
//            A.AVG_DAILY_PICK > A.AVG_DAILY_UNIT
//        THEN
//            (A.AVG_DAILY_UNIT / (CASE
//                WHEN X.CPCCPKU > 0 THEN X.CPCCPKU
//                ELSE 1
//            END)) / A.AVGD_BTW_SLE
//        WHEN
//            A.AVGD_BTW_SLE = 0
//                AND A.DAYS_FRM_SLE = 0
//        THEN
//            A.AVG_DAILY_PICK
//        WHEN A.AVGD_BTW_SLE = 0 THEN (A.AVG_DAILY_PICK / A.DAYS_FRM_SLE)
//        ELSE (A.AVG_DAILY_PICK / A.AVGD_BTW_SLE)
//    END) / (SUGGESTED_NEWLOCVOL) * 1000 AS OPT_PPCCALC,
//    V.WALKFEET AS CURWALKFEET,
//    HOLDTIER,
//    HOLDGRID,
//    HOLDLOCATION,
//    L.WALKBAY AS CURR_BAY
//FROM
//    gillingham.my_npfmvc A
//                JOIN
//    gillingham.bay_location L ON LOCATION = CUR_LOCATION
//        LEFT JOIN
//    gillingham.npfcpcsettings X ON X.CPCITEM = A.ITEM_NUMBER
//        LEFT JOIN
//    gillingham.vectormap V ON VCBAY = V.BAY
//        LEFT JOIN
//    gillingham.item_settings S ON S.ITEM = A.ITEM_NUMBER
//        LEFT JOIN
//    gillingham.slotmaster M ON M.slotmaster_item = A.ITEM_NUMBER
//WHERE
//    SUGGESTED_TIER = ('L01')
//ORDER BY (CASE
//    WHEN A.AVGD_BTW_SLE >= 365 THEN 0
//    WHEN A.DAYS_FRM_SLE >= 180 THEN 0
//    WHEN
//        A.AVG_DAILY_PICK > A.AVG_DAILY_UNIT
//    THEN
//        (A.AVG_DAILY_UNIT / (CASE
//            WHEN X.CPCCPKU > 0 THEN X.CPCCPKU
//            ELSE 1
//        END)) / A.AVGD_BTW_SLE
//    WHEN
//        A.AVGD_BTW_SLE = 0
//            AND A.DAYS_FRM_SLE = 0
//    THEN
//        A.AVG_DAILY_PICK
//    WHEN A.AVGD_BTW_SLE = 0 THEN (A.AVG_DAILY_PICK / A.DAYS_FRM_SLE)
//    ELSE (A.AVG_DAILY_PICK / A.AVGD_BTW_SLE)
//END) DESC");
//$ppcL01->execute();
//$ppcL01array = $ppcL01->fetchAll(pdo::FETCH_ASSOC);



//********ALL L01 locations are 0 walkfeet, do you need this?**************
//L01 Locations in ascending walkfeet to match with highest picked L01 Recs
//$L01Locs = $conn1->prepare("SELECT 
//    V.BAY AS LMLOC, V.WALKFEET, slotmaster_dimgroup as LMGRD5, slotmaster_usedeep as LMDEEP
//FROM
//    gillingham.vectormap V
//        LEFT JOIN
//    gillingham.slotmaster ON slotmaster_loc = V.BAY
//WHERE
//    TIER = 'L01'
//        AND V.BAY NOT IN (SELECT 
//            HOLDLOCATION
//        FROM
//            gillingham.item_settings
//)
//ORDER BY V.WALKFEET ASC");
//$L01Locs->execute();
//$L01Locsarray = $L01Locs->fetchAll(pdo::FETCH_ASSOC);



//assign L01s to specific location
foreach ($ppcL01array as $key => $value) {
//is there a hold location?
    $testloc = $ppcL01array[$key]['HOLDLOCATION'];

    $OPT_NEWGRID = $ppcL01array[$key]['OPT_NEWGRID'];
    $OPT_NDEP = intval($ppcL01array[$key]['OPT_NDEP']);



    if (!is_null($testloc) && $testloc <> '') {
        $OPT_LOCATION = $testloc;
    } else if (count($L01Locsarray > 0)) {
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


    $OPT_TOTIER = $ppcL01array[$key]['OPT_TOTIER'];
    $OPT_WHSE = intval($ppcL01array[$key]['OPT_WHSE']);
    $OPT_ITEM = intval($ppcL01array[$key]['OPT_ITEM']);
    if ($OPT_ITEM == 1007767) {
        echo 't';
    }
    $OPT_PKGU = intval($ppcL01array[$key]['OPT_PKGU']);
    $OPT_LOC = $ppcL01array[$key]['OPT_LOC'];
    $OPT_ADBS = intval($ppcL01array[$key]['OPT_ADBS']);
    $OPT_CSLS = $ppcL01array[$key]['OPT_CSLS'];
    $OPT_CUBE = intval($ppcL01array[$key]['OPT_CUBE']);
    $OPT_CURTIER = $ppcL01array[$key]['OPT_CURTIER'];
    $OPT_AVGPICK = intval($ppcL01array[$key]['OPT_AVGPICK']);
    $OPT_DAILYPICKS = number_format($ppcL01array[$key]['OPT_DAILYPICKS'], 2);
    $OPT_NEWGRIDVOL = intval($ppcL01array[$key]['OPT_NEWGRIDVOL']);
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

    $data[] = "($OPT_WHSE, $OPT_ITEM, $OPT_PKGU, '$OPT_LOC', $OPT_ADBS, '$OPT_CSLS', $OPT_CUBE, '$OPT_CURTIER', '$OPT_TOTIER', '$OPT_NEWGRID', $OPT_NDEP, $OPT_AVGPICK, '$OPT_DAILYPICKS', $OPT_NEWGRIDVOL, $OPT_PPCCALC, $OPT_OPTBAY, $OPT_CURRBAY, '$OPT_CURRDAILYFT', '$OPT_SHLDDAILYFT', '$OPT_ADDTLFTPERPICK', '$OPT_ADDTLFTPERDAY', $OPT_WALKCOST, '$OPT_LOCATION',$OPT_BUILDING)";
}
$columns = 'OPT_WHSE, OPT_ITEM, OPT_PKGU, OPT_LOC, OPT_ADBS, OPT_CSLS, OPT_CUBE, OPT_CURTIER, OPT_TOTIER, OPT_NEWGRID, OPT_NDEP, OPT_AVGPICK, OPT_DAILYPICKS, OPT_NEWGRIDVOL, OPT_PPCCALC, OPT_OPTBAY, OPT_CURRBAY, OPT_CURRDAILYFT, OPT_SHLDDAILYFT, OPT_ADDTLFTPERPICK, OPT_ADDTLFTPERDAY, OPT_WALKCOST, OPT_LOCATION, OPT_BUILDING';
$valuesl01 = array();
$valuesl01 = implode(',', $data);

if (!empty($valuesl01)) {


    $sql = "INSERT IGNORE INTO gillingham.optimalbay ($columns) VALUES $valuesl01";
    $query = $conn1->prepare($sql);
    $query->execute();
}




//if gillingham, assign endcaps

foreach ($ppcarray_jaxendcap as $key => $value) {


    $OPT_TOTIER = $ppcarray_jaxendcap[$key]['OPT_TOTIER'];
    $OPT_WHSE = intval($ppcarray_jaxendcap[$key]['OPT_WHSE']);
    $OPT_ITEM = intval($ppcarray_jaxendcap[$key]['OPT_ITEM']);
    if ($OPT_ITEM == 1160864) {
        echo 't';
    }

    $OPT_PKGU = intval($ppcarray_jaxendcap[$key]['OPT_PKGU']);
    $OPT_LOC = $ppcarray_jaxendcap[$key]['OPT_LOC'];
    $OPT_ADBS = intval($ppcarray_jaxendcap[$key]['OPT_ADBS']);
    $OPT_CSLS = $ppcarray_jaxendcap[$key]['OPT_CSLS'];
    $OPT_CUBE = intval($ppcarray_jaxendcap[$key]['OPT_CUBE']);
    $OPT_CURTIER = $ppcarray_jaxendcap[$key]['OPT_CURTIER'];
    $OPT_NEWGRID = $ppcarray_jaxendcap[$key]['OPT_NEWGRID'];
    $OPT_NDEP = intval($ppcarray_jaxendcap[$key]['OPT_NDEP']);
    $OPT_AVGPICK = intval($ppcarray_jaxendcap[$key]['OPT_AVGPICK']);
    $OPT_DAILYPICKS = number_format($ppcarray_jaxendcap[$key]['OPT_DAILYPICKS'], 2);
    $OPT_NEWGRIDVOL = intval($ppcarray_jaxendcap[$key]['OPT_NEWGRIDVOL']);
    $OPT_PPCCALC = $ppcarray_jaxendcap[$key]['OPT_PPCCALC'];
    $CURRFEET = $ppcarray_jaxendcap[$key]['CURWALKFEET'];
    $OPT_CURRBAY = intval($ppcarray_jaxendcap[$key]['CURR_BAY']);
    $OPT_OPTBAY = intval(0);
    $walkcostarray = _walkcost_GILL($OPT_CURRBAY, $OPT_OPTBAY, $OPT_DAILYPICKS, $CURRFEET);
    $OPT_CURRDAILYFT = ($walkcostarray['CURR_FT_PER_DAY']);
    $OPT_SHLDDAILYFT = ($walkcostarray['SHOULD_FT_PER_DAY']);
    $OPT_ADDTLFTPERPICK = ($walkcostarray['ADDTL_FT_PER_PICK']);
    $OPT_ADDTLFTPERDAY = ($walkcostarray['ADDTL_FT_PER_DAY']);
    $OPT_WALKCOST = $walkcostarray['ADDTL_COST_PER_YEAR'];
    $OPT_LOCATION = '';
    $data[] = "($OPT_WHSE, $OPT_ITEM, $OPT_PKGU, '$OPT_LOC', $OPT_ADBS, '$OPT_CSLS', $OPT_CUBE, '$OPT_CURTIER', '$OPT_TOTIER', '$OPT_NEWGRID', $OPT_NDEP, $OPT_AVGPICK, '$OPT_DAILYPICKS', $OPT_NEWGRIDVOL, $OPT_PPCCALC, $OPT_OPTBAY, $OPT_CURRBAY, '$OPT_CURRDAILYFT', '$OPT_SHLDDAILYFT', '$OPT_ADDTLFTPERPICK', '$OPT_ADDTLFTPERDAY', $OPT_WALKCOST, '$OPT_LOCATION',$OPT_BUILDING)";
//    $counter += 1;
}
$columns = 'OPT_WHSE, OPT_ITEM, OPT_PKGU, OPT_LOC, OPT_ADBS, OPT_CSLS, OPT_CUBE, OPT_CURTIER, OPT_TOTIER, OPT_NEWGRID, OPT_NDEP, OPT_AVGPICK, OPT_DAILYPICKS, OPT_NEWGRIDVOL, OPT_PPCCALC, OPT_OPTBAY, OPT_CURRBAY, OPT_CURRDAILYFT, OPT_SHLDDAILYFT, OPT_ADDTLFTPERPICK, OPT_ADDTLFTPERDAY, OPT_WALKCOST, OPT_LOCATION, OPT_BUILDING';
$values_jaxendcap = array();
$values_jaxendcap = implode(',', $data);

if (!empty($values_jaxendcap)) {

    $sql = "INSERT IGNORE INTO gillingham.optimalbay ($columns) VALUES $values_jaxendcap";
    $query = $conn1->prepare($sql);
    $query->execute();
}

//end of assigning jax endcaps



$values = array();

$maxrange = 3999;
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
        $OPT_TOTIER = $ppcarray[$counter]['OPT_TOTIER'];
        if ($OPT_TOTIER === 'L02' || $OPT_TOTIER == 'L05') {
            $OPT_WHSE = intval($ppcarray[$counter]['OPT_WHSE']);
            $OPT_ITEM = intval($ppcarray[$counter]['OPT_ITEM']);
            $OPT_PKGU = intval($ppcarray[$counter]['OPT_PKGU']);
            $OPT_LOC = $ppcarray[$counter]['OPT_LOC'];
            $OPT_ADBS = intval($ppcarray[$counter]['OPT_ADBS']);
            $OPT_CSLS = $ppcarray[$counter]['OPT_CSLS'];
            $OPT_CUBE = intval($ppcarray[$counter]['OPT_CUBE']);
            $OPT_CURTIER = $ppcarray[$counter]['OPT_CURTIER'];
            $OPT_NEWGRID = $ppcarray[$counter]['OPT_NEWGRID'];
            $OPT_NDEP = intval($ppcarray[$counter]['OPT_NDEP']);
            $OPT_AVGPICK = intval($ppcarray[$counter]['OPT_AVGPICK']);
            $OPT_DAILYPICKS = number_format($ppcarray[$counter]['OPT_DAILYPICKS'], 2);
            $OPT_NEWGRIDVOL = intval($ppcarray[$counter]['OPT_NEWGRIDVOL']);
            $OPT_PPCCALC = $ppcarray[$counter]['OPT_PPCCALC'];
            $CURRFEET = $ppcarray[$counter]['CURWALKFEET'];

            $OPT_CURRBAY = intval($ppcarray[$counter]['CURR_BAY']);
            $OPT_OPTBAY = intval(0);
            $walkcostarray = _walkcost_GILL($OPT_CURRBAY, $OPT_OPTBAY, $OPT_DAILYPICKS, $CURRFEET);

            $OPT_CURRDAILYFT = ($walkcostarray['CURR_FT_PER_DAY']);
            $OPT_SHLDDAILYFT = ($walkcostarray['SHOULD_FT_PER_DAY']);
            $OPT_ADDTLFTPERPICK = ($walkcostarray['ADDTL_FT_PER_PICK']);
            $OPT_ADDTLFTPERDAY = ($walkcostarray['ADDTL_FT_PER_DAY']);
            $OPT_WALKCOST = $walkcostarray['ADDTL_COST_PER_YEAR'];
            $OPT_LOCATION = '';
            $data[] = "($OPT_WHSE, $OPT_ITEM, $OPT_PKGU, '$OPT_LOC', $OPT_ADBS, '$OPT_CSLS', $OPT_CUBE, '$OPT_CURTIER', '$OPT_TOTIER', '$OPT_NEWGRID', $OPT_NDEP, $OPT_AVGPICK, '$OPT_DAILYPICKS', $OPT_NEWGRIDVOL, $OPT_PPCCALC, $OPT_OPTBAY, $OPT_CURRBAY, '$OPT_CURRDAILYFT', '$OPT_SHLDDAILYFT', '$OPT_ADDTLFTPERPICK', '$OPT_ADDTLFTPERDAY', $OPT_WALKCOST, '$OPT_LOCATION',$OPT_BUILDING)";
            $counter += 1;
        } else {

            //not L01, L02, or L05
            $OPT_WHSE = intval($ppcarray[$counter]['OPT_WHSE']);
            $OPT_ITEM = intval($ppcarray[$counter]['OPT_ITEM']);
            if ($OPT_ITEM == 1000833) {
                echo 't';
            }
            $OPT_PKGU = intval($ppcarray[$counter]['OPT_PKGU']);
            $OPT_LOC = $ppcarray[$counter]['OPT_LOC'];
            $OPT_ADBS = intval($ppcarray[$counter]['OPT_ADBS']);
            $OPT_CSLS = $ppcarray[$counter]['OPT_CSLS'];
            $OPT_CUBE = intval($ppcarray[$counter]['OPT_CUBE']);
            $OPT_CURTIER = $ppcarray[$counter]['OPT_CURTIER'];
            $OPT_NEWGRID = $ppcarray[$counter]['OPT_NEWGRID'];
            $OPT_NDEP = intval($ppcarray[$counter]['OPT_NDEP']);
            $OPT_AVGPICK = intval($ppcarray[$counter]['OPT_AVGPICK']);
            $OPT_DAILYPICKS = number_format($ppcarray[$counter]['OPT_DAILYPICKS'], 2);
            $OPT_NEWGRIDVOL = intval($ppcarray[$counter]['OPT_NEWGRIDVOL']);
            $OPT_PPCCALC = $ppcarray[$counter]['OPT_PPCCALC'];
            $OPT_CURRBAY = intval($ppcarray[$counter]['CURR_BAY']);
            $OPT_LOCATION = '';
            $CURRFEET = $ppcarray[$counter]['CURWALKFEET'];
            $HOLDLOC = $ppcarray[$counter]['HOLDLOCATION'];
            if (is_null($HOLDLOC)) { //if location is held, the volume is already subtracted out of the available volume by bay
                $newgrid_runningvol += $OPT_NEWGRIDVOL; //add newgrid vol to running total of newgrid vol
            }

            if ($newgrid_runningvol <= $baytotalvolume) {  //can next item volume fit into current available room?
                if (is_null($HOLDLOC)) {
                    $OPT_OPTBAY = intval($baycubearray[$baykey]['BAY']);
                } else {
                    $OPT_OPTBAY = intval(substr($HOLDLOC, 3, 2));
                }




                $walkcostarray = _walkcost_GILL($OPT_CURRBAY, $OPT_OPTBAY, $OPT_DAILYPICKS, $CURRFEET);
                $OPT_CURRDAILYFT = ($walkcostarray['CURR_FT_PER_DAY']);
                $OPT_SHLDDAILYFT = ($walkcostarray['SHOULD_FT_PER_DAY']);
                $OPT_ADDTLFTPERPICK = ($walkcostarray['ADDTL_FT_PER_PICK']);
                $OPT_ADDTLFTPERDAY = ($walkcostarray['ADDTL_FT_PER_DAY']);
                $OPT_WALKCOST = $walkcostarray['ADDTL_COST_PER_YEAR'];
                $data[] = "($OPT_WHSE, $OPT_ITEM, $OPT_PKGU, '$OPT_LOC', $OPT_ADBS, '$OPT_CSLS', $OPT_CUBE, '$OPT_CURTIER', '$OPT_TOTIER', '$OPT_NEWGRID', $OPT_NDEP, $OPT_AVGPICK, '$OPT_DAILYPICKS', $OPT_NEWGRIDVOL, $OPT_PPCCALC, $OPT_OPTBAY, $OPT_CURRBAY, '$OPT_CURRDAILYFT', '$OPT_SHLDDAILYFT', '$OPT_ADDTLFTPERPICK', '$OPT_ADDTLFTPERDAY', $OPT_WALKCOST, '$OPT_LOCATION',$OPT_BUILDING)";
                $counter += 1;
            } else { //item cannot fit.  Increase bay key and reset
                if ($baykey < $maxbaykey) {
                    $baykey += 1; //add one to baykey to proceed to next bay
                }
                $CURRFEET = $ppcarray[$counter]['CURWALKFEET'];
                $newgrid_runningvol = $OPT_NEWGRIDVOL; //reset running total for new grid vol
                $baytotalvolume = intval($baycubearray[$baykey]['GRIDVOL']); //reset available bay volume for next bay
                if (is_null($HOLDLOC)) {
                    $OPT_OPTBAY = intval($baycubearray[$baykey]['BAY']);
                } else {
                    $OPT_OPTBAY = intval(substr($HOLDLOC, 3, 2));
                }

                $walkcostarray = _walkcost_GILL($OPT_CURRBAY, $OPT_OPTBAY, $OPT_DAILYPICKS, $CURRFEET);
                $OPT_CURRDAILYFT = ($walkcostarray['CURR_FT_PER_DAY']);
                $OPT_SHLDDAILYFT = ($walkcostarray['SHOULD_FT_PER_DAY']);
                $OPT_ADDTLFTPERPICK = ($walkcostarray['ADDTL_FT_PER_PICK']);
                $OPT_ADDTLFTPERDAY = ($walkcostarray['ADDTL_FT_PER_DAY']);
                $OPT_WALKCOST = $walkcostarray['ADDTL_COST_PER_YEAR'];
                $data[] = "($OPT_WHSE, $OPT_ITEM, $OPT_PKGU, '$OPT_LOC', $OPT_ADBS, '$OPT_CSLS', $OPT_CUBE, '$OPT_CURTIER', '$OPT_TOTIER', '$OPT_NEWGRID', $OPT_NDEP, $OPT_AVGPICK, '$OPT_DAILYPICKS', $OPT_NEWGRIDVOL, $OPT_PPCCALC, $OPT_OPTBAY, $OPT_CURRBAY, '$OPT_CURRDAILYFT', '$OPT_SHLDDAILYFT', '$OPT_ADDTLFTPERPICK', '$OPT_ADDTLFTPERDAY', $OPT_WALKCOST, '$OPT_LOCATION',$OPT_BUILDING)";
                $counter += 1;
            }
        }
    }

    $values = implode(',', $data);

    if (empty($values)) {
        break;
    }

    $sql = "INSERT IGNORE INTO gillingham.optimalbay ($columns) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();
    $maxrange += 4000;
} while ($counter <= $rowcount);

//update history table

$sql_hist = "INSERT IGNORE INTO gillingham.optimalbay_hist(optbayhist_whse, optbayhist_tier, optbayhist_date, optbayhist_bay, optbayhist_pick, optbayhist_cost, optbayhist_count)
                 SELECT 
    OPT_WHSE,
    OPT_CURTIER,
    CURDATE(),
    L.WALKBAY AS BAY,
    SUM(OPT_DAILYPICKS),
    AVG(ABS(OPT_WALKCOST)),
    COUNT(OPT_ITEM)
FROM
    gillingham.optimalbay
        JOIN
    gillingham.my_npfmvc ON OPT_ITEM = ITEM_NUMBER
                JOIN
    gillingham.bay_location L ON LOCATION = CUR_LOCATION
WHERE
    OPT_CURTIER <> 'L01'
GROUP BY OPT_WHSE , OPT_CURTIER , CURDATE() , L.BAY";
$query_hist = $conn1->prepare($sql_hist);
$query_hist->execute();

$sql_hist2 = "INSERT IGNORE INTO gillingham.optimalbay_hist(optbayhist_whse, optbayhist_tier, optbayhist_date, optbayhist_bay, optbayhist_pick, optbayhist_cost, optbayhist_count)
                 SELECT OPT_WHSE, OPT_CURTIER, CURDATE(), OPT_LOC as BAY, sum(OPT_DAILYPICKS), avg(ABS(OPT_WALKCOST)), count(OPT_ITEM) FROM gillingham.optimalbay WHERE OPT_CURTIER = 'L01'  GROUP BY OPT_WHSE, OPT_CURTIER, CURDATE(), OPT_LOC;";
$query_hist2 = $conn1->prepare($sql_hist2);
$query_hist2->execute();

//add all others that weren't calculated.  Since using insert igore, can pull in all locations
//$sql_hist3 = "INSERT IGNORE INTO gillingham.optimalbay_hist(optbayhist_whse, optbayhist_tier, optbayhist_date, optbayhist_bay, optbayhist_pick, optbayhist_cost, optbayhist_count)
//SELECT 
//    WAREHOUSE,
//    LMTIER,
//    CURDATE(),
// CASE
//                    WHEN
//                        LMGRD5 LIKE 'MB%'
//                            AND SUBSTRING(OPT_LOC, 3, 2) > '12'
//                    THEN
//                        CONCAT(SUBSTRING(OPT_LOC, 1, 2), '0', SUBSTRING(OPT_LOC, 3, 1))
//                    ELSE SUBSTRING(OPT_LOC, 1, 4)
//                END AS BAY,
//    SUM(CASE
//        WHEN AVGD_BTW_SLE >= 365 THEN 0
//        WHEN DAYS_FRM_SLE >= 180 THEN 0
//        WHEN
//            PICK_QTY_MN > SHIP_QTY_MN
//        THEN
//            (SHIP_QTY_MN / (CASE
//                WHEN CPCCPKU > 0 THEN CPCCPKU
//                ELSE 1
//            END)) / AVGD_BTW_SLE
//        WHEN AVGD_BTW_SLE = 0 AND DAYS_FRM_SLE = 0 THEN PICK_QTY_MN
//        WHEN AVGD_BTW_SLE = 0 THEN (PICK_QTY_MN / DAYS_FRM_SLE)
//        ELSE (PICK_QTY_MN / AVGD_BTW_SLE)
//    END) AS PICKSSUM,
//    0 AS COST,
//    0 AS CNT
//FROM
//    gillingham.mysql_nptsld
//        JOIN
//    gillingham.npfcpcsettings ON CPCWHSE = WAREHOUSE
//        AND ITEM_NUMBER = CPCITEM
//        JOIN
//    gillingham.mysql_npflsm ON LMWHSE = WAREHOUSE
//        AND LMITEM = ITEM_NUMBER
//        AND LMLOC = CUR_LOCATION
//GROUP BY WAREHOUSE , LMTIER , CURDATE() , CASE
//                    WHEN
//                        LMGRD5 LIKE 'MB%'
//                            AND SUBSTRING(OPT_LOC, 3, 2) > '12'
//                    THEN
//                        CONCAT(SUBSTRING(OPT_LOC, 1, 2), '0', SUBSTRING(OPT_LOC, 3, 1))
//                    ELSE SUBSTRING(OPT_LOC, 1, 4)
//                END";
//$query_hist3 = $conn1->prepare($sql_hist3);
//$query_hist3->execute();
