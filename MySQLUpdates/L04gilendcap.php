
<?php

$JAX_ENDCAP = 1;


$slowdownsizecutoff = 99999;
include '../connection/NYServer.php';
include_once '../globalfunctions/slottingfunctions.php';

$var_CURTF = NULL;

$baycube = $conn1->prepare("SELECT 
                                                                WALKBAY AS BAY,
                                                                SUM(slotmaster_grcube) * 1000 AS GRIDVOL,
                                                                SUM(slotmaster_usecube) * 1000 AS USEVOL
                                                            FROM
                                                                gillingham.slotmaster
                                                                    JOIN
                                                                gillingham.bay_location ON LOCATION = slotmaster_loc
                                                            WHERE
                                                                WALKBAY = '00' and slotmaster_tier = 'L04'
                                                            GROUP BY WALKBAY");
$baycube->execute();
$baycubearray = $baycube->fetchAll(pdo::FETCH_ASSOC);

//subtract cube from items on hold from L04 cube
$holdcube = $conn1->prepare("SELECT 
                                                                WALKBAY AS HOLDBAY, SUM(SUGGESTED_NEWLOCVOL) AS HOLDBAYVOL
                                                            FROM
                                                                gillingham.item_settings
                                                                    JOIN
                                                                gillingham.bay_location ON LOCATION = HOLDLOCATION
                                                                    JOIN
                                                                gillingham.my_npfmvc ON CUR_LOCATION = HOLDLOCATION
                                                            WHERE
                                                                HOLDTIER = 'L04' AND WALKBAY = '00'
                                                            GROUP BY HOLDBAY");
$holdcube->execute();
$holdcubearray = $holdcube->fetchAll(pdo::FETCH_ASSOC);

foreach ($holdcubearray as $key => $value) {
    $bay = $holdcubearray[$key]['HOLDBAY'];
    $baysubtractkey = array_search($bay, array_column($baycubearray, 'BAY'));
    $baycubearray[$baysubtractkey]['BAYVOL'] = $baycubearray[$baysubtractkey]['BAYVOL'] - $holdcubearray[$key]['HOLDBAYVOL'];
}

$jaxendcapvol = $baycubearray[0]['GRIDVOL'] * 1000;

$L04GridsArray_endcap = array();


$L04GridsArray_endcap[0]['LMGRD5'] = 'MB1';
$L04GridsArray_endcap[0]['LMHIGH'] = 8;
$L04GridsArray_endcap[0]['LMDEEP'] = 12;
$L04GridsArray_endcap[0]['LMWIDE'] = 9;
$L04GridsArray_endcap[0]['LMVOL9'] = 864;
$L04GridsArray_endcap[0]['GRIDCOUNT'] = 99999;

$L04GridsArray_endcap[1]['LMGRD5'] = 'MB3';
$L04GridsArray_endcap[1]['LMHIGH'] = 10;
$L04GridsArray_endcap[1]['LMDEEP'] = 19.5;
$L04GridsArray_endcap[1]['LMWIDE'] = 9.5;
$L04GridsArray_endcap[1]['LMVOL9'] = 1852;
$L04GridsArray_endcap[1]['GRIDCOUNT'] = 99999;

$L04GridsArray_endcap[2]['LMGRD5'] = 'SR40';
$L04GridsArray_endcap[2]['LMHIGH'] = 18;
$L04GridsArray_endcap[2]['LMDEEP'] = 40;
$L04GridsArray_endcap[2]['LMWIDE'] = 15;
$L04GridsArray_endcap[2]['LMVOL9'] = 10800;
$L04GridsArray_endcap[2]['GRIDCOUNT'] = 99999;

$L04GridsArray_endcap[3]['LMGRD5'] = 'SR26';
$L04GridsArray_endcap[3]['LMHIGH'] = 24;
$L04GridsArray_endcap[3]['LMDEEP'] = 40;
$L04GridsArray_endcap[3]['LMWIDE'] = 15;
$L04GridsArray_endcap[3]['LMVOL9'] = 14400;
$L04GridsArray_endcap[3]['GRIDCOUNT'] = 99999;

$L04GridsArray_endcap[4]['LMGRD5'] = 'H01';
$L04GridsArray_endcap[4]['LMHIGH'] = 15.5;
$L04GridsArray_endcap[4]['LMDEEP'] = 40;
$L04GridsArray_endcap[4]['LMWIDE'] = 27.5;
$L04GridsArray_endcap[4]['LMVOL9'] = 17050;
$L04GridsArray_endcap[4]['GRIDCOUNT'] = 99999;

$L04GridsArray_endcap[5]['LMGRD5'] = 'SR27';
$L04GridsArray_endcap[5]['LMHIGH'] = 20;
$L04GridsArray_endcap[5]['LMDEEP'] = 40;
$L04GridsArray_endcap[5]['LMWIDE'] = 26;
$L04GridsArray_endcap[5]['LMVOL9'] = 20800;
$L04GridsArray_endcap[5]['GRIDCOUNT'] = 99999;

$L04GridsArray_endcap[6]['LMGRD5'] = 'SR39';
$L04GridsArray_endcap[6]['LMHIGH'] = 31;
$L04GridsArray_endcap[6]['LMDEEP'] = 40;
$L04GridsArray_endcap[6]['LMWIDE'] = 18;
$L04GridsArray_endcap[6]['LMVOL9'] = 22320;
$L04GridsArray_endcap[6]['GRIDCOUNT'] = 99999;

$L04GridsArray_endcap[7]['LMGRD5'] = 'SR29';
$L04GridsArray_endcap[7]['LMHIGH'] = 32;
$L04GridsArray_endcap[7]['LMDEEP'] = 40;
$L04GridsArray_endcap[7]['LMWIDE'] = 31;
$L04GridsArray_endcap[7]['LMVOL9'] = 39680;
$L04GridsArray_endcap[7]['GRIDCOUNT'] = 99999;

$L04GridsArray_endcap[8]['LMGRD5'] = 'SR31';
$L04GridsArray_endcap[8]['LMHIGH'] = 32;
$L04GridsArray_endcap[8]['LMDEEP'] = 40;
$L04GridsArray_endcap[8]['LMWIDE'] = 41.5;
$L04GridsArray_endcap[8]['LMVOL9'] = 53120;
$L04GridsArray_endcap[8]['GRIDCOUNT'] = 99999;

$L04GridsArray_endcap[9]['LMGRD5'] = 'SR33';
$L04GridsArray_endcap[9]['LMHIGH'] = 42;
$L04GridsArray_endcap[9]['LMDEEP'] = 40;
$L04GridsArray_endcap[9]['LMWIDE'] = 41.5;
$L04GridsArray_endcap[9]['LMVOL9'] = 69720;
$L04GridsArray_endcap[9]['GRIDCOUNT'] = 99999;



$L04sql = $conn1->prepare("SELECT DISTINCT
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
                                                        $sql_dailyunit AS DAILYUNIT,
                                                     S.CASETF
                              
                                                    FROM
                                                        gillingham.nptsld A
                                                            JOIN
                                                        gillingham.npfcpcsettings X ON X.CPCITEM = A.ITEM
                                                            JOIN
                                                        gillingham.slotmaster D ON D.slotmaster_item = A.ITEM
                                                            LEFT JOIN
                                                        gillingham.my_npfmvc F ON F.ITEM_NUMBER = A.ITEM
                                      LEFT JOIN
                                gillingham.item_settings S on
                                       S.ITEM = A.ITEM 
                            WHERE
                                     A.PKTYPE = ('EA')
                                     and slotmaster_tier in ('L01','L02','L04')
                                    and A.DAYCOUNT >= 1
                                    -- and AVGD_BTW_SLE > 0
                                    and F.ITEM_NUMBER IS NULL
                                        
                            ORDER BY DLY_CUBE_VEL desc");
$L04sql->execute();
$L04array_endcap = $L04sql->fetchAll(pdo::FETCH_ASSOC);


foreach ($L04array_endcap as $key => $value) {

    $ITEM_NUMBER = intval($L04array_endcap[$key]['ITEM_NUMBER']);

    //Check OK in Shelf Setting
    $var_OKINSHLF = $L04array_endcap[$key]['CPCSHLF'];
    $var_stacklimit = $L04array_endcap[$key]['CPCESTK'];
    $var_casetf = $L04array_endcap[$key]['CASETF'];
//    $var_CURTF = $L04array_endcap[$key]['CURTF'];

    $var_AVGSHIPQTY = $L04array_endcap[$key]['SHIP_QTY_MN'];
    $AVGD_BTW_SLE = intval($L04array_endcap[$key]['AVGD_BTW_SLE']);
    if ($AVGD_BTW_SLE == 0) {
        $AVGD_BTW_SLE = 999;
    }

    $var_AVGINV = intval($L04array_endcap[$key]['AVG_INV_OH']);

    $avgdailyshipqty = number_format($var_AVGSHIPQTY / $AVGD_BTW_SLE, 8);
    if ($avgdailyshipqty == 0) {
        $avgdailyshipqty = .000000001;
    }
    $var_PCLIQU = $L04array_endcap[$key]['CPCLIQU'];

    $var_PCEHEIin = $L04array_endcap[$key]['CPCEHEI'];
    if ($var_PCEHEIin == 0) {
        $var_PCEHEIin = $L04array_endcap[$key]['CPCCHEI'];
    }

    if ($var_PCEHEIin == 0) {
        $var_PCEHEIin = 1;
    }

    $var_PCELENin = $L04array_endcap[$key]['CPCELEN'];
    if ($var_PCELENin == 0) {
        $var_PCELENin = $L04array_endcap[$key]['CPCCLEN'];
    }

    if ($var_PCELENin == 0) {
        $var_PCELENin = 1;
    }

    $var_PCEWIDin = $L04array_endcap[$key]['CPCEWID'];
    if ($var_PCEWIDin == 0) {
        $var_PCEWIDin = $L04array_endcap[$key]['CPCCWID'];
    }

    if ($var_PCEWIDin == 0) {
        $var_PCEWIDin = 1;
    }

    $var_PCCHEIin = $L04array_endcap[$key]['CPCCHEI'];
    $var_PCCLENin = $L04array_endcap[$key]['CPCCLEN'];
    $var_PCCWIDin = $L04array_endcap[$key]['CPCCWID'];

    $var_eachqty = $L04array_endcap[$key]['CPCEPKU'];
    $var_caseqty = $L04array_endcap[$key]['CPCCPKU'];
    if ($var_eachqty == 0) {
        $var_eachqty = 1;
    }

    if ($AVGD_BTW_SLE <= 1) {
        $daystostock = 30;
    } elseif ($AVGD_BTW_SLE <= 2) {
        $daystostock = 18;
    } elseif ($AVGD_BTW_SLE <= 3) {
        $daystostock = 13;
    } elseif ($AVGD_BTW_SLE <= 4) {
        $daystostock = 10;
    } elseif ($AVGD_BTW_SLE <= 5) {
        $daystostock = 8;
    } elseif ($AVGD_BTW_SLE <= 7) {
        $daystostock = 6;
    } elseif ($AVGD_BTW_SLE <= 10) {
        $daystostock = 4;
    } elseif ($AVGD_BTW_SLE <= 15) {
        $daystostock = 3;
    } elseif ($AVGD_BTW_SLE <= 20) {
        $daystostock = 1;
    } elseif ($AVGD_BTW_SLE <= 25) {
        $daystostock = 1;
    } elseif ($AVGD_BTW_SLE <= 30) {
        $daystostock = 1;
    } elseif ($AVGD_BTW_SLE <= 40) {
        $daystostock = 1;
    } elseif ($AVGD_BTW_SLE <= 50) {
        $daystostock = 1;
    } else {
        $daystostock = 1;
    }




//    $PKGU_PERC_Restriction = $L04array_endcap[$key]['PERC_PERC'];
    $PKGU_PERC_Restriction = intval(1);

    //call slot quantity logic
    $slotqty_return_array = _slotqty_offsys($var_AVGSHIPQTY, $daystostock, $var_AVGINV, $slowdownsizecutoff, $AVGD_BTW_SLE, $PKGU_PERC_Restriction);

    if (isset($slotqty_return_array['CEILQTY'])) {
        $var_pkgu = intval($L04array_endcap[$key]['PACKAGE_UNIT']);
        $var_pkty = $L04array_endcap[$key]['PACKAGE_TYPE'];
        $optqty = $slotqty_return_array['OPTQTY'];
        $slotqty = $slotqty_return_array['CEILQTY'];
    } else {
        $slotqty = $slotqty_return_array['OPTQTY'];
    }


    if (($slotqty * $var_AVGINV) == 0) {  //if both slot qty and avg inv = 0, then default to 1 unit as slot qty
        $slotqty = 1;
    } elseif ($slotqty == 0) {
        $slotqty = $var_AVGINV;
    }

    //calculate total slot valume to determine what grid to start
    $totalslotvol = $slotqty * $var_PCEHEIin * $var_PCELENin * $var_PCEWIDin / 100;

//    if ($var_OKINSHLF == 'N') {
//        $lastusedgrid5 = '15T11';
//    } else {
//        $lastusedgrid5 = '15S47';
//    }
//    $maxkey = count($L04GridsArray) - 1; //if reach max key and not figured true fit, calc at max
    //loop through available L04 grids to determine smallest location to accomodate slot quantity
    foreach ($L04GridsArray_endcap as $key2 => $value) {
        //if total slot volume is less than location volume, then continue
//        if ($totalslotvol > $L04GridsArray[$key2]['LMVOL9']) {
//            continue;
//        }

        $var_grid5 = $L04GridsArray_endcap [$key2]['LMGRD5'];
        if ($var_OKINSHLF == 'N' && substr($var_grid5, 2, 1) == 'S') {
            continue;
        }
        $var_gridheight = $L04GridsArray_endcap [$key2]['LMHIGH'];
        $var_griddepth = $L04GridsArray_endcap [$key2]['LMDEEP'];
        $var_gridwidth = $L04GridsArray_endcap [$key2]['LMWIDE'];
        $var_locvol = $L04GridsArray_endcap [$key2]['LMVOL9'];

        //Call the true fit for L04`
        if (($var_PCCHEIin * $var_PCCLENin * $var_PCCWIDin * $var_caseqty > 0)) {
            $SUGGESTED_MAX_array = _truefitgrid2iterations_case($var_grid5, $var_gridheight, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCCHEIin, $var_PCCLENin, $var_PCCWIDin, $var_caseqty);
        } else if ($var_stacklimit > 0) {
            $SUGGESTED_MAX_array = _truefit($var_grid5, $var_gridheight, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin, 0, $var_stacklimit);
        } else {
            $SUGGESTED_MAX_array = _truefitgrid2iterations($var_grid5, $var_gridheight, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
        }
        $SUGGESTED_MAX_test = $SUGGESTED_MAX_array[1];



        if ($SUGGESTED_MAX_test >= $slotqty) {
            $lastusedgrid5 = $var_grid5;
            break;
        }

        //to prevent issue of suggesting a shelf when not accpetable according to OK in flag
        $lastusedgrid5 = $var_grid5;
    }

    if ($lastusedgrid5 !== 'SR29' && $lastusedgrid5 !== 'SR31' && $lastusedgrid5 !== 'SR33') {
        unset($L04array_endcap[$key]);
        continue;
    }

    $SUGGESTED_MAX = $SUGGESTED_MAX_test;

    if ($SUGGESTED_MAX_test == 0) {
        unset($L04array_endcap[$key]);
        continue;
    }

    //Call the min calc logic
    $SUGGESTED_MIN = intval(_minloc($SUGGESTED_MAX, $var_AVGSHIPQTY, $var_eachqty));

    //append data to array for writing to my_npfmvc table
    $L04array_endcap[$key]['SUGGESTED_TIER'] = 'L04';
    $L04array_endcap[$key]['SUGGESTED_GRID5'] = $lastusedgrid5;
    $L04array_endcap[$key]['SUGGESTED_DEPTH'] = $var_griddepth;
    $L04array_endcap[$key]['SUGGESTED_MAX'] = $SUGGESTED_MAX;
    $L04array_endcap[$key]['SUGGESTED_MIN'] = $SUGGESTED_MIN;
    $L04array_endcap[$key]['SUGGESTED_SLOTQTY'] = $slotqty;
    $L04array_endcap[$key]['SUGGESTED_IMPMOVES'] = _implied_daily_moves($SUGGESTED_MAX, $SUGGESTED_MIN, $avgdailyshipqty, $var_AVGINV, $L04array_endcap[$key]['SHIP_QTY_MN'], $L04array_endcap[$key]['AVGD_BTW_SLE']);
    $L04array_endcap[$key]['CURRENT_IMPMOVES'] = _implied_daily_moves_withcurrentTF($L04array_endcap[$key]['CURMAX'], $L04array_endcap[$key]['CURMIN'], $avgdailyshipqty, $var_AVGINV, $L04array_endcap[$key]['SHIP_QTY_MN'], $L04array_endcap[$key]['AVGD_BTW_SLE'], $var_CURTF);
//    $L04array_endcap[$key]['SUGGESTED_NEWLOCVOL'] = intval(substr($lastusedgrid5, 0, 2)) * intval(substr($lastusedgrid5, 3, 2)) * intval($var_griddepth);
    $L04array_endcap[$key]['SUGGESTED_NEWLOCVOL'] = $var_locvol;
    $L04array_endcap[$key]['SUGGESTED_DAYSTOSTOCK'] = intval($daystostock);
    $L04array_endcap[$key]['PPI'] = number_format($L04array_endcap[$key]['DAILYPICK'] / $L04array_endcap[$key]['SUGGESTED_NEWLOCVOL'], 10);
}
//sort by PPI descending
$L04array_endcap = array_values($L04array_endcap);  //reset array
$sort = array();
foreach ($L04array_endcap as $k => $v) {
    $sort['PPI'][$k] = $v['PPI'];
    $sort['SUGGESTED_NEWLOCVOL'][$k] = $v['SUGGESTED_NEWLOCVOL'];
}
array_multisort($sort['PPI'], SORT_DESC, $sort['SUGGESTED_NEWLOCVOL'], SORT_ASC, $L04array_endcap);



foreach ($L04array_endcap as $key => $value) {
    if ($jaxendcapvol < 0) {
        break;  //if all available L04 volume has been used, exit
    }



//********** START of SQL to ADD TO TABLE **********

    $WAREHOUSE = intval($L04array_endcap[$key]['WAREHOUSE']);
    $ITEM_NUMBER = intval($L04array_endcap[$key]['ITEM_NUMBER']);
    $PACKAGE_UNIT = intval($L04array_endcap[$key]['PACKAGE_UNIT']);
    $PACKAGE_TYPE = $L04array_endcap[$key]['PACKAGE_TYPE'];
    $CUR_LOCATION = $L04array_endcap[$key]['LMLOC'];
    $DAYS_FRM_SLE = intval($L04array_endcap[$key]['DAYS_FRM_SLE']);
    $AVGD_BTW_SLE = intval($L04array_endcap[$key]['AVGD_BTW_SLE']);
    $AVG_INV_OH = intval($L04array_endcap[$key]['AVG_INV_OH']);
    $NBR_SHIP_OCC = intval($L04array_endcap[$key]['NBR_SHIP_OCC']);
    $PICK_QTY_MN = intval($L04array_endcap[$key]['PICK_QTY_MN']);
    $PICK_QTY_SD = $L04array_endcap[$key]['PICK_QTY_SD'];
    $SHIP_QTY_MN = intval($L04array_endcap[$key]['SHIP_QTY_MN']);
    $SHIP_QTY_SD = $L04array_endcap[$key]['SHIP_QTY_SD'];
    $CPCEPKU = intval($L04array_endcap[$key]['CPCEPKU']);
    $CPCCPKU = intval($L04array_endcap[$key]['CPCCPKU']);
    $CPCFLOW = $L04array_endcap[$key]['CPCFLOW'];
    $CPCTOTE = $L04array_endcap[$key]['CPCTOTE'];
    $CPCSHLF = $L04array_endcap[$key]['CPCSHLF'];
    $CPCROTA = $L04array_endcap[$key]['CPCROTA'];
    $CPCESTK = intval($L04array_endcap[$key]['CPCESTK']);
    $CPCLIQU = $L04array_endcap[$key]['CPCLIQU'];
    $CPCELEN = $L04array_endcap[$key]['CPCELEN'];
    $CPCEHEI = $L04array_endcap[$key]['CPCEHEI'];
    $CPCEWID = $L04array_endcap[$key]['CPCEWID'];
    $CPCCLEN = $L04array_endcap[$key]['CPCCLEN'];
    $CPCCHEI = $L04array_endcap[$key]['CPCCHEI'];
    $CPCCWID = $L04array_endcap[$key]['CPCCWID'];
    $LMHIGH = ($L04array_endcap[$key]['LMHIGH']);
    $LMDEEP = ($L04array_endcap[$key]['LMDEEP']);
    $LMWIDE = ($L04array_endcap[$key]['LMWIDE']);
    $LMVOL9 = ($L04array_endcap[$key]['LMVOL9']);
    $LMTIER = $L04array_endcap[$key]['LMTIER'];
    $LMGRD5 = $L04array_endcap[$key]['LMGRD5'];
    $DLY_CUBE_VEL = intval($L04array_endcap[$key]['DLY_CUBE_VEL']);
    $DLY_PICK_VEL = intval($L04array_endcap[$key]['DLY_PICK_VEL']);
    $SUGGESTED_TIER = $L04array_endcap[$key]['SUGGESTED_TIER'];
    $SUGGESTED_GRID5 = $L04array_endcap[$key]['SUGGESTED_GRID5'];
    $SUGGESTED_DEPTH = $L04array_endcap[$key]['SUGGESTED_DEPTH'];
    $SUGGESTED_MAX = intval($L04array_endcap[$key]['SUGGESTED_MAX']);
    $SUGGESTED_MIN = intval($L04array_endcap[$key]['SUGGESTED_MIN']);
    $SUGGESTED_SLOTQTY = intval($L04array_endcap[$key]['SUGGESTED_SLOTQTY']);

    $SUGGESTED_IMPMOVES = ($L04array_endcap[$key]['SUGGESTED_IMPMOVES']);
    $CURRENT_IMPMOVES = ($L04array_endcap[$key]['CURRENT_IMPMOVES']);
    $SUGGESTED_NEWLOCVOL = ($L04array_endcap[$key]['SUGGESTED_NEWLOCVOL']);
    $SUGGESTED_DAYSTOSTOCK = intval($L04array_endcap[$key]['SUGGESTED_DAYSTOSTOCK']);
    $AVG_DAILY_PICK = $L04array_endcap[$key]['DAILYPICK'];
    $AVG_DAILY_UNIT = $L04array_endcap[$key]['DAILYUNIT'];
    $test = substr($LMGRD5, 0, 2);

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

    if ($key % 100 == 0 && $key <> 0) {
        $values = implode(',', $data);


        $sql = "INSERT IGNORE INTO gillingham.my_npfmvc ($columns) VALUES $values";
        $query = $conn1->prepare($sql);
        $query->execute();
        $data = array();
    }

//********** END of SQL to ADD TO TABLE **********


    $jaxendcapvol -= $SUGGESTED_NEWLOCVOL;
}
$values = implode(',', $data);
if (count($data) >= 1) {

    $sql = "INSERT IGNORE INTO gillingham.my_npfmvc ($columns) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();
    $data = array();
}
