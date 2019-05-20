<?php

if (!isset($usedswaplocaionarray)) {
    $usedswaplocaionarray = array();
}

$usedlocationcommalist = "'" . implode("','", $usedswaplocaionarray) . "'";
$NEW_LOC_TRUEFIT_array = array();

if ($zone == 'LSE') {
    $zonefilter = " = 'LSE'";
} else {
    $zonefilter = " <> 'LSE'";
}


$displayarray[$topcostkey]['MOVES_AFTER_LEVEL1_SWAP'] = '-';
$displayarray[$topcostkey]['MOVESCORE_AFTER_LEVEL1_SWAP'] = '-';
$displayarray[$topcostkey]['WALKSCORE_AFTER_LEVEL1_SWAP'] = '-';
$displayarray[$topcostkey]['TOTSCORE_AFTER_LEVEL1_SWAP'] = '-';
//currently limited to only swap items that want to downsize
//this will prevent scenario where you might upsize a swap item to a highly desirable location with a less desirable item
$SLOT_COST_ONELEVEL = $conn1->prepare("SELECT 
                                                                                A.*,
                                                                                B.OPT_PPCCALC,
                                                                                B.OPT_OPTBAY,
                                                                                B.OPT_CURRBAY,
                                                                                B.OPT_CURRDAILYFT,
                                                                                B.OPT_SHLDDAILYFT,
                                                                                B.OPT_ADDTLFTPERPICK,
                                                                                B.OPT_ADDTLFTPERDAY,
                                                                                B.OPT_WALKCOST,
                                                                                C.slotmaster_normreplen + slotmaster_maxreplen AS CURMAX,
                                                                                C.slotmaster_normreplen AS CURMIN,
                                                                                C.slotmaster_normreplen + slotmaster_maxreplen AS VCCTRF,
                                                                                E.SCORE_TOTALSCORE,
                                                                                E.SCORE_REPLENSCORE,
                                                                                E.SCORE_WALKSCORE,
                                                                                E.SCORE_TOTALSCORE_OPT,
                                                                                E.SCORE_REPLENSCORE_OPT,
                                                                                E.SCORE_WALKSCORE_OPT,
                                                                                V.WALKFEET,
                                                                                'Y' AS CPCPFRC,
                                                                                'Y' AS CPCPFRA,
                                                                                (SELECT 
                                                                                        walkfeet_feet
                                                                                    FROM
                                                                                        gillingham.walkfeet_standard
                                                                                    WHERE
                                                                                        walkfeet_bay = B.OPT_OPTBAY) AS SUGG_WALKFEET,
                                                                                openactions_assignedto,
                                                                                openactions_comment
                                                                            FROM
                                                                                gillingham.my_npfmvc A
                                                                                    JOIN
                                                                                gillingham.optimalbay B ON A.ITEM_NUMBER = B.OPT_ITEM
                                                                                    AND OPT_CSLS = PACKAGE_TYPE
                                                                                    JOIN
                                                                                gillingham.slotmaster C ON C.slotmaster_item = A.ITEM_NUMBER
                                                                                    AND slotmaster_pkgu = PACKAGE_TYPE
                                                                                    JOIN
                                                                                gillingham.slottingscore E ON E.SCORE_ITEM = A.ITEM_NUMBER
                                                                                    AND SCORE_ZONE = PACKAGE_TYPE
                                                                                    JOIN
                                                                                gillingham.bay_location L ON L.LOCATION = A.CUR_LOCATION
                                                                                    JOIN
                                                                                gillingham.vectormap V ON V.BAY = L.BAY
                                                                                    LEFT JOIN
                                                                                gillingham.slottingdb_itemactions ON openactions_item = SCORE_ITEM
                                                                            WHERE
                                                                                A.SUGGESTED_NEWLOCVOL / 1000 < A.LMVOL9
                                                                                    AND A.LMGRD5 = '$VCNGD5'
                                                                                    AND WALKFEET = $OPT_OPTWALKFEET
                                                                            ORDER BY E.SCORE_TOTALSCORE DESC , (A.LMVOL9 - A.SUGGESTED_NEWLOCVOL) DESC");
$SLOT_COST_ONELEVEL->execute();
$SLOT_COST_ONELEVEL_array = $SLOT_COST_ONELEVEL->fetchAll(pdo::FETCH_ASSOC);

//Loop through resultset to determine if there is a perfect match to free up needed perfect match for high cost item
foreach ($SLOT_COST_ONELEVEL_array as $key => $value) {
    $LEVEL_ONE_TIER = $SLOT_COST_ONELEVEL_array[$key]['SUGGESTED_TIER'];
    $LEVEL_ONE_GRD5 = $SLOT_COST_ONELEVEL_array[$key]['SUGGESTED_GRID5'];
    $LEVEL_ONE_DEPTH = $SLOT_COST_ONELEVEL_array[$key]['SUGGESTED_DEPTH'];
    $LEVEL_ONE_BAY = $SLOT_COST_ONELEVEL_array[$key]['OPT_OPTBAY'];
    $LEVEL_ONE_PCCHEI = $SLOT_COST_ONELEVEL_array[$key]['CPCCHEI'];
    $LEVEL_ONE_PCCLEN = $SLOT_COST_ONELEVEL_array[$key]['CPCCLEN'];
    $LEVEL_ONE_PCCWID = $SLOT_COST_ONELEVEL_array[$key]['CPCCWID'];
    $LEVEL_ONE_PCCPKU = $SLOT_COST_ONELEVEL_array[$key]['CPCCPKU'];
    $LEVEL_ONE_PCEHEI = $SLOT_COST_ONELEVEL_array[$key]['CPCEHEI'];
    $LEVEL_ONE_PCELEN = $SLOT_COST_ONELEVEL_array[$key]['CPCELEN'];
    $LEVEL_ONE_PCEWID = $SLOT_COST_ONELEVEL_array[$key]['CPCEWID'];
    $LEVEL_ONE_PCEPKU = $SLOT_COST_ONELEVEL_array[$key]['CPCEPKU'];
    $LEVEL_ONE_WALKFEET = $SLOT_COST_ONELEVEL_array[$key]['WALKFEET'];
//    $LEVEL_ONE_NEWGRIDHEI = intval();
//    $LEVEL_ONE_NEWGRIDWDT = intval());
    $LEVEL_ONE_LIQ = $SLOT_COST_ONELEVEL_array[$key]['CPCLIQU'];
    $LEVEL_ONE_AVGSHIP = $SLOT_COST_ONELEVEL_array[$key]['SHIP_QTY_MN'];
    $LEVEL_ONE_SWAP_NEW_LOC_SLOTQTY = $SLOT_COST_ONELEVEL_array[$key]['SUGGESTED_SLOTQTY'];
    $LEVEL_ONE_SWAP_NEW_LOC_SHIPQTY = $SLOT_COST_ONELEVEL_array[$key]['SHIP_QTY_MN'];
    $LEVEL_ONE_SWAP_NEW_LOC_ADBS = $SLOT_COST_ONELEVEL_array[$key]['AVGD_BTW_SLE'];
    $LEVEL_ONE_DAILYUNIT = $SLOT_COST_ONELEVEL_array[$key]['AVG_DAILY_PICK'];
    $LEVEL_ONE_AVGINV = $SLOT_COST_ONELEVEL_array[$key]['AVG_INV_OH'];
    $LEVEL_ONE_CURRBAY = $SLOT_COST_ONELEVEL_array[$key]['OPT_CURRBAY'];

    $PERFGRID_LEVEL_ONE = $LEVEL_ONE_TIER . $LEVEL_ONE_GRD5 . $LEVEL_ONE_WALKFEET;

    //for case slotting, if optimal bay == 999 (PFR) then set match key to 999999 to indicate need to go to PFR
    if ($LEVEL_ONE_BAY == 999) {
        $LEVEL_ONE_match_key = 999999;
    } else {
        $LEVEL_ONE_match_key = array_search($PERFGRID_LEVEL_ONE, array_column($EMPTYLOC_array, 'KEYVAL'));
    }



    if ($LEVEL_ONE_match_key <> FALSE) { //a perfect grid match has been found.  Set as new location
        if ($LEVEL_ONE_match_key == 999999) {  //Move to case pick PFR
            $LEVEL_ONE_SWAP_NEW_LOC = 'CSE_PFR';
            $LEVEL_ONE_SWAP_NEW_GRD5 = 'C_PFR';
            $displayarray[$topcostkey]['AssgnGrid5'] = $LEVEL_ONE_SWAP_NEW_GRD5; //Add new grid5 to display array
        } else {
            $LEVEL_ONE_SWAP_NEW_LOC = $EMPTYLOC_array[$LEVEL_ONE_match_key]['LOCATION'];
            $LEVEL_ONE_SWAP_NEW_GRD5 = $EMPTYLOC_array[$LEVEL_ONE_match_key]['LOC_DIM'];
            $displayarray[$topcostkey]['AssgnGrid5'] = $LEVEL_ONE_SWAP_NEW_GRD5; //Add new grid5 to display array
        }

        unset($EMPTYLOC_array[$LEVEL_ONE_match_key]);
        $EMPTYLOC_array = array_values($EMPTYLOC_array);
        $displayarray[$topcostkey]['LEVEL_1_NEW_LOC'] = $LEVEL_ONE_SWAP_NEW_LOC; //add new location for level 1 item
        //Can now take newly emptied location with high cost item
        $NEW_LOC = $SLOT_COST_ONELEVEL_array[$key]['CUR_LOCATION'];
        $NEW_GRD5 = $SLOT_COST_ONELEVEL_array[$key]['LMGRD5'];
        $NEW_GRD_HGT = $SLOT_COST_ONELEVEL_array[$key]['LMHIGH'];
        $NEW_GRD_DPT = $SLOT_COST_ONELEVEL_array[$key]['LMDEEP'];
        $NEW_GRD_WDT =$SLOT_COST_ONELEVEL_array[$key]['LMWIDE'];
        $NEW_loc_bay =$SLOT_COST_ONELEVEL_array[$key]['OPT_CURRBAY'];

        $usedswaplocaionarray[] = $NEW_LOC;


        $NEW_LOC_TRUEFIT_array = _truefitgrid2iterations($NEW_GRD5, $NEW_GRD_HGT, $NEW_GRD_DPT, $NEW_GRD_WDT, $PCLIQU, $PCEHEI, $PCELEN, $PCEWID);

        if (count($NEW_LOC_TRUEFIT_array) > 0) {
            $NEW_LOC_TRUEFIT_round2 = $NEW_LOC_TRUEFIT_array[1]; //assign 2-iteration tf to variable
            $Newmin = _minloc($NEW_LOC_TRUEFIT_round2, $TOP_REPLEN_COST_array[$topcostkey]['SHIP_QTY_MN'], $PCCPKU);

            $impmoves_after_level1swap = _implied_daily_moves($NEW_LOC_TRUEFIT_round2, $Newmin, $TOP_REPLEN_COST_array[$topcostkey]['AVG_DAILY_UNIT'], $TOP_REPLEN_COST_array[$topcostkey]['AVG_INV_OH'], $TOP_REPLEN_COST_array[$topcostkey]['SHIP_QTY_MN'], $TOP_REPLEN_COST_array[$topcostkey]['AVGD_BTW_SLE']);
            $replen_score_level1swap = _replen_score_from_moves($impmoves_after_level1swap);


            if ($zone == 'CSE') { //calculate LSE or CSE walk cost
                $walk_score_level1swap_array = _walkcost_case($VCTTIR, $VCTTIR, $TOP_REPLEN_COST_array[$topcostkey]['AVG_DAILY_UNIT'], $TOP_REPLEN_COST_array[$topcostkey]['FLOOR']);
                $walk_score_level1swap = $walk_score_level1swap_array['WALK_SCORE'];
            } else {
                $walk_score_level1swap = _walkscore($NEW_loc_bay, $OPT_OPTBAY, $TOP_REPLEN_COST_array[$topcostkey]['AVG_DAILY_PICK'], -1);
            }




            $displayarray[$topcostkey]['MOVES_AFTER_LEVEL1_SWAP'] = $impmoves_after_level1swap;
            $displayarray[$topcostkey]['MOVESCORE_AFTER_LEVEL1_SWAP'] = abs($replen_score_level1swap);
            $displayarray[$topcostkey]['WALKSCORE_AFTER_LEVEL1_SWAP'] = abs($walk_score_level1swap);
            $displayarray[$topcostkey]['TOTSCORE_AFTER_LEVEL1_SWAP'] = abs($replen_score_level1swap) * abs($walk_score_level1swap);
        } else {
            $displayarray[$topcostkey]['MOVES_AFTER_LEVEL1_SWAP'] = '-';
            $displayarray[$topcostkey]['MOVESCORE_AFTER_LEVEL1_SWAP'] = '-';
            $displayarray[$topcostkey]['WALKSCORE_AFTER_LEVEL1_SWAP'] = '-';
            $displayarray[$topcostkey]['TOTSCORE_AFTER_LEVEL1_SWAP'] = '-';
        }



        $displayarray[$topcostkey]['LEVEL_1_OLD_LOC'] = $NEW_LOC;
        $displayarray[$topcostkey]['LEVEL_1_ITEM'] = $SLOT_COST_ONELEVEL_array[$key]['ITEM_NUMBER'];
//        $displayarray[$topcostkey]['AFTER_LEVEL1_SWAP_DAILY_MOVES'] = $replen_cost_return_array_LEVEL_ONE_SWAP['IMP_MOVES_DAILY'];
        break;
    }
}

if (count($SLOT_COST_ONELEVEL_array) <= 0) {
    $displayarray[$topcostkey]['AssgnGrid5'] = '-';
    $displayarray[$topcostkey]['MOVES_AFTER_LEVEL1_SWAP'] = '-';
    $displayarray[$topcostkey]['MOVESCORE_AFTER_LEVEL1_SWAP'] = '-';
    $displayarray[$topcostkey]['WALKSCORE_AFTER_LEVEL1_SWAP'] = '-';
    $displayarray[$topcostkey]['TOTSCORE_AFTER_LEVEL1_SWAP'] = '-';
}