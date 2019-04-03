<?php

class Cls {

    function arraymapfunct($entry) {
        return $entry['EMPTYGRID'];
    }

}

function searchForExcl($id, $array) {
    foreach ($array as $key => $val) {
        if ($val['ExclKey'] === $id) {
            return TRUE;
        }
    }
    return null;
}

//function _searchForKey($id, $array, $searchkey) {
//    foreach ($array as $key => $val) {
//        if ($val[$searchkey] === $id) {
//            return $key;
//        }
//    }
//    return null;
//}

function searchForMoves($id, $array) {
    foreach ($array as $key => $val) {
        if ($val['KEYValue'] === $id) {
            return $key;
        }
    }
    return null;
}

function _findemptyloc($lockey, $array) {
    foreach ($array as $key => $val) {
        if ($val['KEYFIELD'] === $lockey) {
            return $key;
        }
    }
    return null;
}

function _findpickcount($lockey, $array) {
    foreach ($array as $key => $val) {
        if ($val['PICKKEY'] === $lockey) {
            return $key;
        }
    }
    return null;
}

function _exclusion($id, $array) {
    foreach ($array as $key => $val) {
        if ($val['KEYVALUE'] == $id) {
            return $key;
        }
    }
    return null;
}

function sortdesc($a, $b) {
    return $b[18] - $a[18];
}

function sortdesc17($a, $b) {
    return $b[17] - $a[17];
}

function sortdescVCCVEL($a, $b) {
    return $b['VCCVEL'] - $a['VCCVEL'];
}

function sortdesc21($a, $b) {
    return $b[21] - $a[21];
}

function sortdescPPI($a, $b) {
    return $b['PPI'] - $a['PPI'];
}

function sortasc($a, $b) {
    return $a[18] - $b[18];
}

function sortascLMVOL9($a, $b) {
    return $a['LMVOL9'] - $b['LMVOL9'];
}

function sortasc19($a, $b) {
    return $a[19] - $b[19];
}

function sortasc17($a, $b) {
    return $a[17] - $b[17];
}

function _perentconvert($input) {
    $input = number_format(($input * 100), $decimals = 2);
    return $input . '%';
}

function linear_regression($x, $y) {

// calculate number points
    $n = count($x);

// ensure both arrays of points are the same size
    if ($n != count($y)) {

        trigger_error("linear_regression(): Number of elements in coordinate arrays do not match.", E_USER_ERROR);
    }

// calculate sums
    $x_sum = array_sum($x);
    $y_sum = array_sum($y);

    $xx_sum = 0;
    $xy_sum = 0;

    for ($i = 0; $i < $n; $i++) {

        $xy_sum += ($x[$i] * $y[$i]);
        $xx_sum += ($x[$i] * $x[$i]);
    }

// calculate slope
    $m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));

// calculate intercept
    $b = ($y_sum - ($m * $x_sum)) / $n;

// return result
    return array("m" => $m, "b" => $b);
}

function _caseoppsidsearch($id, $array) {
    foreach ($array as $key => $val) {
        if ($val[0] === $id) {
            return $key;
        }
    }
    return null;
}

function _standarderror($std, $ncount) {
    $standerr = number_format($std / sqrt($ncount), 2);
    return $standerr;
}

function _tscore($samplemean, $stderr, $ohqty) {

    $ci = floatval(($ohqty - $samplemean) / $stderr);
    return $ci;
}

function _searchForLoc($id, $array) {
    foreach ($array as $key => $val) {
        if ($val['KEYVALUE'] === $id) {
            return $key;
        }
    }
    return null;
}

function _mcstandardize($MCCLASS) {

    switch ($MCCLASS) {
        case 'A':
            $MCCLASS = 'A';
            break;
        case 'B':
            $MCCLASS = 'A';
            break;
        case 'C':
            $MCCLASS = 'A';
            break;
        case 'E':
            $MCCLASS = 'B';
            break;
        case 'F':
            $MCCLASS = 'B';
            break;
        case 'G':
            $MCCLASS = 'B';
            break;
        case 'H':
            $MCCLASS = 'B';
            break;
        case 'I':
            $MCCLASS = 'C';
            break;
        case 'J':
            $MCCLASS = 'C';
            break;
        case 'K':
            $MCCLASS = 'C';
            break;
        case 'L':
            $MCCLASS = 'D';
            break;
        case 'M':
            $MCCLASS = 'D';
            break;

        default:
            break;
    }
    return $MCCLASS;
}

function _asomovescore($rolling90ASO) {
    if ($rolling90ASO <= 1) {
        $ASOMOVESCORE = 1;
    } elseif ($rolling90ASO <= 2) {
        $ASOMOVESCORE = .9;
    } elseif ($rolling90ASO <= 3) {
        $ASOMOVESCORE = .8;
    } elseif ($rolling90ASO <= 4) {
        $ASOMOVESCORE = .7;
    } elseif ($rolling90ASO <= 5) {
        $ASOMOVESCORE = .6;
    } elseif ($rolling90ASO <= 6) {
        $ASOMOVESCORE = .5;
    } elseif ($rolling90ASO <= 8) {
        $ASOMOVESCORE = .4;
    } elseif ($rolling90ASO <= 10) {
        $ASOMOVESCORE = .3;
    } elseif ($rolling90ASO <= 15) {
        $ASOMOVESCORE = .2;
    } elseif ($rolling90ASO <= 20) {
        $ASOMOVESCORE = .1;
    } else {
        $ASOMOVESCORE = 0;
    }
    return $ASOMOVESCORE;
}

function _automovescore($rolling90AUTO) {
    if ($rolling90AUTO <= 4) {
        $AUTOMOVESCORE = 1;
    } elseif ($rolling90AUTO <= 5) {
        $AUTOMOVESCORE = .9;
    } elseif ($rolling90AUTO <= 6) {
        $AUTOMOVESCORE = .8;
    } elseif ($rolling90AUTO <= 8) {
        $AUTOMOVESCORE = .7;
    } elseif ($rolling90AUTO <= 10) {
        $AUTOMOVESCORE = .6;
    } elseif ($rolling90AUTO <= 12) {
        $AUTOMOVESCORE = .5;
    } elseif ($rolling90AUTO <= 14) {
        $AUTOMOVESCORE = .4;
    } elseif ($rolling90AUTO <= 18) {
        $AUTOMOVESCORE = .3;
    } elseif ($rolling90AUTO <= 25) {
        $AUTOMOVESCORE = .2;
    } elseif ($rolling90AUTO <= 30) {
        $AUTOMOVESCORE = .1;
    } else {
        $AUTOMOVESCORE = 0;
    }
    return $AUTOMOVESCORE;
}

function _maxoowscore($MAXOOW) {
    if ($MAXOOW == 0) {
        $MAXOOWSCORE = 1;
    } elseif ($MAXOOW <= .1) {
        $MAXOOWSCORE = .9;
    } elseif ($MAXOOW <= .2) {
        $MAXOOWSCORE = .8;
    } elseif ($MAXOOW <= .3) {
        $MAXOOWSCORE = .7;
    } elseif ($MAXOOW <= .5) {
        $MAXOOWSCORE = .6;
    } elseif ($MAXOOW <= .75) {
        $MAXOOWSCORE = .5;
    } elseif ($MAXOOW <= 1) {
        $MAXOOWSCORE = .4;
    } elseif ($MAXOOW <= 1.5) {
        $MAXOOWSCORE = .3;
    } elseif ($MAXOOW <= 2) {
        $MAXOOWSCORE = .2;
    } elseif ($MAXOOW <= 3) {
        $MAXOOWSCORE = .1;
    } else {
        $MAXOOWSCORE = 0;
    }

    return $MAXOOWSCORE;
}

function _minoowscore($MINOOW) {
    if ($MINOOW == 0) {
        $MINOOWSCORE = 1;
    } elseif ($MINOOW <= .1) {
        $MINOOWSCORE = .9;
    } elseif ($MINOOW <= .2) {
        $MINOOWSCORE = .8;
    } elseif ($MINOOW <= .3) {
        $MINOOWSCORE = .7;
    } elseif ($MINOOW <= .5) {
        $MINOOWSCORE = .6;
    } elseif ($MINOOW <= .75) {
        $MINOOWSCORE = .5;
    } elseif ($MINOOW <= 1) {
        $MINOOWSCORE = .4;
    } elseif ($MINOOW <= 1.5) {
        $MINOOWSCORE = .3;
    } elseif ($MINOOW <= 2) {
        $MINOOWSCORE = .2;
    } elseif ($MINOOW <= 3) {
        $MINOOWSCORE = .1;
    } else {
        $MINOOWSCORE = 0;
    }

    return $MINOOWSCORE;
}

function _correcgridscore($CORRECTGRID) {
    if ($CORRECTGRID == 'Y') {
        $CORRECTGRIDSCORE = 1;
    } else {
        $CORRECTGRIDSCORE = 0;
    }
    return $CORRECTGRIDSCORE;
}

function _class_plus_minus($MCCLASS) {

    switch ($MCCLASS) {
        case 'A':
            $MCCLASS = array('B', 'C');
            break;
        case 'B':
            $MCCLASS = array('C', 'A', 'E');
            break;
        case 'C':
            $MCCLASS = array('B', 'E', 'A', 'F');
            break;
        case 'E':
            $MCCLASS = array('C', 'F', 'B', 'G');
            break;
        case 'F':
            $MCCLASS = array('E', 'G', 'C', 'H');
            break;
        case 'G':
            $MCCLASS = array('F', 'H', 'E', 'I');
            break;
        case 'H':
            $MCCLASS = array('G', 'I', 'F', 'J');
            break;
        case 'I':
            $MCCLASS = array('H', 'J', 'G');
            break;
        case 'J':
            $MCCLASS = array('H', 'I');
            break;
        case 'K':
            $MCCLASS = array('L', 'M');
            break;
        case 'L':
            $MCCLASS = array('K', 'M');
            break;
        case 'M':
            $MCCLASS = array('K', 'L');
            break;

        default:
            break;
    }
    return $MCCLASS;
}

function _AcceptBayFunction($startbay) {
    switch ($startbay) {
        case 0:
            $BAY_array = array(0, 1);

            break;
        case 1:
            $BAY_array = array($startbay, 0, 2, 3);
            break;
        case 2:
            $BAY_array = array($startbay, 1, 3, 4, 5,);
            break;
        case 3:
            $BAY_array = array($startbay, 2, 4, 1, 5, 6, 7, 8);
            break;
        default:
            $BAY_array = array($startbay, $startbay - 1, $startbay + 1, $startbay - 2, $startbay + 2, $startbay - 3, $startbay + 3);
            break;
    }
    return $BAY_array;
}

function _class_plus_minus_ALL($MCCLASS) {

    switch ($MCCLASS) {
        case 'A':
            $MCCLASS = array('B', 'C', 'E', 'F', 'G', 'H', 'I', 'J');
            break;
        case 'B':
            $MCCLASS = array('C', 'A', 'E', 'F', 'G', 'H', 'I', 'J');
            break;
        case 'C':
            $MCCLASS = array('B', 'E', 'A', 'F', 'G', 'H', 'I', 'J');
            break;
        case 'E':
            $MCCLASS = array('C', 'F', 'B', 'G', 'A', 'H', 'I', 'J');
            break;
        case 'F':
            $MCCLASS = array('E', 'G', 'C', 'H', 'B', 'H', 'A', 'J');
            break;
        case 'G':
            $MCCLASS = array('F', 'H', 'E', 'I', 'C', 'B', 'J', 'A');
            break;
        case 'H':
            $MCCLASS = array('F', 'G', 'E', 'I', 'C', 'B', 'J', 'A');
            break;
        case 'I':
            $MCCLASS = array('F', 'H', 'E', 'G', 'C', 'B', 'J', 'A');
            break;
        case 'J':
            $MCCLASS = array('F', 'H', 'E', 'I', 'C', 'B', 'G', 'A');
            break;
        case 'K':
            $MCCLASS = array('L', 'M');
            break;
        case 'L':
            $MCCLASS = array('K', 'M');
            break;
        case 'M':
            $MCCLASS = array('K', 'L');
            break;

        default:
            break;
    }
    return $MCCLASS;
}

function _slotting_replen_cost($LOMAXC, $VCCTRF, $LOMINC, $SHIP_QTY_MN, $AVGD_BTW_SLE) {
    $replen_cost_return_array = array();


    $DAYS_TO_STOCK = 10;  //Should this be changed for "C" movers to 1-2 ship occurences?
//    $DAMPEN_PERCENT = 1;
    $MOVES_PER_HOUR = 10;
    $HOURLY_RATE = 19;
    $DAYS_IN_YEAR = 253;


    if ($LOMAXC > $VCCTRF) {  //determine whether to use true fit or current max to determine capacity of location
        $DAYS_OH_DENOM = $LOMAXC;
    } else {
//        $DAYS_OH_DENOM = $VCCTRF;  //since moves currently go to max, do not want to look at true fit.
        $DAYS_OH_DENOM = $LOMAXC;
    }

//    CAST(CASE WHEN (SLOT_QTY + SHIP_QTY_MN) >= max( THEN
//    (VCCTRF / SHIP_QTY_MN) + (VCADBS - 1) ELSE
//    ((VCCTRF - SLOT_QTY)/SHIP_QTY_MN) * VCADBS
//    END as Dec(10, 2)) as LOC_DMD_DAYS
//calculate days demand the location will hold

    if (($SHIP_QTY_MN) >= $DAYS_OH_DENOM || ($DAYS_OH_DENOM - $LOMINC) <= $SHIP_QTY_MN) {
        $LOC_DMD_DAYS = $AVGD_BTW_SLE;
        $replen_cost_return_array['LOC_DMD_DAYS'] = number_format($LOC_DMD_DAYS, 2);
    } else {
        $LOC_DMD_DAYS = (($DAYS_OH_DENOM - $LOMINC) / $SHIP_QTY_MN) * $AVGD_BTW_SLE;
        $replen_cost_return_array['LOC_DMD_DAYS'] = number_format($LOC_DMD_DAYS, 2);
    }
//Orginal Calc:
//    if (($SLOT_QTY + $SHIP_QTY_MN) >= $DAYS_OH_DENOM) {
//        $LOC_DMD_DAYS = ($DAYS_OH_DENOM / $SHIP_QTY_MN) + ($AVGD_BTW_SLE - 1);
//        $replen_cost_return_array['LOC_DMD_DAYS'] = number_format($LOC_DMD_DAYS, 2);
//    } else {
//        $LOC_DMD_DAYS = (($DAYS_OH_DENOM - $SHIP_QTY_MN) / $SHIP_QTY_MN) * $AVGD_BTW_SLE;
//        $replen_cost_return_array['LOC_DMD_DAYS'] = number_format($LOC_DMD_DAYS, 2);
//    }
//calculate daily implied moves
    if ($SHIP_QTY_MN >= $DAYS_OH_DENOM) {  //if slot quantity (safety stock) is greater than current true fit, you will theortically do a move every day you ship to replinish safety stock as well as any move to satisfy demand
        if ($LOC_DMD_DAYS == 0) {
            $IMP_MOVES_DAILY = 0;
            $replen_cost_return_array['IMP_MOVES_DAILY'] = number_format(0);
            $replen_cost_return_array['IMP_MOVES_MONTHLY'] = number_format(0);
        } else {
            $IMP_MOVES_DAILY = (1 / $LOC_DMD_DAYS) + (.5 / $AVGD_BTW_SLE);  //half the time you will have to do a replishment move as well as an ASO
            $replen_cost_return_array['IMP_MOVES_DAILY'] = number_format($IMP_MOVES_DAILY, 5);
            $replen_cost_return_array['IMP_MOVES_MONTHLY'] = intval((number_format($IMP_MOVES_DAILY, 5) * 253) / 12);
        }
    } else {
        if ($LOC_DMD_DAYS == 0) {
            $IMP_MOVES_DAILY = 0;
            $replen_cost_return_array['IMP_MOVES_DAILY'] = number_format(0, 5);
            $replen_cost_return_array['IMP_MOVES_MONTHLY'] = number_format(0);
        } else {
            $IMP_MOVES_DAILY = (1 / $LOC_DMD_DAYS);
            $replen_cost_return_array['IMP_MOVES_DAILY'] = number_format($IMP_MOVES_DAILY, 5);
            $replen_cost_return_array['IMP_MOVES_MONTHLY'] = intval((number_format($IMP_MOVES_DAILY, 5) * 253) / 12);
        }
    }


    $ACCEPTABLE_MOVES_DAILY = number_format(1 / ($DAYS_TO_STOCK * $AVGD_BTW_SLE), 5);
    $replen_cost_return_array['ACCEPTABLE_MOVES_DAILY'] = $ACCEPTABLE_MOVES_DAILY;
    $ADDITIONAL_DAILY_MOVES = number_format($IMP_MOVES_DAILY - $ACCEPTABLE_MOVES_DAILY, 5);
    $replen_cost_return_array['ADDITIONAL_DAILY_MOVES'] = $ADDITIONAL_DAILY_MOVES;
//    $replen_cost_return_array['ADDITIONAL_DAILY_MOVES_DAMPENED'] = number_format($ADDITIONAL_DAILY_MOVES * $DAMPEN_PERCENT, 2);
    $replen_cost_return_array['YEARLY_REPLEN_COST'] = number_format(($HOURLY_RATE / $MOVES_PER_HOUR) * $ADDITIONAL_DAILY_MOVES * $DAYS_IN_YEAR, 2);

    return $replen_cost_return_array;
}

function _maxtoTFtest($LOMAXC, $VCCTRF, $VCNDMD) {
    if ($VCCTRF > $LOMAXC && $VCNDMD > $LOMAXC) {
        $diff = min($VCCTRF, $VCNDMD) - $LOMAXC;  //take difference to deterimine upsize qty
    } else {
        $diff = 0;
    }
    return $diff;
}

function _reslotrecommendation($CurrCostTotal, $CurrCostWalk, $CurrCostReplen, $MaxAdjCost, $PerfSlotCostTotal, $PerfSlotCostWalk, $PerfSlotCostReplen, $Level1CostTotal, $Level1CostWalk, $Level1CostReplen, $ImpMCCostTotal, $ImpMCCostWalk, $ImpMCCostReplen, $settingscheck, $ImpGRID5ScoreTotal, $ImpGRID5ScoreWalk, $ImpGRID5ScoreReplen) {

//scenario number descriptions
// 0 - Nothing can be done
// 1 - Adjust Max
// 2 - Perfect Slot
// 3 - 1-Level Slot
// 4 - Imperfect MC
// 5 - Imperfect GRID5

    $finalrecommendation = array();

//settings check is set
    if ($settingscheck == 999) {
        $finalrecommendation['TEXT'] = 'Verify Slotting Settings';
        $finalrecommendation['CostSavingsTotal'] = 'N/A';  //Assume perfect slotting cost can be obtained since it is in the correct grid5
        $finalrecommendation['CostSavingsWalk'] = 'N/A';
        $finalrecommendation['CostSavingsReplen'] = 'N/A';
        $finalrecommendation['Scenario'] = 999;
        return $finalrecommendation;
    }

//case when all adjusted costs are blank
    if ($MaxAdjCost == '-' && $PerfSlotCostTotal == '-' && $Level1CostTotal == '-' && $ImpMCCostTotal == '-' && $ImpGRID5ScoreTotal == '-') {
        $finalrecommendation['TEXT'] = 'Nothing can be done';
        $finalrecommendation['CostSavingsTotal'] = 0;
        $finalrecommendation['CostSavingsWalk'] = 0;
        $finalrecommendation['CostSavingsReplen'] = 0;
        $finalrecommendation['Scenario'] = 0;
        return $finalrecommendation;
    }

//case when perfect slot is available and cannot adjust max
    if ($PerfSlotCostTotal <> '-' && $MaxAdjCost == '-') {
        $finalrecommendation['TEXT'] = 'Perfect Slot Found';
        $finalrecommendation['CostSavingsTotal'] = $PerfSlotCostTotal - $CurrCostTotal;
        $finalrecommendation['CostSavingsWalk'] = $PerfSlotCostWalk - $CurrCostWalk;
        $finalrecommendation['CostSavingsReplen'] = $PerfSlotCostReplen - $CurrCostReplen;
        $finalrecommendation['Scenario'] = 2;
        return $finalrecommendation;
    }

//case when Level 1 swap slot is available and cannot adjust max
    if ($Level1CostTotal <> '-' && $MaxAdjCost == '-') {
        $finalrecommendation['TEXT'] = 'Level 1 Swap Found';
        $finalrecommendation['CostSavingsTotal'] = $Level1CostTotal - $CurrCostTotal;
        $finalrecommendation['CostSavingsWalk'] = $Level1CostWalk - $CurrCostWalk;
        $finalrecommendation['CostSavingsReplen'] = $Level1CostReplen - $CurrCostReplen;
        $finalrecommendation['Scenario'] = 3;
        return $finalrecommendation;
    }

//case when Imp_MC slot is available and cannot adjust max
    if ($ImpMCCostTotal <> '-' && $MaxAdjCost == '-') {
        $finalrecommendation['TEXT'] = 'Imperfect Bay Found';
        $finalrecommendation['CostSavingsTotal'] = $ImpMCCostTotal - $CurrCostTotal;
        $finalrecommendation['CostSavingsWalk'] = $ImpMCCostWalk - $CurrCostWalk;
        $finalrecommendation['CostSavingsReplen'] = $ImpMCCostReplen - $CurrCostReplen;
        $finalrecommendation['Scenario'] = 4;
        return $finalrecommendation;
    }

//case when Imp_GRID5 slot is available and cannot adjust max
    if ($ImpGRID5ScoreTotal <> '-' && $MaxAdjCost == '-') {
        $finalrecommendation['TEXT'] = 'Imperfect Grid5 Found';
        $finalrecommendation['CostSavingsTotal'] = $ImpGRID5ScoreTotal - $CurrCostTotal;
        $finalrecommendation['CostSavingsWalk'] = $ImpGRID5ScoreWalk - $CurrCostWalk;
        $finalrecommendation['CostSavingsReplen'] = $ImpGRID5ScoreReplen - $CurrCostReplen;
        $finalrecommendation['Scenario'] = 5;
        return $finalrecommendation;
    }

//case when max adj cost is within 10% of Perf slot cost, use max adjust cost
    if ($PerfSlotCostTotal <> '-' && $MaxAdjCost <> '-') {
        if ($PerfSlotCostTotal >= .99) {
            $finalrecommendation['TEXT'] = 'Perfect Slot Found';
            $finalrecommendation['CostSavingsTotal'] = $PerfSlotCostTotal - $CurrCostTotal;
            $finalrecommendation['CostSavingsWalk'] = $PerfSlotCostWalk - $CurrCostWalk;
            $finalrecommendation['CostSavingsReplen'] = $PerfSlotCostReplen - $CurrCostReplen;
            $finalrecommendation['Scenario'] = 2;
            return $finalrecommendation;
        } elseif ($MaxAdjCost > $PerfSlotCostReplen && ($PerfSlotCostReplen / $MaxAdjCost) >= .9) {  //change to ">" for max adjust cost on 1/5/17
            $finalrecommendation['TEXT'] = 'Adjust loc max';
            $finalrecommendation['CostSavingsTotal'] = $MaxAdjCost - $CurrCostReplen;
            $finalrecommendation['CostSavingsWalk'] = 0;
            $finalrecommendation['CostSavingsReplen'] = $MaxAdjCost - $CurrCostReplen;
            $finalrecommendation['Scenario'] = 1;
            return $finalrecommendation;
        } else {
            $finalrecommendation['TEXT'] = 'Perfect Slot Found';
            $finalrecommendation['CostSavingsTotal'] = $PerfSlotCostTotal - $CurrCostTotal;
            $finalrecommendation['CostSavingsWalk'] = $PerfSlotCostWalk - $CurrCostWalk;
            $finalrecommendation['CostSavingsReplen'] = $PerfSlotCostReplen - $CurrCostReplen;
            $finalrecommendation['Scenario'] = 2;
            return $finalrecommendation;
        }
    }

//case when max adj cost is within 10% of Level1 slot cost, use max adjust cost
    if ($Level1CostTotal <> '-' && $MaxAdjCost <> '-') {
        if ($Level1CostTotal >= .99) {
            $finalrecommendation['TEXT'] = 'Level 1 Swap Found';
            $finalrecommendation['CostSavingsTotal'] = $Level1CostTotal - $CurrCostTotal;
            $finalrecommendation['CostSavingsWalk'] = $Level1CostWalk - $CurrCostWalk;
            $finalrecommendation['CostSavingsReplen'] = $Level1CostReplen - $CurrCostReplen;
            $finalrecommendation['Scenario'] = 3;
            return $finalrecommendation;
        } elseif ($MaxAdjCost > $Level1CostTotal && ($MaxAdjCost / $Level1CostReplen) >= .9) {  //change to ">" for max adjust cost on 1/5/17
            $finalrecommendation['TEXT'] = 'Adjust loc max';
            $finalrecommendation['CostSavingsTotal'] = $MaxAdjCost - $CurrCostReplen;
            $finalrecommendation['CostSavingsWalk'] = 0;
            $finalrecommendation['CostSavingsReplen'] = $MaxAdjCost - $CurrCostReplen;
            $finalrecommendation['Scenario'] = 1;
            return $finalrecommendation;
        } else {
            $finalrecommendation['TEXT'] = 'Level 1 Swap Found';
            $finalrecommendation['CostSavingsTotal'] = $Level1CostTotal - $CurrCostTotal;
            $finalrecommendation['CostSavingsWalk'] = $Level1CostWalk - $CurrCostWalk;
            $finalrecommendation['CostSavingsReplen'] = $Level1CostReplen - $CurrCostReplen;
            $finalrecommendation['Scenario'] = 3;
            return $finalrecommendation;
        }
    }


//case when max adj cost is within 10% of Imp_MC slot cost, use max adjust cost
    if ($ImpMCCostTotal <> '-' && $MaxAdjCost <> '-') {
        if ($ImpMCCostTotal >= .99) {
            $finalrecommendation['TEXT'] = 'Imperfect Bay Found';
            $finalrecommendation['CostSavingsTotal'] = $ImpMCCostTotal - $CurrCostTotal;
            $finalrecommendation['CostSavingsWalk'] = $ImpMCCostWalk - $CurrCostWalk;
            $finalrecommendation['CostSavingsReplen'] = $ImpMCCostReplen - $CurrCostReplen;
            $finalrecommendation['Scenario'] = 4;
            return $finalrecommendation;
        } elseif ($MaxAdjCost > $ImpMCCostReplen && ($MaxAdjCost / $ImpMCCostReplen) >= .9) {  //change to ">" for max adjust cost on 1/5/17
            $finalrecommendation['TEXT'] = 'Adjust loc max';
            $finalrecommendation['CostSavingsTotal'] = $MaxAdjCost - $CurrCostReplen;
            $finalrecommendation['CostSavingsWalk'] = 0;
            $finalrecommendation['CostSavingsReplen'] = $MaxAdjCost - $CurrCostReplen;
            $finalrecommendation['Scenario'] = 1;
            return $finalrecommendation;
        } else {
            $finalrecommendation['TEXT'] = 'Imperfect Bay Found';
            $finalrecommendation['CostSavingsTotal'] = $ImpMCCostTotal - $CurrCostTotal;
            $finalrecommendation['CostSavingsWalk'] = $ImpMCCostWalk - $CurrCostWalk;
            $finalrecommendation['CostSavingsReplen'] = $ImpMCCostReplen - $CurrCostReplen;
            $finalrecommendation['Scenario'] = 4;
            return $finalrecommendation;
        }
    }

//case when max adj cost is within 10% of Imp_GRID5 slot cost, use max adjust cost
    if ($ImpGRID5ScoreTotal <> '-' && $MaxAdjCost <> '-') {
        if ($ImpGRID5ScoreTotal >= .99) {
            $finalrecommendation['TEXT'] = 'Imperfect Grid5 Found';
            $finalrecommendation['CostSavingsTotal'] = $ImpGRID5ScoreTotal - $CurrCostTotal;
            $finalrecommendation['CostSavingsWalk'] = $ImpGRID5ScoreWalk - $CurrCostWalk;
            $finalrecommendation['CostSavingsReplen'] = $ImpGRID5ScoreReplen - $CurrCostReplen;
            $finalrecommendation['Scenario'] = 5;
            return $finalrecommendation;
        } elseif ($MaxAdjCost > $ImpGRID5ScoreReplen && ($MaxAdjCost / $ImpGRID5ScoreReplen) >= .9) {  //change to ">" for max adjust cost on 1/5/17
            $finalrecommendation['TEXT'] = 'Adjust loc max';
            $finalrecommendation['CostSavingsTotal'] = $MaxAdjCost - $CurrCostReplen;
            $finalrecommendation['CostSavingsWalk'] = 0;
            $finalrecommendation['CostSavingsReplen'] = $MaxAdjCost - $CurrCostReplen;
            $finalrecommendation['Scenario'] = 1;
            return $finalrecommendation;
        } else {
            $finalrecommendation['TEXT'] = 'Imperfect Grid5 Found';
            $finalrecommendation['CostSavingsTotal'] = $ImpGRID5ScoreTotal - $CurrCostTotal;
            $finalrecommendation['CostSavingsWalk'] = $ImpGRID5ScoreWalk - $CurrCostWalk;
            $finalrecommendation['CostSavingsReplen'] = $ImpGRID5ScoreReplen - $CurrCostReplen;
            $finalrecommendation['Scenario'] = 5;
            return $finalrecommendation;
        }
    }


//case when only max can be adjusted.
//KEEP AS LAST CASE!!
    if ($PerfSlotCostTotal == '-' && $Level1CostTotal == '-' && $ImpGRID5ScoreTotal == '-' && $ImpMCCostTotal == '-' && $MaxAdjCost > $CurrCostTotal) {
        $finalrecommendation['TEXT'] = 'Adjust loc max';
        $finalrecommendation['CostSavingsTotal'] = $MaxAdjCost - $CurrCostReplen;
        $finalrecommendation['CostSavingsWalk'] = 0;
        $finalrecommendation['CostSavingsReplen'] = $MaxAdjCost - $CurrCostReplen;
        $finalrecommendation['Scenario'] = 1;
        return $finalrecommendation;
    }

    if (!isset($finalrecommendation['TEXT'])) {
        $finalrecommendation['TEXT'] = 'Logic Malfunction';
        $finalrecommendation['Scenario'] = 'Logic Malfunction';
        $finalrecommendation['CostSavingsTotal'] = 0;
    }

    return $finalrecommendation;
}

function _caseoreachtf($tier) {
    switch ($tier) {
        case 'L02':
            $tf_to_use = 'Case';
            break;
        case 'C01':
            $tf_to_use = 'Case';
            break;
        case 'C02':
            $tf_to_use = 'Case';
            break;
        case 'CSE_CONVEY':
            $tf_to_use = 'Case';
            break;
        case 'CSE_PFR_CON':
            $tf_to_use = 'Case';
            break;
        case 'CSE_NONCON':
            $tf_to_use = 'Case';
            break;
        case 'CSE_PFR_NONC':
            $tf_to_use = 'Case';
            break;

        default:
            $tf_to_use = 'Each';
            break;
    }

    return $tf_to_use;
}

if (!function_exists('array_column')) {

    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if (!isset($value[$columnKey])) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            } else {
                if (!isset($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if (!is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }

}

function _walkcost($currbay, $shouldbay, $dailypicks, $currfeet) {
    $hourlyrate = 19;
    $walkrate_sec = 4.4;
    $days_year = 253;


    switch ($shouldbay) {
        case 0:
            $shldwalkfeet = 8;
            break;
        case 1:
            $shldwalkfeet = 8;
            break;
        case 2:
            $shldwalkfeet = 20;
            break;
        case 3:
            $shldwalkfeet = 20;
            break;
        case 4:
            $shldwalkfeet = 28;
            break;
        case 5:
            $shldwalkfeet = 28;
            break;
        case 6:
            $shldwalkfeet = 36;
            break;
        case 7:
            $shldwalkfeet = 36;
            break;
        case 8:
            $shldwalkfeet = 44;
            break;
        case 9:
            $shldwalkfeet = 44;
            break;
        case 10:
            $shldwalkfeet = 52;
            break;
        case 11:
            $shldwalkfeet = 52;
            break;
        case 12:
            $shldwalkfeet = 60;
            break;
        case 13:
            $shldwalkfeet = 60;
            break;
        case 14:
            $shldwalkfeet = 68;
            break;
        case 15:
            $shldwalkfeet = 68;
            break;
        case 16:
            $shldwalkfeet = 76;
            break;
        case 17:
            $shldwalkfeet = 76;
            break;
        case 18:
            $shldwalkfeet = 84;
            break;
        case 19:
            $shldwalkfeet = 84;
            break;
        case 20:
            $shldwalkfeet = 92;
            break;
        case 21:
            $shldwalkfeet = 100;
            break;
        case 22:
            $shldwalkfeet = 100;
            break;
        case 23:
            $shldwalkfeet = 108;
            break;
        case 24:
            $shldwalkfeet = 108;
            break;
        case 25:
            $shldwalkfeet = 116;
            break;
        case 26:
            $shldwalkfeet = 116;
            break;
        case 27:
            $shldwalkfeet = 124;
            break;
        case 28:
            $shldwalkfeet = 124;
            break;
        case 29:
            $shldwalkfeet = 132;
            break;
        case 30:
            $shldwalkfeet = 132;
            break;
        default:
            $shldwalkfeet = 132;
            break;
    }

    if ($currfeet >= 0) {
        $currwalkfeet = $currfeet;
    } else { //default to standards
        switch ($currbay) {
            case 0:
                $currwalkfeet = 8;
                break;
            case 1:
                $currwalkfeet = 8;
                break;
            case 2:
                $currwalkfeet = 20;
                break;
            case 3:
                $currwalkfeet = 20;
                break;
            case 4:
                $currwalkfeet = 28;
                break;
            case 5:
                $currwalkfeet = 28;
                break;
            case 6:
                $currwalkfeet = 36;
                break;
            case 7:
                $currwalkfeet = 36;
                break;
            case 8:
                $currwalkfeet = 44;
                break;
            case 9:
                $currwalkfeet = 44;
                break;
            case 10:
                $currwalkfeet = 52;
                break;
            case 11:
                $currwalkfeet = 52;
                break;
            case 12:
                $currwalkfeet = 60;
                break;
            case 13:
                $currwalkfeet = 60;
                break;
            case 14:
                $currwalkfeet = 68;
                break;
            case 15:
                $currwalkfeet = 68;
                break;
            case 16:
                $currwalkfeet = 76;
                break;
            case 17:
                $currwalkfeet = 76;
                break;
            case 18:
                $currwalkfeet = 84;
                break;
            case 19:
                $currwalkfeet = 84;
                break;
            case 20:
                $currwalkfeet = 92;
                break;
            case 21:
                $currwalkfeet = 100;
                break;
            case 22:
                $currwalkfeet = 100;
                break;
            case 23:
                $currwalkfeet = 108;
                break;
            case 24:
                $currwalkfeet = 108;
                break;
            case 25:
                $currwalkfeet = 116;
                break;
            case 26:
                $currwalkfeet = 116;
                break;
            case 27:
                $currwalkfeet = 124;
                break;
            case 28:
                $currwalkfeet = 124;
                break;
            case 29:
                $currwalkfeet = 132;
                break;
            case 30:
                $currwalkfeet = 132;
                break;
            default:
                $currwalkfeet = 132;
                break;
        }
    }

    $addtl_feet_per_pick = $currwalkfeet - $shldwalkfeet;
    $addtl_feet_per_day = $addtl_feet_per_pick * $dailypicks;
    $addtl_cost_per_year = ((($addtl_feet_per_day * $days_year) / $walkrate_sec ) / 3600) * $hourlyrate;

    $walkcostarray = array();
    $walkcostarray['CURR_FT_PER_DAY'] = $currwalkfeet * $dailypicks;
    $walkcostarray['SHOULD_FT_PER_DAY'] = $shldwalkfeet * $dailypicks;
    $walkcostarray['ADDTL_FT_PER_PICK'] = $addtl_feet_per_pick;
    $walkcostarray['ADDTL_FT_PER_DAY'] = $addtl_feet_per_day;
    $walkcostarray['ADDTL_COST_PER_YEAR'] = $addtl_cost_per_year;

    return $walkcostarray;
}

function _baywalkfeet($bay) {

    switch ($bay) {
        case 0:
            $shldwalkfeet = 0;
            break;
        case 1:
            $shldwalkfeet = 4250;
            break;
        case 2:
            $shldwalkfeet = 4250;
            break;
        case 3:
            $shldwalkfeet = 6250;
            break;
        case 4:
            $shldwalkfeet = 6250;
            break;
        case 5:
            $shldwalkfeet = 8250;
            break;
        case 6:
            $shldwalkfeet = 8250;
            break;
        case 7:
            $shldwalkfeet = 10250;
            break;
        case 8:
            $shldwalkfeet = 10250;
            break;
        case 9:
            $shldwalkfeet = 12250;
            break;
        case 10:
            $shldwalkfeet = 12250;
            break;
        case 11:
            $shldwalkfeet = 14250;
            break;
        case 12:
            $shldwalkfeet = 14250;
            break;
        default:
            $shldwalkfeet = 20000;
            break;
    }

    return $shldwalkfeet;
}

function _walkcost_GILL($currbay, $shouldbay, $dailypicks, $currfeet) {
    $hourlyrate = 19;
    $walkrate_sec = 1.4;
    $days_year = 253;


    switch ($shouldbay) {
        case 0:
            $shldwalkfeet = 0;
            break;
        case 1:
            $shldwalkfeet = 4250;
            break;
        case 2:
            $shldwalkfeet = 4250;
            break;
        case 3:
            $shldwalkfeet = 6250;
            break;
        case 4:
            $shldwalkfeet = 6250;
            break;
        case 5:
            $shldwalkfeet = 8250;
            break;
        case 6:
            $shldwalkfeet = 8250;
            break;
        case 7:
            $shldwalkfeet = 10250;
            break;
        case 8:
            $shldwalkfeet = 10250;
            break;
        case 9:
            $shldwalkfeet = 12250;
            break;
        case 10:
            $shldwalkfeet = 12250;
            break;
        case 11:
            $shldwalkfeet = 14250;
            break;
        case 12:
            $shldwalkfeet = 14250;
            break;
        default:
            $shldwalkfeet = 20000;
            break;
    }

    if ($currfeet >= 0) {
        $currwalkfeet = $currfeet;
    } else { //default to standards
        switch ($currbay) {
            case 0:
                $currwalkfeet = 0;
                break;
            case 1:
                $currwalkfeet = 4250;
                break;
            case 2:
                $currwalkfeet = 4250;
                break;
            case 3:
                $currwalkfeet = 6250;
                break;
            case 4:
                $currwalkfeet = 6250;
                break;
            case 5:
                $currwalkfeet = 8250;
                break;
            case 6:
                $currwalkfeet = 8250;
                break;
            case 7:
                $currwalkfeet = 10250;
                break;
            case 8:
                $currwalkfeet = 10250;
                break;
            case 9:
                $currwalkfeet = 12250;
                break;
            case 10:
                $currwalkfeet = 12250;
                break;
            case 11:
                $currwalkfeet = 14250;
                break;
            case 12:
                $currwalkfeet = 14250;
                break;
            default:
                $currwalkfeet = 20000;
                break;
        }
    }

    $addtl_feet_per_pick = ($currwalkfeet - $shldwalkfeet) / 1000;  //addtl meters per pick
    $addtl_feet_per_day = $addtl_feet_per_pick * $dailypicks;
    $addtl_cost_per_year = ((($addtl_feet_per_day * $days_year) / $walkrate_sec ) / 3600) * $hourlyrate;

    $walkcostarray = array();
    $walkcostarray['CURR_FT_PER_DAY'] = $currwalkfeet * $dailypicks;
    $walkcostarray['SHOULD_FT_PER_DAY'] = $shldwalkfeet * $dailypicks;
    $walkcostarray['ADDTL_FT_PER_PICK'] = $addtl_feet_per_pick;
    $walkcostarray['ADDTL_FT_PER_DAY'] = $addtl_feet_per_day;
    $walkcostarray['ADDTL_COST_PER_YEAR'] = $addtl_cost_per_year;

    return $walkcostarray;
}

function _walkcost_feet($currfeet, $shouldfeet, $dailypicks) {
    $hourlyrate = 19;
    $walkrate_sec = 1.4;
    $days_year = 253;


    $addtl_feet_per_pick = ($currfeet - $shouldfeet) / 1000;  //addtl meters per pick
    $addtl_feet_per_day = $addtl_feet_per_pick * $dailypicks;
    $addtl_cost_per_year = ((($addtl_feet_per_day * $days_year) / $walkrate_sec ) / 3600) * $hourlyrate;

    $walkcostarray = array();
    $walkcostarray['CURR_FT_PER_DAY'] = $currfeet * $dailypicks;
    $walkcostarray['SHOULD_FT_PER_DAY'] = $shouldfeet * $dailypicks;
    $walkcostarray['ADDTL_FT_PER_PICK'] = $addtl_feet_per_pick;
    $walkcostarray['ADDTL_FT_PER_DAY'] = $addtl_feet_per_day;
    $walkcostarray['ADDTL_COST_PER_YEAR'] = $addtl_cost_per_year;

    return $walkcostarray;
}

function _walkcost_NOTL($currbay, $shouldbay, $dailypicks) {
    $hourlyrate = 19;
    $walkrate_sec = 4.4;
    $days_year = 253;
    switch ($shouldbay) {
        case 0:
            $shldwalkfeet = 8;
            break;
        case 1:
            $shldwalkfeet = 20;
            break;
        case 2:
            $shldwalkfeet = 20;
            break;
        case 3:
            $shldwalkfeet = 28;
            break;
        case 4:
            $shldwalkfeet = 28;
            break;
        case 5:
            $shldwalkfeet = 36;
            break;
        case 6:
            $shldwalkfeet = 36;
            break;
        case 7:
            $shldwalkfeet = 44;
            break;
        case 8:
            $shldwalkfeet = 44;
            break;
        case 9:
            $shldwalkfeet = 52;
            break;
        case 10:
            $shldwalkfeet = 52;
            break;
        case 11:
            $shldwalkfeet = 60;
            break;
        case 12:
            $shldwalkfeet = 60;
            break;
        case 13:
            $shldwalkfeet = 68;
            break;
        case 14:
            $shldwalkfeet = 68;
            break;
        case 15:
            $shldwalkfeet = 76;
            break;
        case 16:
            $shldwalkfeet = 76;
            break;
        case 17:
            $shldwalkfeet = 84;
            break;
        case 18:
            $shldwalkfeet = 84;
            break;
        case 19:
            $shldwalkfeet = 92;
            break;
        case 20:
            $shldwalkfeet = 92;
            break;
        case 21:
            $shldwalkfeet = 100;
            break;
        case 22:
            $shldwalkfeet = 100;
            break;
        case 23:
            $shldwalkfeet = 108;
            break;
        case 24:
            $shldwalkfeet = 108;
            break;
        case 25:
            $shldwalkfeet = 116;
            break;
        case 26:
            $shldwalkfeet = 116;
            break;
        case 27:
            $shldwalkfeet = 124;
            break;
        case 28:
            $shldwalkfeet = 124;
            break;
        case 29:
            $shldwalkfeet = 132;
            break;
        case 30:
            $shldwalkfeet = 132;
            break;
        default:
            $shldwalkfeet = 132;
            break;
    }



    switch ($currbay) {
        case 0:
            $currwalkfeet = 8;
            break;
        case 1:
            $currwalkfeet = 20;
            break;
        case 2:
            $currwalkfeet = 20;
            break;
        case 3:
            $currwalkfeet = 28;
            break;
        case 4:
            $currwalkfeet = 28;
            break;
        case 5:
            $currwalkfeet = 36;
            break;
        case 6:
            $currwalkfeet = 36;
            break;
        case 7:
            $currwalkfeet = 44;
            break;
        case 8:
            $currwalkfeet = 44;
            break;
        case 9:
            $currwalkfeet = 52;
            break;
        case 10:
            $currwalkfeet = 52;
            break;
        case 11:
            $currwalkfeet = 60;
            break;
        case 12:
            $currwalkfeet = 60;
            break;
        case 13:
            $currwalkfeet = 68;
            break;
        case 14:
            $currwalkfeet = 68;
            break;
        case 15:
            $currwalkfeet = 76;
            break;
        case 16:
            $currwalkfeet = 76;
            break;
        case 17:
            $currwalkfeet = 84;
            break;
        case 18:
            $currwalkfeet = 84;
            break;
        case 19:
            $currwalkfeet = 92;
            break;
        case 20:
            $currwalkfeet = 92;
            break;
        case 21:
            $currwalkfeet = 100;
            break;
        case 22:
            $currwalkfeet = 100;
            break;
        case 23:
            $currwalkfeet = 108;
            break;
        case 24:
            $currwalkfeet = 108;
            break;
        case 25:
            $currwalkfeet = 116;
            break;
        case 26:
            $currwalkfeet = 116;
            break;
        case 27:
            $currwalkfeet = 124;
            break;
        case 28:
            $currwalkfeet = 124;
            break;
        case 29:
            $currwalkfeet = 132;
            break;
        case 30:
            $currwalkfeet = 132;
            break;
        default:
            $currwalkfeet = 132;
            break;
    }


    $addtl_feet_per_pick = $currwalkfeet - $shldwalkfeet;
    $addtl_feet_per_day = $addtl_feet_per_pick * $dailypicks;
    $addtl_cost_per_year = ((($addtl_feet_per_day * $days_year) / $walkrate_sec ) / 3600) * $hourlyrate;

    $walkcostarray = array();
    $walkcostarray['CURR_FT_PER_DAY'] = $currwalkfeet * $dailypicks;
    $walkcostarray['SHOULD_FT_PER_DAY'] = $shldwalkfeet * $dailypicks;
    $walkcostarray['ADDTL_FT_PER_PICK'] = $addtl_feet_per_pick;
    $walkcostarray['ADDTL_FT_PER_DAY'] = $addtl_feet_per_day;
    $walkcostarray['ADDTL_COST_PER_YEAR'] = $addtl_cost_per_year;

    return $walkcostarray;
}

function _walkcost_case($currtier, $shouldtier, $dailypicks, $floor) {

    $hourlyrate = 19;
    $walkrate_sec = 4.4;
    $days_year = 253;

    if ($floor == 'N' || $floor == ' ' || $floor == null) {  //addresses issue with priamries in the air.  Set default picks to 50 per hour
        $hourpick_curr = 50;
    } else {
        switch ($currtier) {
            case 'C01':
                $hourpick_curr = 200;
                break;
            case 'C02':
                $hourpick_curr = 200;
                break;
            case 'C03':
                $hourpick_curr = 90;
                break;
            case 'C04':
                $hourpick_curr = 200;
                break;
            case 'C05':
                $hourpick_curr = 90;
                break;
            case 'C06':
                $hourpick_curr = 90;
                break;
            case 'C07':
                $hourpick_curr = 90;
                break;
            case 'C08':
                $hourpick_curr = 90;
                break;
            case 'C09':
                $hourpick_curr = 90;
                break;
            case 'CSE_CONVEY':
                $hourpick_curr = 90;
                break;
            case 'CSE_NONCON':
                $hourpick_curr = 90;
                break;

            default:
                $hourpick_curr = 50;
                break;
        }
    }


    switch ($shouldtier) {
        case 'C01':
            $hourpick_shld = 200;
            break;
        case 'C02':
            $hourpick_shld = 200;
            break;
        case 'CSE_CONVEY':
            $hourpick_shld = 90;
            break;
        case 'CSE_NONCON':
            $hourpick_shld = 90;
            break;
        default:
            $hourpick_shld = 50;
            break;
    }



    $addtl_min_per_pick = ((1 / $hourpick_curr) - (1 / $hourpick_shld)) * 60;
    $addtl_min_per_day = $addtl_min_per_pick * $dailypicks;
    $addtl_cost_per_year = (($addtl_min_per_day * $days_year) / 60) * $hourlyrate;

    $walkcostarray = array();
    $walkcostarray['CURR_FT_PER_DAY'] = (1 / $hourpick_curr) * $dailypicks * 60;
    $walkcostarray['SHOULD_FT_PER_DAY'] = (1 / $hourpick_shld) * $dailypicks * 60;
    $walkcostarray['ADDTL_FT_PER_PICK'] = $addtl_min_per_pick;
    $walkcostarray['ADDTL_FT_PER_DAY'] = $addtl_min_per_day;
    $walkcostarray['ADDTL_COST_PER_YEAR'] = $addtl_cost_per_year;


    $walkscore = 1 - (abs(($dailypicks / $hourpick_curr) - ($dailypicks / $hourpick_shld)) / .052632);
    if ($walkscore < 0) {
        $walkcostarray['WALK_SCORE'] = 0;
    } else {
        $walkcostarray['WALK_SCORE'] = $walkscore;
    }
    return $walkcostarray;
}

function _minloc($max, $avgshipqty, $caseqty) {
    if (($avgshipqty * 2) <= ($max * .25)) {
        $minloc = ($avgshipqty * 2); //set min to 2 ship occurences if less
    } elseif (($avgshipqty * 2) >= ($max * .25)) {
        //i changed to this at some point.  cant remember why.  Reverting back to .25 of max
        //$minloc = ceil($max); //set min to 25% of max
        $minloc = ceil($max * .25); //set min to 25% of max
    }
    if ($minloc == 0) {
        $minloc = 1;
    }

    return $minloc;
}

function _minloccase($max, $avgshipqty, $caseqty) {
    if (($avgshipqty * 2) <= ($max * .25)) {
        $minloc = ceil(($avgshipqty * 2) / $caseqty) * $caseqty; //set min to 2 ship occurences if less
    } elseif (($avgshipqty * 2) >= ($max * .25)) {
        $minloc = ceil(($max * .25) / $caseqty) * $caseqty; //set min to 25% of max
    }

    return $minloc;
}

function _slotqty_offsys($avgshipqty, $daystostock, $avginventory, $slowdownsizecutoff, $ADBS, $PKGU_PERC_Restriction) {
    $slotqtyarray = array();
    if ($ADBS >= $slowdownsizecutoff) {  //if designated as a slow mover, slot to 2 ship occurences
        $slotqtyarray['OPTQTY'] = $avgshipqty * 2;
        return $slotqtyarray;
    }

    $maxceil = intval(ceil($avginventory * $PKGU_PERC_Restriction));

    $optimatalqty = $avgshipqty * $daystostock;  //based of days to stock, optimal quantity to slot as max
    $slotqtyarray['OPTQTY'] = $optimatalqty;
    if ($optimatalqty > $maxceil) {
        $slotqtyarray['CEILQTY'] = $maxceil;
        return $slotqtyarray;
    } else {
        return $slotqtyarray;
    }
}

function _implied_daily_moves($max, $min, $daily_ship_qty, $avginv, $shipqtymn, $adbs) {
    $loc_theoretical_max = min($max, $avginv);  //should never have more than this in location.
    if ($daily_ship_qty == 0) {
        $impliedmoves = 0;
        return $impliedmoves;
    }

    if ($shipqtymn >= $max) { //prevent overstating moves because of large single shipments
        $impliedmoves = 1 / $adbs;
        return $impliedmoves;
    }

    if ($max == $min) {
        $impliedmoves = 1 / ($max / $daily_ship_qty);
        return $impliedmoves;
    }

    $divisor = (($loc_theoretical_max - $min) / $daily_ship_qty);
    if ($divisor == 0) {
        $divisor = 9999999;
    }

    if ($loc_theoretical_max == $avginv) {
        $impliedmoves = 0;
    } else {
        $impliedmoves = 1 / $divisor;
    }
    return $impliedmoves;
}

function _implied_daily_moves_nomin($max, $daily_ship_qty, $avginv) {
    $loc_theoretical_max = min($max, $avginv);  //should never have more than this in location.
    if ($daily_ship_qty == 0) {
        $impliedmoves = 0;
        return $impliedmoves;
    }
    if ($loc_theoretical_max == $avginv) {
        $impliedmoves = 0;
        return $impliedmoves;
    }

    $impliedmoves = $daily_ship_qty / $loc_theoretical_max;
    return $impliedmoves;
}

function _implied_daily_moves_withcurrentTF($max, $min, $daily_ship_qty, $avginv, $shipqtymn, $adbs, $var_CURTF) {
    $loc_theoretical_max = min($max, $avginv);  //should never have more than this in location.
    if ($daily_ship_qty == 0) {
        $impliedmoves = 0;
        return $impliedmoves;
    }

    if ($shipqtymn >= $max) { //prevent overstating moves because of large single shipments
        $impliedmoves = 1 / $adbs;
        return $impliedmoves;
    }

    $divisor = (($loc_theoretical_max - $min) / $daily_ship_qty);
    if ($divisor == 0) {
        $divisor = 9999999;
    }

    if (isset($var_CURTF)) {
        if ($var_CURTF >= $avginv) {
            $impliedmoves = 0;
            return $impliedmoves;
        }
    }

    if ($loc_theoretical_max == $avginv) {
        $impliedmoves = 0;
    } else {
        $impliedmoves = 1 / $divisor;
    }
    return $impliedmoves;
}

function _replen_score_from_moves($newmoves) {
    $movesperhour = 15;
    $movediff = abs($newmoves);
    $normalizedscore = 1 - (($movediff / $movesperhour) / .052632);

    if ($normalizedscore < 0) {
        $normalizedscore = 0;
    }
    return $normalizedscore;
}

function _walkscore($currbay, $shouldbay, $dailypicks, $currwalkfeet_bay) {


    switch ($shouldbay) {
        case 0:
            $shldwalkfeet = 0;
            break;
        case 1:
            $shldwalkfeet = 4250;
            break;
        case 2:
            $shldwalkfeet = 4250;
            break;
        case 3:
            $shldwalkfeet = 6250;
            break;
        case 4:
            $shldwalkfeet = 6250;
            break;
        case 5:
            $shldwalkfeet = 8250;
            break;
        case 6:
            $shldwalkfeet = 8250;
            break;
        case 7:
            $shldwalkfeet = 10250;
            break;
        case 8:
            $shldwalkfeet = 10250;
            break;
        case 9:
            $shldwalkfeet = 12250;
            break;
        case 10:
            $shldwalkfeet = 12250;
            break;
        case 11:
            $shldwalkfeet = 14250;
            break;
        case 12:
            $shldwalkfeet = 14250;
            break;
        default:
            $shldwalkfeet = 20000;
            break;
    }

    if ($currwalkfeet_bay >= 0) {
        $currwalkfeet = $currwalkfeet_bay;
    } else {
        switch ($currbay) {
            case 0:
                $currwalkfeet = 0;
                break;
            case 1:
                $currwalkfeet = 4250;
                break;
            case 2:
                $currwalkfeet = 4250;
                break;
            case 3:
                $currwalkfeet = 6250;
                break;
            case 4:
                $currwalkfeet = 6250;
                break;
            case 5:
                $currwalkfeet = 8250;
                break;
            case 6:
                $currwalkfeet = 8250;
                break;
            case 7:
                $currwalkfeet = 10250;
                break;
            case 8:
                $currwalkfeet = 10250;
                break;
            case 9:
                $currwalkfeet = 12250;
                break;
            case 10:
                $currwalkfeet = 12250;
                break;
            case 11:
                $currwalkfeet = 14250;
                break;
            case 12:
                $currwalkfeet = 14250;
                break;
            default:
                $currwalkfeet = 20000;
                break;
        }
    }
    $dailyfeet = ($currwalkfeet - $shldwalkfeet) * $dailypicks;
    $walkscore = 1 - (abs($dailyfeet) / 1000 / 1.4 / .052632);

    if ($walkscore < 0) {
        $walkscore = 0;
    }


    return $walkscore;
}

function _walkcost_case2($currfeet, $shouldfeet, $dailypicks) {

    $hourlyrate = 19;
    $machinefeetpersec = 7.33;  //assumed 5 MPH
    $days_year = 253;
    $batchfactor = 70;  //approximate number of cases per batch.  Since picks are batched, each walktime is decreased by factor to more accurately represent costs.




    $addtl_min_per_pick = ((($currfeet - $shouldfeet) / $batchfactor) / $machinefeetpersec) / 60;
    $addtl_min_per_day = $addtl_min_per_pick * $dailypicks;
    $addtl_cost_per_year = (($addtl_min_per_day * $days_year) / 60) * $hourlyrate;

    $walkcostarray = array();
    $walkcostarray['CURR_FT_PER_DAY'] = $currfeet * $dailypicks / $batchfactor;
    $walkcostarray['SHOULD_FT_PER_DAY'] = $shouldfeet * $dailypicks / $batchfactor;
    $walkcostarray['ADDTL_FT_PER_PICK'] = ($currfeet - $shouldfeet) / $batchfactor;
    $walkcostarray['ADDTL_FT_PER_DAY'] = ($currfeet - $shouldfeet) * $dailypicks / $batchfactor;
    $walkcostarray['ADDTL_COST_PER_YEAR'] = $addtl_cost_per_year;


    $walkscore = 1 - (abs((($currfeet - $shouldfeet) / $batchfactor)) / 5280 / 5 / .052632);
    if ($walkscore < 0) {
        $walkcostarray['WALK_SCORE'] = 0;
    } else {
        $walkcostarray['WALK_SCORE'] = $walkscore;
    }
    return $walkcostarray;
}
