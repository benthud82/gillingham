
<?php
//*********    The logic never recommends an imperfect grid5????  ********


include_once '../connection/NYServer.php';
$displayarray[$topcostkey]['MOVES_AFTER_IMP_GRID'] = '-';
$displayarray[$topcostkey]['MOVESCORE_AFTER_IMP_GRID'] = '-';
$displayarray[$topcostkey]['WALKSCORE_AFTER_IMP_GRID'] = '-';
$displayarray[$topcostkey]['TOTSCORE_AFTER_IMP_GRID'] = '-';

include 'emptygridsdata.php';

$slotqtycalc = $displayarray[$topcostkey]['SUGGESTED_SLOTQTY'];
foreach ($EMPTYGRID_array as $key3 => $value3) {



    if ($EMPTYGRID_array[$key3]['LMTIER'] !== ($TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_TIER'])) {  //must be the correct tier
        continue;
    }

    $testgrid5 = $EMPTYGRID_array[$key3]['LMGRD5'];  //largest empty grid available
    //Determine if each or case truefit should be used.

    $NEW_LOC_TRUEFIT_array = _truefitgrid2iterations($testgrid5, $EMPTYGRID_array[$key3]['LMHIGH'], $EMPTYGRID_array[$key3]['LMDEEP'], $EMPTYGRID_array[$key3]['LMWIDE'], $PCLIQU, $PCEHEI, $PCELEN, $PCEWID);  //call funcation to calculate TF based of grid5

    $slotcalc = $NEW_LOC_TRUEFIT_array[1] / $slotqtycalc;
    $spcount = count($EMPTYGRID_array) - 1;

    //I think this works need to test.
    //START HERE*************

    
    if (($slotcalc <= 2.5 && $slotcalc >= .75) || $spcount == $key3) {
        $NEW_LOC_TRUEFIT_round2 = $NEW_LOC_TRUEFIT_array[1]; //assign 2-iteration tf to variable
//        $tf_to_newdmdcalc = number_format($NEW_LOC_TRUEFIT_round2 / $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_SLOTQTY'], 2);  //compare calculated TF to desired slotting quantity to determine if need to contiue to next grid5
//        $tf_to_curmaxcalc = number_format($NEW_LOC_TRUEFIT_round2 / $TOP_REPLEN_COST_array[$topcostkey]['CURMAX'], 2);
    } else {
        continue;
    }

    $lookupkey_l3 = $EMPTYGRID_array[$key3]['EMPTYGRID'];  //grid5 keyval to lookup in empty locations
    $IMPERFECT_GRID5_key = array_search($lookupkey_l3, array_column($EMPTYLOC_array, 'KEYVAL'));
    if ($IMPERFECT_GRID5_key <> FALSE) {

        $NEW_LOC = $EMPTYLOC_array[$IMPERFECT_GRID5_key]['LOCATION'];
        $NEW_loc_bay = intval($EMPTYLOC_array[$IMPERFECT_GRID5_key]['WALKBAY']);
        $displayarray[$topcostkey]['IMPERFECT_GRID5_SLOT_LOC'] = $NEW_LOC;
        $NEW_GRD5 = $EMPTYLOC_array[$IMPERFECT_GRID5_key]['LOC_DIM'];
        $displayarray[$topcostkey]['AssgnGrid5'] = $NEW_GRD5; //Add new grid5 to display array

        $Newmin = _minloc($NEW_LOC_TRUEFIT_round2, $TOP_REPLEN_COST_array[$topcostkey]['SHIP_QTY_MN'], $TOP_REPLEN_COST_array[$topcostkey]['CPCCPKU']);
        $impmoves_after_perfloc = _implied_daily_moves_nomin($NEW_LOC_TRUEFIT_round2, $TOP_REPLEN_COST_array[$topcostkey]['AVG_DAILY_UNIT'], $TOP_REPLEN_COST_array[$topcostkey]['AVG_INV_OH']);
        $replen_score_Perf_Loc = _replen_score_from_moves($impmoves_after_perfloc);

        if ($zone == 'CSE') { //calculate LSE or CSE walk cost
            $walk_score_Perf_Loc_array = _walkcost_case($VCFTIR, $VCTTIR, $TOP_REPLEN_COST_array[$topcostkey]['AVG_DAILY_UNIT'], $TOP_REPLEN_COST_array[$topcostkey]['FLOOR']);
            $walk_score_Perf_Loc = 1;
        } else {
            $walk_score_Perf_Loc = _walkscore($NEW_loc_bay, $OPT_OPTBAY, $TOP_REPLEN_COST_array[$topcostkey]['AVG_DAILY_PICK'], -1);
        }



        if (($walk_score_Perf_Loc * $replen_score_Perf_Loc) > ($TOP_REPLEN_COST_array[$topcostkey]['SCORE_TOTALSCORE'] * 1.25)) {  //if there is a 25% increase in total score then continue
            $displayarray[$topcostkey]['MOVES_AFTER_IMP_GRID'] = $impmoves_after_perfloc;
            $displayarray[$topcostkey]['MOVESCORE_AFTER_IMP_GRID'] = $replen_score_Perf_Loc;
            $displayarray[$topcostkey]['WALKSCORE_AFTER_IMP_GRID'] = $walk_score_Perf_Loc;
            $displayarray[$topcostkey]['TOTSCORE_AFTER_IMP_GRID'] = abs($replen_score_Perf_Loc) * abs($walk_score_Perf_Loc);
            unset($EMPTYLOC_array[$IMPERFECT_GRID5_key]);
            $EMPTYLOC_array = array_values($EMPTYLOC_array);
            break; //break out of foreach loop once match is made
        } else {
            $displayarray[$topcostkey]['MOVES_AFTER_IMP_GRID'] = '-';
            $displayarray[$topcostkey]['MOVESCORE_AFTER_IMP_GRID'] = '-';
            $displayarray[$topcostkey]['WALKSCORE_AFTER_IMP_GRID'] = '-';
            $displayarray[$topcostkey]['TOTSCORE_AFTER_IMP_GRID'] = '-';
        }
    }
}

if ($IMPERFECT_GRID5_key === FALSE) {
    $displayarray[$topcostkey]['MOVES_AFTER_IMP_GRID'] = '-';
    $displayarray[$topcostkey]['MOVESCORE_AFTER_IMP_GRID'] = '-';
    $displayarray[$topcostkey]['WALKSCORE_AFTER_IMP_GRID'] = '-';
    $displayarray[$topcostkey]['TOTSCORE_AFTER_IMP_GRID'] = '-';
}
 