
<?php
$var_CURTF = NULL;
$JAX_ENDCAP = 0;
$slowdownsizecutoff = 99999;

//what is total L04 volume available.  Only used for capacity constraints
$L04onholdkey = array_search('L04', array_column($holdvolumearray, 'SUGGESTED_TIER')); //Find 'L06' associated key in items on hold array to subtract from available volume
$L04key = array_search('L04', array_column($allvolumearray, 'LMTIER')); //Find 'L04' associated key
if ($L04onholdkey !== FALSE) {
    $L04Vol = intval($allvolumearray[$L04key]['TIER_VOL']) - intval($holdvolumearray[$L04onholdkey]['ASSVOL']);
} else {
    $L04Vol = intval($allvolumearray[$L04key]['TIER_VOL']);
}

echo $whssel . ' starting volume is ' . $L04Vol;

$sqlexclude = '';

//*** Step 4: L04 Designation ***

include '../connection/connection_details.php';
//Pull in available L04 Grid5s by volume ascending order

//with useable dimensions
$L04GridsSQL = $conn1->prepare("SELECT 
                                                                slotmaster_dimgroup AS LMGRD5,
                                                                slotmaster_usehigh AS LMHIGH,
                                                                slotmaster_usedeep AS LMDEEP,
                                                                slotmaster_usewide AS LMWIDE,
                                                                (slotmaster_usecube) * 1000 AS LMVOL9,
                                                                COUNT(*) AS GRIDCOUNT
                                                            FROM
                                                                gillingham.slotmaster
                                                            WHERE
                                                                slotmaster_allowpick = 'Y'
                                                                    AND slotmaster_tier = 'L04'
                                                            GROUP BY slotmaster_dimgroup , slotmaster_usehigh , slotmaster_usedeep , slotmaster_usewide, slotmaster_usecube
                                                            HAVING GRIDCOUNT >= 50
                                                            ORDER BY LMVOL9 ASC");
$L04GridsSQL->execute();
$L04GridsArray = $L04GridsSQL->fetchAll(pdo::FETCH_ASSOC);

//with actual dimensions
//$L04GridsSQL = $conn1->prepare("SELECT 
//                                                                slotmaster_dimgroup AS LMGRD5,
//                                                                slotmaster_grhigh AS LMHIGH,
//                                                                slotmaster_grdeep AS LMDEEP,
//                                                                slotmaster_grwide AS LMWIDE,
//                                                                (slotmaster_grcube) * 1000 AS LMVOL9,
//                                                                COUNT(*) AS GRIDCOUNT
//                                                            FROM
//                                                                gillingham.slotmaster
//                                                            WHERE
//                                                                slotmaster_allowpick = 'Y'
//                                                                    AND slotmaster_tier = 'L04'
//                                                            GROUP BY slotmaster_dimgroup , slotmaster_grhigh , slotmaster_grdeep , slotmaster_grwide, slotmaster_grcube
//                                                            HAVING GRIDCOUNT >= 50
//                                                            ORDER BY LMVOL9 ASC");
//$L04GridsSQL->execute();
//$L04GridsArray = $L04GridsSQL->fetchAll(pdo::FETCH_ASSOC);

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
                                    and A.DAYCOUNT >= 1
                                    and slotmaster_tier in ('L01','L02','L04')
                                    -- and AVGD_BTW_SLE > 0
                                    and F.ITEM_NUMBER IS NULL
                                        
                            ORDER BY DLY_CUBE_VEL desc");
$L04sql->execute();
$L04array = $L04sql->fetchAll(pdo::FETCH_ASSOC);



foreach ($L04array as $key => $value) {
    if ($L04Vol < 0) {
        break;  //if all available L04 volume has been used, exit
    }
    $ITEM_NUMBER = intval($L04array[$key]['ITEM_NUMBER']);
    //Check OK in Shelf Setting
    $var_OKINSHLF = $L04array[$key]['CPCSHLF'];
    $var_stacklimit = $L04array[$key]['CPCESTK'];
    $var_casetf = $L04array[$key]['CASETF'];
//    $var_CURTF = $L04array[$key]['CURTF'];

    $var_AVGSHIPQTY = $L04array[$key]['SHIP_QTY_MN'];
    $AVGD_BTW_SLE = intval($L04array[$key]['AVGD_BTW_SLE']);
    if ($AVGD_BTW_SLE == 0) {
        $AVGD_BTW_SLE = 999;
    }

    $var_AVGINV = intval($L04array[$key]['AVG_INV_OH']);

    $avgdailyshipqty = number_format($var_AVGSHIPQTY / $AVGD_BTW_SLE, 8);
    if ($avgdailyshipqty == 0) {
        $avgdailyshipqty = .000000001;
    }
    $var_PCLIQU = $L04array[$key]['CPCLIQU'];

    $var_PCEHEIin = $L04array[$key]['CPCEHEI'];
    if ($var_PCEHEIin == 0) {
        $var_PCEHEIin = $L04array[$key]['CPCCHEI'];
    }

    if ($var_PCEHEIin == 0) {
        $var_PCEHEIin = 1;
    }

    $var_PCELENin = $L04array[$key]['CPCELEN'];
    if ($var_PCELENin == 0) {
        $var_PCELENin = $L04array[$key]['CPCCLEN'];
    }

    if ($var_PCELENin == 0) {
        $var_PCELENin = 1;
    }

    $var_PCEWIDin = $L04array[$key]['CPCEWID'];
    if ($var_PCEWIDin == 0) {
        $var_PCEWIDin = $L04array[$key]['CPCCWID'];
    }

    if ($var_PCEWIDin == 0) {
        $var_PCEWIDin = 1;
    }

    $var_PCCHEIin = $L04array[$key]['CPCCHEI'];
    $var_PCCLENin = $L04array[$key]['CPCCLEN'];
    $var_PCCWIDin = $L04array[$key]['CPCCWID'];

    $var_eachqty = $L04array[$key]['CPCEPKU'];
    $var_caseqty = $L04array[$key]['CPCCPKU'];
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

    $PKGU_PERC_Restriction = intval(1);
//    $PKGU_PERC_Restriction = $L04array[$key]['PERC_PERC'];
    //call slot quantity logic
    $slotqty_return_array = _slotqty_offsys($var_AVGSHIPQTY, $daystostock, $var_AVGINV, $slowdownsizecutoff, $AVGD_BTW_SLE, $PKGU_PERC_Restriction);

    if (isset($slotqty_return_array['CEILQTY'])) {
        $var_pkgu = intval($L04array[$key]['PACKAGE_UNIT']);
        $var_pkty = $L04array[$key]['PACKAGE_TYPE'];
        $optqty = $slotqty_return_array['OPTQTY'];
        $slotqty = $slotqty_return_array['CEILQTY'];
        //write to table inventory_restricted

        $result2 = $conn1->prepare("INSERT INTO gillingham.inventory_restricted (ID_INV_REST, WHSE_INV_REST, ITEM_INV_REST, PKGU_INV_REST, PKGTYPE_INV_REST, AVGINV_INV_REST, OPTQTY_INV_REST, CEILQTY_INV_REST) values (0,'$whssel', $ITEM_NUMBER ,$var_pkgu,'$var_pkty',$var_AVGINV, $optqty, $slotqty)");
        $result2->execute();
    } else {
        $slotqty = $slotqty_return_array['OPTQTY'];
    }


    if (($slotqty * $var_AVGINV) == 0) {  //if both slot qty and avg inv = 0, then default to 1 unit as slot qty
        $slotqty = 1;
    } elseif ($slotqty == 0) {
        $slotqty = $var_AVGINV;
    }


//    if ($var_OKINSHLF == 'N') {
//        $lastusedgrid5 = '15T11';
//    } else {
//        $lastusedgrid5 = '15S47';
//    }
//    $maxkey = count($L04GridsArray) - 1; //if reach max key and not figured true fit, calc at max
    //loop through available L04 grids to determine smallest location to accomodate slot quantity
    foreach ($L04GridsArray as $key2 => $value) {
        //if total slot volume is less than location volume, then continue
//        if ($totalslotvol > $L04GridsArray[$key2]['LMVOL9']) {
//            continue;
//        }

        $var_grid5 = $L04GridsArray[$key2]['LMGRD5'];
        if ($var_OKINSHLF == 'N' && substr($var_grid5, 2, 1) == 'S') {
            continue;
        }
        $var_gridheight = $L04GridsArray[$key2]['LMHIGH'];
        $var_griddepth = $L04GridsArray[$key2]['LMDEEP'];
        $var_gridwidth = $L04GridsArray[$key2]['LMWIDE'];
        $var_locvol = $L04GridsArray[$key2]['LMVOL9'];

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


    $SUGGESTED_MAX = $SUGGESTED_MAX_test;
   
    //Call the min calc logic
    $SUGGESTED_MIN = intval(_minloc($SUGGESTED_MAX, $var_AVGSHIPQTY, $var_eachqty));

    //append data to array for writing to my_npfmvc table
    $L04array[$key]['SUGGESTED_TIER'] = 'L04';
    $L04array[$key]['SUGGESTED_GRID5'] = $lastusedgrid5;
    $L04array[$key]['SUGGESTED_DEPTH'] = $var_griddepth;
    $L04array[$key]['SUGGESTED_MAX'] = $SUGGESTED_MAX;
    $L04array[$key]['SUGGESTED_MIN'] = $SUGGESTED_MIN;
    $L04array[$key]['SUGGESTED_SLOTQTY'] = $slotqty;
    $L04array[$key]['SUGGESTED_IMPMOVES'] = _implied_daily_moves($SUGGESTED_MAX, $SUGGESTED_MIN, $avgdailyshipqty, $var_AVGINV, $L04array[$key]['SHIP_QTY_MN'], $L04array[$key]['AVGD_BTW_SLE']);
    $L04array[$key]['CURRENT_IMPMOVES'] = _implied_daily_moves_withcurrentTF($L04array[$key]['CURMAX'], $L04array[$key]['CURMIN'], $avgdailyshipqty, $var_AVGINV, $L04array[$key]['SHIP_QTY_MN'], $L04array[$key]['AVGD_BTW_SLE'], $var_CURTF);

    $L04array[$key]['SUGGESTED_NEWLOCVOL'] = $var_locvol;
    $L04array[$key]['SUGGESTED_DAYSTOSTOCK'] = intval($daystostock);

    //********** START of SQL to ADD TO TABLE **********


    $WAREHOUSE = intval($L04array[$key]['WAREHOUSE']);
    $ITEM_NUMBER = intval($L04array[$key]['ITEM_NUMBER']);
    $PACKAGE_UNIT = intval($L04array[$key]['PACKAGE_UNIT']);
    $PACKAGE_TYPE = $L04array[$key]['PACKAGE_TYPE'];
    $CUR_LOCATION = $L04array[$key]['LMLOC'];
    $DAYS_FRM_SLE = intval($L04array[$key]['DAYS_FRM_SLE']);
    $AVGD_BTW_SLE = intval($L04array[$key]['AVGD_BTW_SLE']);
    $AVG_INV_OH = intval($L04array[$key]['AVG_INV_OH']);
    $NBR_SHIP_OCC = intval($L04array[$key]['NBR_SHIP_OCC']);
    $PICK_QTY_MN = intval($L04array[$key]['PICK_QTY_MN']);
    $PICK_QTY_SD = $L04array[$key]['PICK_QTY_SD'];
    $SHIP_QTY_MN = intval($L04array[$key]['SHIP_QTY_MN']);
    $SHIP_QTY_SD = $L04array[$key]['SHIP_QTY_SD'];
    $CPCEPKU = intval($L04array[$key]['CPCEPKU']);
    $CPCCPKU = intval($L04array[$key]['CPCCPKU']);
    $CPCFLOW = $L04array[$key]['CPCFLOW'];
    $CPCTOTE = $L04array[$key]['CPCTOTE'];
    $CPCSHLF = $L04array[$key]['CPCSHLF'];
    $CPCROTA = $L04array[$key]['CPCROTA'];
    $CPCESTK = intval($L04array[$key]['CPCESTK']);
    $CPCLIQU = $L04array[$key]['CPCLIQU'];
    $CPCELEN = $L04array[$key]['CPCELEN'];
    $CPCEHEI = $L04array[$key]['CPCEHEI'];
    $CPCEWID = $L04array[$key]['CPCEWID'];
    $CPCCLEN = $L04array[$key]['CPCCLEN'];
    $CPCCHEI = $L04array[$key]['CPCCHEI'];
    $CPCCWID = $L04array[$key]['CPCCWID'];
    $LMHIGH = ($L04array[$key]['LMHIGH']);
    $LMDEEP = ($L04array[$key]['LMDEEP']);
    $LMWIDE = ($L04array[$key]['LMWIDE']);
    $LMVOL9 = ($L04array[$key]['LMVOL9']);
    $LMTIER = $L04array[$key]['LMTIER'];
    $LMGRD5 = $L04array[$key]['LMGRD5'];
    $DLY_CUBE_VEL = intval($L04array[$key]['DLY_CUBE_VEL']);
    $DLY_PICK_VEL = intval($L04array[$key]['DLY_PICK_VEL']);
    $SUGGESTED_TIER = $L04array[$key]['SUGGESTED_TIER'];
    $SUGGESTED_GRID5 = $L04array[$key]['SUGGESTED_GRID5'];
    $SUGGESTED_DEPTH = $L04array[$key]['SUGGESTED_DEPTH'];
    $SUGGESTED_MAX = intval($L04array[$key]['SUGGESTED_MAX']);
    $SUGGESTED_MIN = intval($L04array[$key]['SUGGESTED_MIN']);
    $SUGGESTED_SLOTQTY = intval($L04array[$key]['SUGGESTED_SLOTQTY']);

    $SUGGESTED_IMPMOVES = ($L04array[$key]['SUGGESTED_IMPMOVES']);
    $CURRENT_IMPMOVES = ($L04array[$key]['CURRENT_IMPMOVES']);
    $SUGGESTED_NEWLOCVOL = ($L04array[$key]['SUGGESTED_NEWLOCVOL']);
    $SUGGESTED_DAYSTOSTOCK = intval($L04array[$key]['SUGGESTED_DAYSTOSTOCK']);
    $AVG_DAILY_PICK = $L04array[$key]['DAILYPICK'];
    $AVG_DAILY_UNIT = $L04array[$key]['DAILYUNIT'];
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


    $L04Vol -= $var_locvol;
}
$values = implode(',', $data);

$sql = "INSERT IGNORE INTO gillingham.my_npfmvc ($columns) VALUES $values";
$query = $conn1->prepare($sql);
$query->execute();

$data = array();
echo $whssel . ' unused volume is ' . $L04Vol;
