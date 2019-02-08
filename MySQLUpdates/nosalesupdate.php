
<?php

$var_CURTF = NULL;
$JAX_ENDCAP = 0;
$slowdownsizecutoff = 99999;

//This is the remaining L04 volume after all items with sales have been slotted.
echo '<br><br>  starting volume is for no sales is ' . $L04Vol;

$sqlexclude = '';

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

$L04sql = $conn1->prepare("SELECT 
    D.slotmaster_branch AS WAREHOUSE,
    D.slotmaster_item AS ITEM_NUMBER,
    1 AS PACKAGE_UNIT,
    'EA' AS PACKAGE_TYPE,
    D.slotmaster_loc AS LMLOC,
    99999 AS DAYS_FRM_SLE,
    99999 AS AVGD_BTW_SLE,
    99999 AS AVG_INV_OH,
    0 AS NBR_SHIP_OCC,
    0 AS PICK_QTY_MN,
    0 AS PICK_QTY_SD,
    0 AS SHIP_QTY_MN,
    0 AS SHIP_QTY_SD,
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
    D.slotmaster_usecube * 1000 AS LMVOL9,
    D.slotmaster_tier AS LMTIER,
    D.slotmaster_dimgroup AS LMGRD5,
    D.slotmaster_normreplen + D.slotmaster_maxreplen AS CURMAX,
    D.slotmaster_normreplen AS CURMIN,
    CASE
        WHEN X.CPCELEN * X.CPCEHEI * X.CPCEWID > 0 THEN ((CASE
        WHEN A.AVGD_BTW_SLE >= 365 THEN 0
        WHEN A.DAYS_FRM_SLE >= 180 THEN 0
        WHEN A.PICK_QTY_MN > A.SHIP_QTY_MN THEN A.SHIP_QTY_MN / A.AVGD_BTW_SLE
        WHEN
            A.AVGD_BTW_SLE = 0
                AND A.DAYS_FRM_SLE = 0
        THEN
            A.SHIP_QTY_MN
        WHEN A.AVGD_BTW_SLE = 0 THEN (A.SHIP_QTY_MN / A.DAYS_FRM_SLE)
        ELSE (A.SHIP_QTY_MN / A.AVGD_BTW_SLE)
    END) * X.CPCELEN * X.CPCEHEI * X.CPCEWID)
        ELSE (CASE
        WHEN A.AVGD_BTW_SLE >= 365 THEN 0
        WHEN A.DAYS_FRM_SLE >= 180 THEN 0
        WHEN A.PICK_QTY_MN > A.SHIP_QTY_MN THEN A.SHIP_QTY_MN / A.AVGD_BTW_SLE
        WHEN
            A.AVGD_BTW_SLE = 0
                AND A.DAYS_FRM_SLE = 0
        THEN
            A.SHIP_QTY_MN
        WHEN A.AVGD_BTW_SLE = 0 THEN (A.SHIP_QTY_MN / A.DAYS_FRM_SLE)
        ELSE (A.SHIP_QTY_MN / A.AVGD_BTW_SLE)
    END) * X.CPCCLEN * X.CPCCHEI * X.CPCCWID / X.CPCCPKU
    END AS DLY_CUBE_VEL,
    CASE
        WHEN
            X.CPCELEN * X.CPCEHEI * X.CPCEWID > 0
        THEN
            (CASE
                WHEN A.AVGD_BTW_SLE >= 365 THEN 0
                WHEN A.DAYS_FRM_SLE >= 180 THEN 0
                WHEN
                    A.PICK_QTY_MN > A.SHIP_QTY_MN
                THEN
                    (A.SHIP_QTY_MN / (CASE
                        WHEN X.CPCCPKU > 0 THEN X.CPCCPKU
                        ELSE 1
                    END)) / A.AVGD_BTW_SLE
                WHEN A.AVGD_BTW_SLE = 0 AND A.DAYS_FRM_SLE = 0 THEN A.PICK_QTY_MN
                WHEN A.AVGD_BTW_SLE = 0 THEN (A.PICK_QTY_MN / A.DAYS_FRM_SLE)
                ELSE (A.PICK_QTY_MN / A.AVGD_BTW_SLE)
            END) * X.CPCELEN * X.CPCEHEI * X.CPCEWID
        ELSE (CASE
            WHEN A.AVGD_BTW_SLE >= 365 THEN 0
            WHEN A.DAYS_FRM_SLE >= 180 THEN 0
            WHEN
                A.PICK_QTY_MN > A.SHIP_QTY_MN
            THEN
                (A.SHIP_QTY_MN / (CASE
                    WHEN X.CPCCPKU > 0 THEN X.CPCCPKU
                    ELSE 1
                END)) / A.AVGD_BTW_SLE
            WHEN A.AVGD_BTW_SLE = 0 AND A.DAYS_FRM_SLE = 0 THEN A.PICK_QTY_MN
            WHEN A.AVGD_BTW_SLE = 0 THEN (A.PICK_QTY_MN / A.DAYS_FRM_SLE)
            ELSE (A.PICK_QTY_MN / A.AVGD_BTW_SLE)
        END) * X.CPCCLEN * X.CPCCHEI * X.CPCCWID
    END AS DLY_PICK_VEL,
    CASE
        WHEN A.AVGD_BTW_SLE >= 365 THEN 0
        WHEN A.DAYS_FRM_SLE >= 180 THEN 0
        WHEN
            A.PICK_QTY_MN > A.SHIP_QTY_MN
        THEN
            (A.SHIP_QTY_MN / (CASE
                WHEN X.CPCCPKU > 0 THEN X.CPCCPKU
                ELSE 1
            END)) / A.AVGD_BTW_SLE
        WHEN A.AVGD_BTW_SLE = 0 AND A.DAYS_FRM_SLE = 0 THEN A.PICK_QTY_MN
        WHEN A.AVGD_BTW_SLE = 0 THEN (A.PICK_QTY_MN / A.DAYS_FRM_SLE)
        ELSE (A.PICK_QTY_MN / A.AVGD_BTW_SLE)
    END AS DAILYPICK,
    CASE
        WHEN A.AVGD_BTW_SLE >= 365 THEN 0
        WHEN A.DAYS_FRM_SLE >= 180 THEN 0
        WHEN A.PICK_QTY_MN > A.SHIP_QTY_MN THEN A.SHIP_QTY_MN / A.AVGD_BTW_SLE
        WHEN A.AVGD_BTW_SLE = 0 AND A.DAYS_FRM_SLE = 0 THEN A.SHIP_QTY_MN
        WHEN A.AVGD_BTW_SLE = 0 THEN (A.SHIP_QTY_MN / A.DAYS_FRM_SLE)
        ELSE (A.SHIP_QTY_MN / A.AVGD_BTW_SLE)
    END AS DAILYUNIT,
    S.CASETF
FROM
    gillingham.slotmaster D
        LEFT JOIN
    my_npfmvc A ON D.slotmaster_item = A.ITEM_NUMBER
        LEFT JOIN
    gillingham.npfcpcsettings X ON CPCITEM = D.slotmaster_item
        LEFT JOIN
    gillingham.item_settings S ON S.ITEM = D.slotmaster_item
WHERE
    slotmaster_item IS NOT NULL
        AND A.ITEM_NUMBER IS NULL
        ORDER BY LMVOL9 ASC;");
$L04sql->execute();
$L04array_nosales = $L04sql->fetchAll(pdo::FETCH_ASSOC);



foreach ($L04array_nosales as $key => $value) {
    if ($L04Vol < 0) {
        break;  //if all available L04 volume has been used, exit
    }
    $ITEM_NUMBER = intval($L04array_nosales[$key]['ITEM_NUMBER']);


    //Check OK in Shelf Setting
    $var_OKINSHLF = $L04array_nosales[$key]['CPCSHLF'];
    $var_stacklimit = $L04array_nosales[$key]['CPCESTK'];
    $var_casetf = $L04array_nosales[$key]['CASETF'];
//    $var_CURTF = $L04array[$key]['CURTF'];

    $var_AVGSHIPQTY = $L04array_nosales[$key]['SHIP_QTY_MN'];
    $AVGD_BTW_SLE = intval($L04array_nosales[$key]['AVGD_BTW_SLE']);
    if ($AVGD_BTW_SLE == 0) {
        $AVGD_BTW_SLE = 999;
    }

    $var_AVGINV = intval($L04array_nosales[$key]['AVG_INV_OH']);

    $avgdailyshipqty = number_format($var_AVGSHIPQTY / $AVGD_BTW_SLE, 8);
    if ($avgdailyshipqty == 0) {
        $avgdailyshipqty = .000000001;
    }
    $var_PCLIQU = $L04array_nosales[$key]['CPCLIQU'];

    $var_PCEHEIin = $L04array_nosales[$key]['CPCEHEI'];
    if ($var_PCEHEIin == 0) {
        $var_PCEHEIin = $L04array_nosales[$key]['CPCCHEI'];
    }


    if ($var_PCEHEIin == 0) {
        $var_PCEHEIin = 1;
    }

    $var_PCELENin = $L04array_nosales[$key]['CPCELEN'];
    if ($var_PCELENin == 0) {
        $var_PCELENin = $L04array_nosales[$key]['CPCCLEN'];
    }

    if ($var_PCELENin == 0) {
        $var_PCELENin = 1;
    }

    $var_PCEWIDin = $L04array_nosales[$key]['CPCEWID'];
    if ($var_PCEWIDin == 0) {
        $var_PCEWIDin = $L04array_nosales[$key]['CPCCWID'];
    }

    if ($var_PCEWIDin == 0) {
        $var_PCEWIDin = 1;
    }

    $var_PCCHEIin = $L04array_nosales[$key]['CPCCHEI'];
    $var_PCCLENin = $L04array_nosales[$key]['CPCCLEN'];
    $var_PCCWIDin = $L04array_nosales[$key]['CPCCWID'];

    $var_eachqty = $L04array_nosales[$key]['CPCEPKU'];
    $var_caseqty = $L04array_nosales[$key]['CPCCPKU'];
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
        $var_pkgu = intval($L04array_nosales[$key]['PACKAGE_UNIT']);
        $var_pkty = $L04array_nosales[$key]['PACKAGE_TYPE'];
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

    //assign current grid5 since no sales are available
    $lastusedgrid5 = $L04array_nosales[$key]['LMGRD5'];

    //what is actual true fit of current grid5


    $var_locvol = ($L04array_nosales[$key]['LMVOL9']);
    if (intval($var_locvol) > 317120){
        $var_locvol = 317120;
    }
    $SUGGESTED_MAX = intval($L04array_nosales[$key]['CURMAX']);

    //Call the min calc logic
    $SUGGESTED_MIN = intval($L04array_nosales[$key]['CURMIN']);

    //append data to array for writing to my_npfmvc table
    $L04array_nosales[$key]['SUGGESTED_TIER'] = 'L04';
    $L04array_nosales[$key]['SUGGESTED_GRID5'] = $lastusedgrid5;
    $L04array_nosales[$key]['SUGGESTED_DEPTH'] = ($L04array_nosales[$key]['LMDEEP']);
    $L04array_nosales[$key]['SUGGESTED_MAX'] = $SUGGESTED_MAX;
    $L04array_nosales[$key]['SUGGESTED_MIN'] = $SUGGESTED_MIN;
    $L04array_nosales[$key]['SUGGESTED_SLOTQTY'] = $slotqty;
    $L04array_nosales[$key]['SUGGESTED_IMPMOVES'] = _implied_daily_moves_withcurrentTF($L04array_nosales[$key]['CURMAX'], $L04array_nosales[$key]['CURMIN'], $avgdailyshipqty, $var_AVGINV, $L04array_nosales[$key]['SHIP_QTY_MN'], $L04array_nosales[$key]['AVGD_BTW_SLE'], $var_CURTF);  //same as current because no sales history
    $L04array_nosales[$key]['CURRENT_IMPMOVES'] = _implied_daily_moves_withcurrentTF($L04array_nosales[$key]['CURMAX'], $L04array_nosales[$key]['CURMIN'], $avgdailyshipqty, $var_AVGINV, $L04array_nosales[$key]['SHIP_QTY_MN'], $L04array_nosales[$key]['AVGD_BTW_SLE'], $var_CURTF);

    $L04array_nosales[$key]['SUGGESTED_NEWLOCVOL'] = $var_locvol;
    $L04array_nosales[$key]['SUGGESTED_DAYSTOSTOCK'] = intval($daystostock);

    //********** START of SQL to ADD TO TABLE **********


    $WAREHOUSE = intval($L04array_nosales[$key]['WAREHOUSE']);
    $ITEM_NUMBER = intval($L04array_nosales[$key]['ITEM_NUMBER']);
    $PACKAGE_UNIT = intval($L04array_nosales[$key]['PACKAGE_UNIT']);
    $PACKAGE_TYPE = $L04array_nosales[$key]['PACKAGE_TYPE'];
    $CUR_LOCATION = $L04array_nosales[$key]['LMLOC'];
    $DAYS_FRM_SLE = intval($L04array_nosales[$key]['DAYS_FRM_SLE']);
    $AVGD_BTW_SLE = intval($L04array_nosales[$key]['AVGD_BTW_SLE']);
    $AVG_INV_OH = intval($L04array_nosales[$key]['AVG_INV_OH']);
    $NBR_SHIP_OCC = intval($L04array_nosales[$key]['NBR_SHIP_OCC']);
    $PICK_QTY_MN = intval($L04array_nosales[$key]['PICK_QTY_MN']);
    $PICK_QTY_SD = $L04array_nosales[$key]['PICK_QTY_SD'];
    $SHIP_QTY_MN = intval($L04array_nosales[$key]['SHIP_QTY_MN']);
    $SHIP_QTY_SD = $L04array_nosales[$key]['SHIP_QTY_SD'];
    $CPCEPKU = intval($L04array_nosales[$key]['CPCEPKU']);
    $CPCCPKU = intval($L04array_nosales[$key]['CPCCPKU']);
    $CPCFLOW = $L04array_nosales[$key]['CPCFLOW'];
    If (is_null($CPCFLOW)) {
        $CPCFLOW = 'Y';
    }
    $CPCTOTE = $L04array_nosales[$key]['CPCTOTE'];
    If (is_null($CPCTOTE)) {
        $CPCTOTE = 'Y';
    }
    $CPCSHLF = $L04array_nosales[$key]['CPCSHLF'];
    If (is_null($CPCSHLF)) {
        $CPCSHLF = 'Y';
    }
    $CPCROTA = $L04array_nosales[$key]['CPCROTA'];
    If (is_null($CPCROTA)) {
        $CPCROTA = 'Y';
    }
    $CPCESTK = intval($L04array_nosales[$key]['CPCESTK']);
    If (is_null($CPCESTK)) {
        $CPCESTK = intval(0);
    }
    $CPCLIQU = $L04array_nosales[$key]['CPCLIQU'];
    If (is_null($CPCLIQU)) {
        $CPCROTA = ' ';
    }
    $CPCELEN = $L04array_nosales[$key]['CPCELEN'];

    If (is_null($CPCELEN)) {
        $CPCELEN = intval(0);
    }
    $CPCEHEI = $L04array_nosales[$key]['CPCEHEI'];

    If (is_null($CPCEHEI)) {
        $CPCEHEI = intval(0);
    }
    $CPCEWID = $L04array_nosales[$key]['CPCEWID'];

    If (is_null($CPCEWID)) {
        $CPCEWID = intval(0);
    }
    $CPCCLEN = $L04array_nosales[$key]['CPCCLEN'];

    If (is_null($CPCCLEN)) {
        $CPCCLEN = intval(0);
    }
    $CPCCHEI = $L04array_nosales[$key]['CPCCHEI'];

    If (is_null($CPCCHEI)) {
        $CPCCHEI = intval(0);
    }
    $CPCCWID = $L04array_nosales[$key]['CPCCWID'];

    If (is_null($CPCCWID)) {
        $CPCCWID = intval(0);
    }
    $LMHIGH = ($L04array_nosales[$key]['LMHIGH']);
    $LMDEEP = ($L04array_nosales[$key]['LMDEEP']);
    $LMWIDE = ($L04array_nosales[$key]['LMWIDE']);
    $LMVOL9 = ($L04array_nosales[$key]['LMVOL9']) / 1000;
    $LMTIER = $L04array_nosales[$key]['LMTIER'];
    $LMGRD5 = $L04array_nosales[$key]['LMGRD5'];
    $DLY_CUBE_VEL = intval($L04array_nosales[$key]['DLY_CUBE_VEL']);
    $DLY_PICK_VEL = intval($L04array_nosales[$key]['DLY_PICK_VEL']);
    $SUGGESTED_TIER = $L04array_nosales[$key]['SUGGESTED_TIER'];
    $SUGGESTED_GRID5 = $L04array_nosales[$key]['SUGGESTED_GRID5'];
    $SUGGESTED_DEPTH = $L04array_nosales[$key]['SUGGESTED_DEPTH'];
    $SUGGESTED_MAX = intval($L04array_nosales[$key]['SUGGESTED_MAX']);
    $SUGGESTED_MIN = intval($L04array_nosales[$key]['SUGGESTED_MIN']);
    $SUGGESTED_SLOTQTY = intval($L04array_nosales[$key]['SUGGESTED_SLOTQTY']);

    $SUGGESTED_IMPMOVES = ($L04array_nosales[$key]['SUGGESTED_IMPMOVES']);
    $CURRENT_IMPMOVES = ($L04array_nosales[$key]['CURRENT_IMPMOVES']);
    $SUGGESTED_NEWLOCVOL = ($L04array_nosales[$key]['SUGGESTED_NEWLOCVOL']);
    $SUGGESTED_DAYSTOSTOCK = intval($L04array_nosales[$key]['SUGGESTED_DAYSTOSTOCK']);
    $AVG_DAILY_PICK = $L04array_nosales[$key]['DAILYPICK'];
    $AVG_DAILY_UNIT = $L04array_nosales[$key]['DAILYUNIT'];
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
