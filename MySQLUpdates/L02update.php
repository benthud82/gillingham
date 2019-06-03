<?php

$JAX_ENDCAP = 0;

//*** RESTRICTION VARIABLES ***
$minadbs = 5;  //need to have at least 15% of the available items.  For NOTL, 5 ADBS represents 4678 of 31000 items or 15%
$mindsls = 14; //sold in the last two weeks
//$daystostock = 15;  //stock 10 shipping occurences as max
$slowdownsizecutoff = 999999;  //min ADBS to only stock to 2 ship occurences as Max.  Not used right now till capacity is determined
$skippedkeycount = 0;
include '../connection/NYServer.php';




//*** Step 2: L02 Designation ***
//Delete Restricted flow Locs
$SQLDelete = $conn1->prepare("DELETE FROM gillingham.items_restricted WHERE REST_WHSE = '$whssel' and REST_SHOULD = 'FLOW'");
$SQLDelete->execute();


//Pull in available L02 Grid5s by volume ascending order
$L02GridsSQL = $conn1->prepare("SELECT 
                                                            slotmaster_dimgroup as LMGRD5,
                                                            slotmaster_grhigh AS LMHIGH,
                                                            slotmaster_grdeep AS LMDEEP,
                                                            slotmaster_grwide AS LMWIDE,
                                                            slotmaster_grcube * 1000 AS LMVOL9,
                                                            COUNT(*) as GRIDCOUNT
                                                        FROM
                                                            gillingham.slotmaster
                                                        WHERE
                                                            slotmaster_tier = 'L02'
                                                        GROUP BY slotmaster_dimgroup , slotmaster_grhigh , slotmaster_grdeep , slotmaster_grwide , slotmaster_grcube
                                                        HAVING COUNT(slotmaster_dimgroup) >= 10
                                                        ORDER BY slotmaster_usecube ASC");
$L02GridsSQL->execute();
$L02GridsArray = $L02GridsSQL->fetchAll(pdo::FETCH_ASSOC);




//subtract out the held grids from the grids array
$onholdsql = $conn1->prepare("SELECT 
                                                                        HOLDGRID, COUNT(*) as HOLDCOUNT
                                                                    FROM
                                                                        gillingham.item_settings
                                                                    WHERE
                                                                        HOLDTIER = 'L02' AND WHSE = '$whssel'
                                                                    GROUP BY HOLDGRID");
$onholdsql->execute();
$onholdsqlarray = $onholdsql->fetchAll(pdo::FETCH_ASSOC);

foreach ($onholdsqlarray as $key => $value) {
    $onholdkey = array_search($onholdsqlarray[$key]['HOLDGRID'], array_column($L02GridsArray, 'LMGRD5')); //Find Grid5 associated key
    $L02GridsArray[$onholdkey]['GRIDCOUNT'] -= $onholdsqlarray[$key]['HOLDCOUNT'];  //subtract the count of held grids from available grid count
    //remove grid if new count = 0
    if ($L02GridsArray[$onholdkey]['GRIDCOUNT'] == 0) {
        unset($L02GridsArray[$onholdkey]);
        $L02GridsArray = array_values($L02GridsArray);
    }
}


$L02sql = $conn1->prepare("SELECT DISTINCT
                                                        'GB00001' as WAREHOUSE,
                                                        A.ITEM as ITEM_NUMBER,
                                                        A.PKGU as PACKAGE_UNIT,
                                                        A.PKTYPE as PACKAGE_TYPE,
                                                        D.slotmaster_loc as LMLOC,
                                                        A.DSLS as DAYS_FRM_SLE,
                                                        A.ADBS as AVGD_BTW_SLE,
                                                        A.AVG_INVOH as AVG_INV_OH,
                                                        A.DAYCOUNT as NBR_SHIP_OCC,
                                                        A.AVG_PICK as PICK_QTY_MN,
                                                        A.PICK_STD AS PICK_QTY_SD,
                                                        A.AVG_UNITS as SHIP_QTY_MN,
                                                        A.UNIT_STD as SHIP_QTY_SD,
                                                        X.CPCEPKU,
                                                        X.CPCCPKU,
                                                        X.CPCFLOW,
                                                        X.CPCTOTE,
                                                        X.CPCSHLF,
                                                        X.CPCROTA,
                                                        X.CPCESTK,
                                                        X.CPCLIQU,
                                                        X.CPCELEN,
                                                        X.CPCEHEI,
                                                        X.CPCEWID,
                                                        X.CPCCLEN,
                                                        X.CPCCHEI,
                                                        X.CPCCWID,
                                                        X.CPCNEST,
                                                        D.slotmaster_chargroup,
                                                        D.slotmaster_pickzone,
                                                        D.slotmaster_usehigh AS LMHIGH,
                                                        D.slotmaster_usedeep AS LMDEEP,
                                                        D.slotmaster_usewide AS LMWIDE,
                                                        D.slotmaster_usecube AS LMVOL9,
                                                        D.slotmaster_tier AS LMTIER,
                                                        D.slotmaster_dimgroup AS LMGRD5,
                                                        D.slotmaster_normreplen + D.slotmaster_maxreplen AS CURMAX,
                                                        D.slotmaster_normreplen AS CURMIN,
                                                        CASE
                                                            WHEN X.CPCELEN * X.CPCEHEI * X.CPCEWID > 0 THEN (($sql_dailyunit) * X.CPCELEN * X.CPCEHEI * X.CPCEWID)
                                                            ELSE ($sql_dailyunit) * X.CPCCLEN * X.CPCCHEI * X.CPCCWID / X.CPCCPKU
                                                        END AS DLY_CUBE_VEL,
                                                        CASE
                                                            WHEN X.CPCELEN * X.CPCEHEI * X.CPCEWID > 0 THEN ($sql_dailypick) * X.CPCELEN * X.CPCEHEI * X.CPCEWID
                                                            ELSE ($sql_dailypick) * X.CPCCLEN * X.CPCCHEI * X.CPCCWID
                                                        END AS DLY_PICK_VEL,
                                                        $sql_dailypick AS DAILYPICK,
                                                        $sql_dailyunit AS DAILYUNIT
                                                    FROM
                                                        gillingham.nptsld A
                                                            JOIN
                                                        gillingham.npfcpcsettings X ON X.CPCITEM = A.ITEM
                                                            JOIN
                                                        gillingham.slotmaster D ON D.slotmaster_item = A.ITEM
                                                            LEFT JOIN
                                                        gillingham.my_npfmvc F ON F.ITEM_NUMBER = A.ITEM
                                                    WHERE
                                                            A.PKTYPE = ('EA')
                                                            and A.DAYCOUNT >= 4
                                                            and slotmaster_tier in ('L01','L02','L04')
                                                            and A.ADBS > 0
                                                            and A.ADBS <= $minadbs
                                                            and A.DSLS <= $mindsls
                                                            and F.ITEM_NUMBER IS NULL
                                                    ORDER BY DLY_CUBE_VEL desc");
$L02sql->execute();
$L02array = $L02sql->fetchAll(pdo::FETCH_ASSOC);
$conn1 = null;
foreach ($L02array as $key => $value) {
    $var_item = intval($L02array[$key]['ITEM_NUMBER']);


    if (count($L02GridsArray) <= 0) {
        break;  //if all grids have been used, exit
    }

    //Check OK in Flow Setting
    $var_OKINFLOW = $L02array[$key]['CPCFLOW'];
    if ($var_OKINFLOW == 'N') {

        $var_pkgu = intval($L02array[$key]['PACKAGE_UNIT']);
        $var_pkty = $L02array[$key]['PACKAGE_TYPE'];
        $var_should = 'FLOW';
        include_once '../connection/NYServer.php';
        //write to table that should have gone to flow and was restricted
        $result2 = $conn1->prepare("INSERT INTO gillingham.items_restricted (REST_ID, REST_WHSE, REST_ITEM, REST_PKGU, REST_PKTY, REST_SHOULD) values (0,'$whssel', $var_item ,$var_pkgu,'" . $var_pkty . "','" . $var_should . "')");
        $result2->execute();
        $conn1 = null;
        $skippedkeycount += 1;
        unset($L02array[$key]);
        continue;
    }

    $var_AVGSHIPQTY = $L02array[$key]['SHIP_QTY_MN'];
    $AVGD_BTW_SLE = intval($L02array[$key]['AVGD_BTW_SLE']);
    $var_AVGINV = intval($L02array[$key]['AVG_INV_OH']);
    $avgdailyshipqty = number_format($var_AVGSHIPQTY / $AVGD_BTW_SLE, 8);
    if ($avgdailyshipqty == 0) {
        $avgdailyshipqty = .000000001;
    }
    $var_PCLIQU = $L02array[$key]['CPCLIQU'];

    $var_PCEHEIin = $L02array[$key]['CPCEHEI'];
    if ($var_PCEHEIin == 0) {
        $var_PCEHEIin = $L02array[$key]['CPCCHEI'];
    }

    $var_PCELENin = $L02array[$key]['CPCELEN'];
    if ($var_PCELENin == 0) {
        $var_PCELENin = $L02array[$key]['CPCCLEN'];
    }

    $var_PCEWIDin = $L02array[$key]['CPCEWID'];
    if ($var_PCEWIDin == 0) {
        $var_PCEWIDin = $L02array[$key]['CPCCWID'];
    }

    $var_caseqty = $L02array[$key]['CPCCPKU'];
    if ($var_caseqty == 0) {
        $var_caseqty = 1;
    }

    if ($AVGD_BTW_SLE <= 1) {
        $daystostock = 10;
    } elseif ($AVGD_BTW_SLE <= 2) {
        $daystostock = 6;
    } elseif ($AVGD_BTW_SLE <= 3) {
        $daystostock = 5;
    } elseif ($AVGD_BTW_SLE <= 4) {
        $daystostock = 3;
    } elseif ($AVGD_BTW_SLE <= 5) {
        $daystostock = 3;
    }



//    $PKGU_PERC_Restriction = $L02array[$key]['PERC_PERC'];  //Don't have this yet, will have to get case pick demand
    $PKGU_PERC_Restriction = 1; //Don't have this yet, will have to get case pick demand
    $ITEM_NUMBER = intval($L02array[$key]['ITEM_NUMBER']);

    //call slot quantity logic
    $slotqty_return_array = _slotqty_offsys($var_AVGSHIPQTY, $daystostock, $var_AVGINV, $slowdownsizecutoff, $AVGD_BTW_SLE, $PKGU_PERC_Restriction);

    if (isset($slotqty_return_array['CEILQTY'])) {
        $var_pkgu = intval($L02array[$key]['PACKAGE_UNIT']);
        $var_pkty = $L02array[$key]['PACKAGE_TYPE'];
        $optqty = $slotqty_return_array['OPTQTY'];
        $slotqty = $slotqty_return_array['CEILQTY'];
        include '../connection/NYServer.php';
        //write to table inventory_restricted
        $result2 = $conn1->prepare("INSERT INTO gillingham.inventory_restricted (ID_INV_REST, WHSE_INV_REST, ITEM_INV_REST, PKGU_INV_REST, PKGTYPE_INV_REST, AVGINV_INV_REST, OPTQTY_INV_REST, CEILQTY_INV_REST) values (0,'$whssel', $ITEM_NUMBER ,$var_pkgu,'$var_pkty',$var_AVGINV, $optqty, $slotqty)");
        $result2->execute();
        $conn1 = null;
    } else {
        $slotqty = $slotqty_return_array['OPTQTY'];
    }

    //calculate total slot valume to determine what grid to start
    $totalslotvol = ($slotqty * $var_PCEHEIin * $var_PCELENin * $var_PCEWIDin);

    //loop through available L02 grids to determine smallest location to accomodate slot quantity
    foreach ($L02GridsArray as $key2 => $value) {
        //if total slot volume is less than location volume, then continue

        $var_grid5 = $L02GridsArray[$key2]['LMGRD5'];
        $var_gridheight = $L02GridsArray[$key2]['LMHIGH'];
        $var_griddepth = $L02GridsArray[$key2]['LMDEEP'];
        $var_gridwidth = $L02GridsArray[$key2]['LMWIDE'];
        $var_locvol = $L02GridsArray[$key2]['LMVOL9'];

        //Call the case true fit for L02
        $SUGGESTED_MAX_array = _truefitgrid2iterations($var_grid5, $var_gridheight, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin, $var_caseqty);
        $SUGGESTED_MAX_test = $SUGGESTED_MAX_array[1];

        if ($SUGGESTED_MAX_test >= $slotqty) {
            break;
        }
    }

//for gillingham, if suggested slot qty will not fit in an available location, continue to next best candidate
    if ($SUGGESTED_MAX_test == 0) {
        $skippedkeycount += 1;
        unset($L02array[$key]);
        continue;
    }

    $L02GridsArray[$key2]['GRIDCOUNT'] -= 1;  //subtract used grid from array as no longer available
    if ($L02GridsArray[$key2]['GRIDCOUNT'] <= 0) {
        unset($L02GridsArray[$key2]);
        $L02GridsArray = array_values($L02GridsArray);  //reset array
    }

    $SUGGESTED_MAX = $SUGGESTED_MAX_test;
    //Call the min calc logic
    $SUGGESTED_MIN = intval(_minloc($SUGGESTED_MAX, $var_AVGSHIPQTY, $var_caseqty));

    //append data to array for writing to my_npfmvc table
    $L02array[$key]['SUGGESTED_TIER'] = 'L02';
    $L02array[$key]['SUGGESTED_GRID5'] = $var_grid5;
    $L02array[$key]['SUGGESTED_DEPTH'] = $var_griddepth;
    $L02array[$key]['SUGGESTED_MAX'] = $SUGGESTED_MAX;
    $L02array[$key]['SUGGESTED_MIN'] = $SUGGESTED_MIN;
    $L02array[$key]['SUGGESTED_SLOTQTY'] = $slotqty;
    $L02array[$key]['SUGGESTED_IMPMOVES'] = _implied_daily_moves($SUGGESTED_MAX, $SUGGESTED_MIN, $avgdailyshipqty, $var_AVGINV, $L02array[$key]['SHIP_QTY_MN'], $L02array[$key]['AVGD_BTW_SLE']);
    $L02array[$key]['CURRENT_IMPMOVES'] = _implied_daily_moves($L02array[$key]['CURMAX'], $L02array[$key]['CURMIN'], $avgdailyshipqty, $var_AVGINV, $L02array[$key]['SHIP_QTY_MN'], $L02array[$key]['AVGD_BTW_SLE']);
    $L02array[$key]['SUGGESTED_NEWLOCVOL'] = ($var_locvol);
    $L02array[$key]['SUGGESTED_DAYSTOSTOCK'] = intval($daystostock);
}

//L02 items have been designated.  Loop through L02 array to add to my_npfmvc 
//delete unassigned items from array using $key as the last offset
array_splice($L02array, ($key - $skippedkeycount - 1));

$L02array = array_values($L02array);  //reset array

include '../connection/NYServer.php';

$values = array();
$intranid = 0;
$maxrange = 999;
$counter = 0;
$rowcount = count($L02array);

do {
    if ($maxrange > $rowcount) {  //prevent undefined offset
        $maxrange = $rowcount - 1;
    }

    $data = array();
    $values = array();
    while ($counter <= $maxrange) { //split into 1000 lines segments to insert into table my_npfmvc
        $WAREHOUSE = intval($L02array[$counter]['WAREHOUSE']);
        $ITEM_NUMBER = intval($L02array[$counter]['ITEM_NUMBER']);
        $PACKAGE_UNIT = intval($L02array[$counter]['PACKAGE_UNIT']);
        $PACKAGE_TYPE = $L02array[$counter]['PACKAGE_TYPE'];
        $CUR_LOCATION = $L02array[$counter]['LMLOC'];
        $DAYS_FRM_SLE = intval($L02array[$counter]['DAYS_FRM_SLE']);
        $AVGD_BTW_SLE = intval($L02array[$counter]['AVGD_BTW_SLE']);
        $AVG_INV_OH = intval($L02array[$counter]['AVG_INV_OH']);
        $NBR_SHIP_OCC = intval($L02array[$counter]['NBR_SHIP_OCC']);
        $PICK_QTY_MN = intval($L02array[$counter]['PICK_QTY_MN']);
        $PICK_QTY_SD = $L02array[$counter]['PICK_QTY_SD'];
        $SHIP_QTY_MN = intval($L02array[$counter]['SHIP_QTY_MN']);
        $SHIP_QTY_SD = $L02array[$counter]['SHIP_QTY_SD'];
        $CPCEPKU = intval($L02array[$counter]['CPCEPKU']);
        $CPCCPKU = intval($L02array[$counter]['CPCCPKU']);
        $CPCFLOW = $L02array[$counter]['CPCFLOW'];
        $CPCTOTE = $L02array[$counter]['CPCTOTE'];
        $CPCSHLF = $L02array[$counter]['CPCSHLF'];
        $CPCROTA = $L02array[$counter]['CPCROTA'];
        $CPCESTK = intval($L02array[$counter]['CPCESTK']);
        $CPCLIQU = $L02array[$counter]['CPCLIQU'];
        $CPCELEN = $L02array[$counter]['CPCELEN'];
        $CPCEHEI = $L02array[$counter]['CPCEHEI'];
        $CPCEWID = $L02array[$counter]['CPCEWID'];
        $CPCCLEN = $L02array[$counter]['CPCCLEN'];
        $CPCCHEI = $L02array[$counter]['CPCCHEI'];
        $CPCCWID = $L02array[$counter]['CPCCWID'];
        $LMHIGH = ($L02array[$counter]['LMHIGH']);
        $LMDEEP = ($L02array[$counter]['LMDEEP']);
        $LMWIDE = ($L02array[$counter]['LMWIDE']);
        $LMVOL9 = ($L02array[$counter]['LMVOL9']);
        $LMTIER = $L02array[$counter]['LMTIER'];
        $LMGRD5 = $L02array[$counter]['LMGRD5'];
        $DLY_CUBE_VEL = $L02array[$counter]['DLY_CUBE_VEL'];
        $DLY_PICK_VEL = $L02array[$counter]['DLY_PICK_VEL'];
        $SUGGESTED_TIER = $L02array[$counter]['SUGGESTED_TIER'];
        $SUGGESTED_GRID5 = $L02array[$counter]['SUGGESTED_GRID5'];
        $SUGGESTED_DEPTH = $L02array[$counter]['SUGGESTED_DEPTH'];
        $SUGGESTED_MAX = intval($L02array[$counter]['SUGGESTED_MAX']);
        $SUGGESTED_MIN = intval($L02array[$counter]['SUGGESTED_MIN']);
        $SUGGESTED_SLOTQTY = intval($L02array[$counter]['SUGGESTED_SLOTQTY']);

        $SUGGESTED_IMPMOVES = ($L02array[$counter]['SUGGESTED_IMPMOVES']);
        $CURRENT_IMPMOVES = ($L02array[$counter]['CURRENT_IMPMOVES']);
        $SUGGESTED_NEWLOCVOL = ($L02array[$counter]['SUGGESTED_NEWLOCVOL']);
        $SUGGESTED_DAYSTOSTOCK = intval($L02array[$counter]['SUGGESTED_DAYSTOSTOCK']);
        $AVG_DAILY_PICK = $L02array[$counter]['DAILYPICK'];
        $AVG_DAILY_UNIT = $L02array[$counter]['DAILYUNIT'];
        if ($LMTIER == 'L01') {
            $VCBAY = $CUR_LOCATION;
        } else if ($LMTIER == 'L05') {
            $VCBAY = substr($CUR_LOCATION, 0, 3) . '01';
        } else if (substr($LMGRD5, 0, 2) == 'MB') {
            $VCBAY = substr($CUR_LOCATION, 0, 2) . '0' . substr($CUR_LOCATION, 2, 1);
        } else {
            $VCBAY = substr($CUR_LOCATION, 0, 4);
        }
        $data[] = "($WAREHOUSE,$ITEM_NUMBER,$PACKAGE_UNIT,'$PACKAGE_TYPE','$CUR_LOCATION',$DAYS_FRM_SLE,$AVGD_BTW_SLE,$AVG_INV_OH,$NBR_SHIP_OCC,$PICK_QTY_MN,$PICK_QTY_SD,$SHIP_QTY_MN,$SHIP_QTY_SD,$CPCEPKU,$CPCCPKU,'$CPCFLOW','$CPCTOTE','$CPCSHLF','$CPCROTA',$CPCESTK,'$CPCLIQU',$CPCELEN,$CPCEHEI,$CPCEWID,$CPCCLEN,$CPCCHEI,$CPCCWID,'$LMHIGH','$LMDEEP','$LMWIDE','$LMVOL9','$LMTIER','$LMGRD5',$DLY_CUBE_VEL,$DLY_PICK_VEL,'$SUGGESTED_TIER','$SUGGESTED_GRID5','$SUGGESTED_DEPTH',$SUGGESTED_MAX,$SUGGESTED_MIN,$SUGGESTED_SLOTQTY,'$SUGGESTED_IMPMOVES','$CURRENT_IMPMOVES','$SUGGESTED_NEWLOCVOL',$SUGGESTED_DAYSTOSTOCK,'$AVG_DAILY_PICK','$AVG_DAILY_UNIT', '$VCBAY', $JAX_ENDCAP)";
        $counter += 1;
    }
    $values = implode(',', $data);

    if (empty($values)) {
        break;
    }

    $sql = "INSERT IGNORE INTO gillingham.my_npfmvc ($columns) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();

    $maxrange += 1000;
} while ($counter <= $rowcount);



