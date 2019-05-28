
<?php
ini_set('max_execution_time', 99999);
set_time_limit(99999);
ini_set('memory_limit', '-1');
include_once '../connection/NYServer.php';
include_once '../../globalfunctions/slottingfunctions.php';
include_once '../../globalfunctions/newitem.php';

$var_userid = $_POST['userid'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);
$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];

if (isset($_POST['returncount'])) {  //user came from loose slotting module
    $returncount = $_POST['returncount'];
    $zone = $_POST['zone'];
    $itemnumsql = ' ';
}

if (isset($_POST['itemnum'])) {  //user came from slotting assist module
    $returncount = 1;
    $zone = 'LSE';
    $itemnum = intval($_POST['itemnum']);
    $itemnumsql = ' and A.ITEM_NUMBER = ' . $itemnum;
}


$avg_score_inc = 0;
$displayarray = array();  //initiate array
//include file to return highest cost items by Whse and Tier.  Will loop through these items.  Start with top 10.
if ($zone == 'LSE') {
    include_once 'highscorearray_LSE.php';
} else {
    include_once 'highscorearray_CSE.php';
}

//include empty location file from NPFLSM.  Put into array $NPFLSM_array.
include_once 'emptylocdata.php';

//include empty grids data.  Just array of available empty grids by fixture MC
//include_once 'emptygridsdata.php';
//Loop through $TOP_REPLEN_COST_array to determine appropriate course of action
foreach ($TOP_REPLEN_COST_array as $topcostkey => $topvalue) {


    $settingscheck = 0; //initiate settings check
    $LEVEL_ONE_match_key = $IMPERFECT_MC_key = $IMPERFECT_GRID5_key = FALSE;
    $var_itemcode = $TOP_REPLEN_COST_array[$topcostkey]['ITEM_NUMBER']; //variable for include files
    $var_location = $TOP_REPLEN_COST_array[$topcostkey]['CUR_LOCATION']; //variable for include files

    $displayarray[$topcostkey]['ITEM_NUMBER'] = $var_itemcode; //add info to display array
    $displayarray[$topcostkey]['CUR_LOCATION'] = $var_location; //add info to display array
    $displayarray[$topcostkey]['LMGRD5'] = $TOP_REPLEN_COST_array[$topcostkey]['LMGRD5']; //add info to display array
    $displayarray[$topcostkey]['SCORE_TOTALSCORE'] = $TOP_REPLEN_COST_array[$topcostkey]['SCORE_TOTALSCORE']; //add info to display array
    $displayarray[$topcostkey]['SCORE_WALKSCORE'] = $TOP_REPLEN_COST_array[$topcostkey]['SCORE_WALKSCORE']; //add info to display array
    $displayarray[$topcostkey]['SCORE_REPLENSCORE'] = $TOP_REPLEN_COST_array[$topcostkey]['SCORE_REPLENSCORE']; //add info to display array
    $displayarray[$topcostkey]['SCORE_TOTALSCORE_OPT'] = $TOP_REPLEN_COST_array[$topcostkey]['SCORE_TOTALSCORE_OPT']; //add info to display array
    $displayarray[$topcostkey]['SCORE_WALKSCORE_OPT'] = $TOP_REPLEN_COST_array[$topcostkey]['SCORE_WALKSCORE_OPT']; //add info to display array
    $displayarray[$topcostkey]['SCORE_REPLENSCORE_OPT'] = $TOP_REPLEN_COST_array[$topcostkey]['SCORE_REPLENSCORE_OPT']; //add info to display array
    $displayarray[$topcostkey]['AVGD_BTW_SLE'] = $TOP_REPLEN_COST_array[$topcostkey]['AVGD_BTW_SLE']; //add info to display array
    $displayarray[$topcostkey]['DAYS_FRM_SLE'] = $TOP_REPLEN_COST_array[$topcostkey]['DAYS_FRM_SLE']; //add info to display array
    $displayarray[$topcostkey]['CURMAX'] = $TOP_REPLEN_COST_array[$topcostkey]['CURMAX']; //add info to display array
    $displayarray[$topcostkey]['CURMIN'] = $TOP_REPLEN_COST_array[$topcostkey]['CURMIN']; //add info to display array
    $displayarray[$topcostkey]['LMTIER'] = $TOP_REPLEN_COST_array[$topcostkey]['LMTIER']; //add info to display array
    $displayarray[$topcostkey]['SUGGESTED_TIER'] = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_TIER']; //add info to display array
    $displayarray[$topcostkey]['LMDEEP'] = $TOP_REPLEN_COST_array[$topcostkey]['LMDEEP']; //add info to display array
    $displayarray[$topcostkey]['SHIP_QTY_MN'] = $TOP_REPLEN_COST_array[$topcostkey]['SHIP_QTY_MN']; //add info to display array
    $displayarray[$topcostkey]['PICK_QTY_MN'] = $TOP_REPLEN_COST_array[$topcostkey]['PICK_QTY_MN']; //add info to display array
    $displayarray[$topcostkey]['AVG_DAILY_PICK'] = $TOP_REPLEN_COST_array[$topcostkey]['AVG_DAILY_PICK']; //add info to display array
    $displayarray[$topcostkey]['AVG_DAILY_UNIT'] = $TOP_REPLEN_COST_array[$topcostkey]['AVG_DAILY_UNIT']; //add info to display array
    $displayarray[$topcostkey]['SUGGESTED_SLOTQTY'] = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_SLOTQTY']; //add info to display array
    $displayarray[$topcostkey]['VCCTRF'] = $TOP_REPLEN_COST_array[$topcostkey]['VCCTRF']; //add info to display array
    $displayarray[$topcostkey]['SUGGESTED_GRID5'] = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_GRID5']; //add info to display array
    $displayarray[$topcostkey]['SUGGESTED_DEPTH'] = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_DEPTH']; //add info to display array
    $displayarray[$topcostkey]['SUGGESTED_MAX'] = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_MAX']; //add info to display array
    $displayarray[$topcostkey]['CURRENT_IMPMOVES'] = $TOP_REPLEN_COST_array[$topcostkey]['CURRENT_IMPMOVES']; //add info to display array
    $displayarray[$topcostkey]['SUGGESTED_IMPMOVES'] = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_IMPMOVES']; //add info to display array
    $displayarray[$topcostkey]['PACKAGE_UNIT'] = $TOP_REPLEN_COST_array[$topcostkey]['PACKAGE_UNIT']; //add info to display array
    $displayarray[$topcostkey]['AVG_INV_OH'] = $TOP_REPLEN_COST_array[$topcostkey]['AVG_INV_OH']; //add info to display array
    $displayarray[$topcostkey]['ASSNTSM'] = $TOP_REPLEN_COST_array[$topcostkey]['openactions_assignedto']; //add info to display array
    $displayarray[$topcostkey]['ASSNCOMM'] = $TOP_REPLEN_COST_array[$topcostkey]['openactions_comment']; //add info to display array



    $OPT_OPTBAY = $TOP_REPLEN_COST_array[$topcostkey]['OPT_OPTBAY'];
    $OPT_OPTWALKFEET= $TOP_REPLEN_COST_array[$topcostkey]['SUGG_WALKFEET'];
    $OPT_CURRBAY = $TOP_REPLEN_COST_array[$topcostkey]['OPT_CURRBAY'];
    $OPT_CURRDAILYFT = $TOP_REPLEN_COST_array[$topcostkey]['OPT_CURRDAILYFT'];
    $OPT_SHLDDAILYFT = $TOP_REPLEN_COST_array[$topcostkey]['OPT_SHLDDAILYFT'];
    $OPT_ADDTLFTPERPICK = $TOP_REPLEN_COST_array[$topcostkey]['OPT_ADDTLFTPERPICK'];
    $OPT_ADDTLFTPERDAY = $TOP_REPLEN_COST_array[$topcostkey]['OPT_ADDTLFTPERDAY'];


    $displayarray[$topcostkey]['OPT_OPTBAY'] = $OPT_OPTBAY;
    $displayarray[$topcostkey]['OPT_CURRBAY'] = $OPT_CURRBAY;
    $displayarray[$topcostkey]['OPT_CURRDAILYFT'] = $OPT_CURRDAILYFT;
    $displayarray[$topcostkey]['OPT_SHLDDAILYFT'] = $OPT_SHLDDAILYFT;
    $displayarray[$topcostkey]['OPT_ADDTLFTPERPICK'] = $OPT_ADDTLFTPERPICK;
    $displayarray[$topcostkey]['OPT_ADDTLFTPERDAY'] = $OPT_ADDTLFTPERDAY;


    $displayarray[$topcostkey]['OKinFlow'] = $TOP_REPLEN_COST_array[$topcostkey]['CPCFLOW'];
    $displayarray[$topcostkey]['OKinTote'] = $TOP_REPLEN_COST_array[$topcostkey]['CPCTOTE'];
    $displayarray[$topcostkey]['OKinShelf'] = $TOP_REPLEN_COST_array[$topcostkey]['CPCSHLF'];
    $displayarray[$topcostkey]['Rotate'] = $TOP_REPLEN_COST_array[$topcostkey]['CPCROTA'];
    $displayarray[$topcostkey]['Stack'] = $TOP_REPLEN_COST_array[$topcostkey]['CPCESTK'];
    $displayarray[$topcostkey]['Liquid'] = $TOP_REPLEN_COST_array[$topcostkey]['CPCLIQU'];
    $displayarray[$topcostkey]['CasePkgu'] = $TOP_REPLEN_COST_array[$topcostkey]['CPCCPKU'];
    $displayarray[$topcostkey]['PFRC'] = $TOP_REPLEN_COST_array[$topcostkey]['CPCPFRC']; //add info to display array
    $displayarray[$topcostkey]['PFRA'] = $TOP_REPLEN_COST_array[$topcostkey]['CPCPFRA']; //add info to display array

    $VCPKGU = intval($TOP_REPLEN_COST_array[$topcostkey]['PACKAGE_UNIT']);
    $VCGRD5 = $TOP_REPLEN_COST_array[$topcostkey]['LMGRD5'];
    $VCFTIR = $TOP_REPLEN_COST_array[$topcostkey]['LMTIER'];
    $VCTTIR = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_TIER'];
    $VCNGD5 = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_GRID5'];
    $VCNDEP = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_DEPTH'];
    $VCNDMD = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_SLOTQTY'];
    $LOMAXC = $TOP_REPLEN_COST_array[$topcostkey]['CURMAX'];
    $LOMINC = $TOP_REPLEN_COST_array[$topcostkey]['CURMIN'];
    $LMDEEP = $TOP_REPLEN_COST_array[$topcostkey]['LMDEEP'];

    //$PCIPKU = $TOP_REPLEN_COST_array[$topcostkey]['CPCIPKU'];
    $PCCPKU = $TOP_REPLEN_COST_array[$topcostkey]['CPCCPKU'];
    $PCEPKU = $TOP_REPLEN_COST_array[$topcostkey]['CPCEPKU'];
    $PCFLOR = $TOP_REPLEN_COST_array[$topcostkey]['CPCFLOW'];
    $PCTOTE = $TOP_REPLEN_COST_array[$topcostkey]['CPCTOTE'];
    $PCSHLF = $TOP_REPLEN_COST_array[$topcostkey]['CPCSHLF'];
    $PCEROT = $TOP_REPLEN_COST_array[$topcostkey]['CPCROTA'];
    $PCESTA = $TOP_REPLEN_COST_array[$topcostkey]['CPCESTK'];
    $PCLIQU = $TOP_REPLEN_COST_array[$topcostkey]['CPCLIQU'];
    $PCPFRC = $TOP_REPLEN_COST_array[$topcostkey]['CPCPFRC'];
    $PCPFRA = $TOP_REPLEN_COST_array[$topcostkey]['CPCPFRA'];
    $PCELEN = $TOP_REPLEN_COST_array[$topcostkey]['CPCELEN'];
    $PCEHEI = $TOP_REPLEN_COST_array[$topcostkey]['CPCEHEI'];
    $PCEWID = $TOP_REPLEN_COST_array[$topcostkey]['CPCEWID'];
    $PCCLEN = $TOP_REPLEN_COST_array[$topcostkey]['CPCCLEN'];
    $PCCHEI = $TOP_REPLEN_COST_array[$topcostkey]['CPCCHEI'];
    $PCCWID = $TOP_REPLEN_COST_array[$topcostkey]['CPCCWID'];


//include file to NPFMVC needed data points. Also include current depth, max, and min from NPFLOC file. Put into array $NPFMVC_array for all 5 whse.  Will have to parse out based on primary warehouse
//    include '../globaldata/NPFMVCdata.php';
//include slotting demand info from NPTSLD.  Put into array $NPTSLT_array.
//    include '../globaldata/NPTSLDdata.php';
//include NPFCPC data for settings issues/checks.  
//    include '../globaldata/NPFCPCdata.php';
//Calculate slotting cost by calling function _slotting_replen_cost in slottingfunctions.php
//Values in array include: (1)Days Demand at location, (2)Implied Daily Moves, (3)Acceptable Daily Moves, (4)Additional Moves over Acceptable, (5)Yearly Replen Cost
//    $replen_cost_return_array = _slotting_replen_cost($LOMAXC, $VCCTRF, $displayarray[$topcostkey]['Curr_Min'], $SHIP_QTY_MN, $AVGD_BTW_SLE);
//    $walk_cost_return_array = _walkcost($OPT_CURRBAY, $OPT_OPTBAY, $OPT_DAILYPICKS);
//Max to True Fit Test.  Call function _maxtoTFtest to determine if opportunity to upsize max exists
//do not have a current true fit??
    $MAX_Increase = _maxtoTFtest($displayarray[$topcostkey]['CURMAX'], $displayarray[$topcostkey]['VCCTRF'], $displayarray[$topcostkey]['SUGGESTED_SLOTQTY']);
    //$MAX_Increase = 0;
    if ($MAX_Increase > 0) {
      //  $upsizemax_newmin = _minloc($TOP_REPLEN_COST_array[$topcostkey]['VCCTRF'], $TOP_REPLEN_COST_array[$topcostkey]['SHIP_QTY_MN'], $TOP_REPLEN_COST_array[$topcostkey]['CPCCPKU']);
        $upsizemax_newmin = $TOP_REPLEN_COST_array[$topcostkey]['CURMIN'];
        $impmoves_after_max_increase = _implied_daily_moves($displayarray[$topcostkey]['VCCTRF'], $upsizemax_newmin, $TOP_REPLEN_COST_array[$topcostkey]['AVG_DAILY_UNIT'], $TOP_REPLEN_COST_array[$topcostkey]['AVG_INV_OH'], $TOP_REPLEN_COST_array[$topcostkey]['SHIP_QTY_MN'], $TOP_REPLEN_COST_array[$topcostkey]['AVGD_BTW_SLE']);
        $replen_score_Max_Increase = _replen_score_from_moves($impmoves_after_max_increase);
//        $replen_cost_return_array_Max_Increase = _slotting_replen_cost($VCCTRF, $VCCTRF, $displayarray[$topcostkey]['Curr_Min'], $SHIP_QTY_MN, $AVGD_BTW_SLE);
        $displayarray[$topcostkey]['MOVESCORE_AFTER_MAX_INCREASE'] = $replen_score_Max_Increase;
        $displayarray[$topcostkey]['MOVES_AFTER_MAX_INCREASE'] = $impmoves_after_max_increase;
    } else {
        $displayarray[$topcostkey]['MOVESCORE_AFTER_MAX_INCREASE'] = '-';
        $displayarray[$topcostkey]['MOVES_AFTER_MAX_INCREASE'] = '-';
    }


//Is the curr grid = to new grid?  If so, what is causing the high replen cost?
    if (($TOP_REPLEN_COST_array[$topcostkey]['LMGRD5'] == $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_GRID5']) && ($VCNDEP == $TOP_REPLEN_COST_array[$topcostkey]['LMDEEP'] )) {
        $settingscheck = 999; //indicate that item is in proper grid.  Probable that a setting is causing the high replen cost 
//call slotting settings function to determine what is causing the issue
    }

//STEP 1: Is there a perfect grid, perfect MC empty location available
    $PERFGRID = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_TIER'] . $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_GRID5'] . $OPT_OPTWALKFEET;
    $perfect_match_key = array_search($PERFGRID, array_column($EMPTYLOC_array, 'KEYVAL'));

    if ($perfect_match_key !== FALSE) { //a perfect grid match has been found.  Set as new location
        $NEW_LOC = $EMPTYLOC_array[$perfect_match_key]['LOCATION'];
        $displayarray[$topcostkey]['PERF_SLOT_LOC'] = $NEW_LOC;
        $NEW_GRD5 = $EMPTYLOC_array[$perfect_match_key]['LOC_DIM'];
        $displayarray[$topcostkey]['AssgnGrid5'] = $NEW_GRD5; //Add new grid5 to display array
        $NEW_LOC_TRUEFIT_round2 = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_MAX'];
        $Newmin = _minloc($NEW_LOC_TRUEFIT_round2, $TOP_REPLEN_COST_array[$topcostkey]['SHIP_QTY_MN'], $TOP_REPLEN_COST_array[$topcostkey]['CPCCPKU']);
        $impmoves_after_perfloc = _implied_daily_moves($NEW_LOC_TRUEFIT_round2, $Newmin, $TOP_REPLEN_COST_array[$topcostkey]['AVG_DAILY_UNIT'], $TOP_REPLEN_COST_array[$topcostkey]['AVG_INV_OH'], $TOP_REPLEN_COST_array[$topcostkey]['SHIP_QTY_MN'], $TOP_REPLEN_COST_array[$topcostkey]['AVGD_BTW_SLE']);
        $replen_score_Perf_Loc = _replen_score_from_moves($impmoves_after_perfloc);

        if ($zone == 'CSE') { //calculate LSE or CSE walk cost
            $walk_score_Perf_Loc_array = _walkcost_case($VCFTIR, $VCTTIR, $TOP_REPLEN_COST_array[$topcostkey]['AVG_DAILY_UNIT'], $TOP_REPLEN_COST_array[$topcostkey]['FLOOR']);
            $walk_score_Perf_Loc = 1;
        } else {
            $walk_score_Perf_Loc = _walkscore(intval(substr($NEW_LOC, 3, 2)), $OPT_OPTBAY, $TOP_REPLEN_COST_array[$topcostkey]['AVG_DAILY_PICK']);
        }
        $displayarray[$topcostkey]['MOVES_AFTER_PERF_GRID'] = $impmoves_after_perfloc;
        $displayarray[$topcostkey]['MOVESCORE_AFTER_PERF_GRID'] = abs($replen_score_Perf_Loc);
        $displayarray[$topcostkey]['WALKSCORE_AFTER_PERF_GRID'] = abs($walk_score_Perf_Loc);
        $displayarray[$topcostkey]['TOTSCORE_AFTER_PERF_GRID'] = abs($replen_score_Perf_Loc) * abs($walk_score_Perf_Loc);
        unset($EMPTYLOC_array[$perfect_match_key]);
        $EMPTYLOC_array = array_values($EMPTYLOC_array);
    } else {
        $displayarray[$topcostkey]['MOVES_AFTER_PERF_GRID'] = '-';
        $displayarray[$topcostkey]['MOVESCORE_AFTER_PERF_GRID'] = '-';
        $displayarray[$topcostkey]['WALKSCORE_AFTER_PERF_GRID'] = '-';
        $displayarray[$topcostkey]['TOTSCORE_AFTER_PERF_GRID'] = '-';
    }
//******END OF STEP 1*******
//STEP 2: Is there an item that wants to downzize with a perfect match. Sort by replen cost ascending.
    if ($perfect_match_key === FALSE) {
        include 'loc_swap_onelevel.php'; //call logic to find perfect swap location
    } else {
        $displayarray[$topcostkey]['MOVES_AFTER_LEVEL1_SWAP'] = '-';
        $displayarray[$topcostkey]['MOVESCORE_AFTER_LEVEL1_SWAP'] = '-';
        $displayarray[$topcostkey]['WALKSCORE_AFTER_LEVEL1_SWAP'] = '-';
        $displayarray[$topcostkey]['TOTSCORE_AFTER_LEVEL1_SWAP'] = '-';
    }
//******END OF STEP 2*******
//STEP 3: Is there a perfect grid with an imperfect MC
    if ($perfect_match_key === FALSE && $LEVEL_ONE_match_key === FALSE) {
        include 'imperfect_mc.php'; //call logic to find perfect swap location
    } else {

        $displayarray[$topcostkey]['AFTER_IMPERFECT_MC_SLOT_MOVES'] = '-';
        $displayarray[$topcostkey]['MOVESCORE_AFTER_IMPERFECT_MC'] = '-';
        $displayarray[$topcostkey]['WALKSCORE_AFTER_IMPERFECT_MC'] = '-';
        $displayarray[$topcostkey]['TOTSCORE_AFTER_IMPERFECT_MC'] = '-';
    }
//******END OF STEP 3*******
//STEP 4: Is there an imperfect grid5 in perfect MC
    if ($perfect_match_key === FALSE && $LEVEL_ONE_match_key === FALSE && $IMPERFECT_MC_key === FALSE) {
       include 'imperfect_grid5.php'; //call logic to find perfect swap location
    } else {
        $displayarray[$topcostkey]['MOVES_AFTER_IMP_GRID'] = '-';
        $displayarray[$topcostkey]['MOVESCORE_AFTER_IMP_GRID'] = '-';
        $displayarray[$topcostkey]['WALKSCORE_AFTER_IMP_GRID'] = '-';
        $displayarray[$topcostkey]['TOTSCORE_AFTER_IMP_GRID'] = '-';
    }
//******END OF STEP 4*******    

    if ($LEVEL_ONE_match_key === FALSE && $perfect_match_key === FALSE && $IMPERFECT_MC_key === FALSE && $IMPERFECT_GRID5_key === FALSE) { //could not find level 1 match
//Need to add logic for additional steps
//For now will just defaul to -
        $displayarray[$topcostkey]['AssgnGrid5'] = '-'; //NOT RIGHT!!!  CORRECT!!!
        $displayarray[$topcostkey]['AFTER_LEVEL1_SWAP_TOTCOST'] = '-';
        $displayarray[$topcostkey]['AFTER_IMPERFECT_MC_TOTCOST'] = '-';
        $displayarray[$topcostkey]['AFTER_IMPERFECT_GRID5_TOTCOST'] = '-';
        $displayarray[$topcostkey]['AFTER_LEVEL1_SWAP_TOTCOST'] = '-';
    }

//call recomendation logic
//    $finalrec = _reslotrecommendation($displayarray[$topcostkey]['CurrTotCost'], $displayarray[$topcostkey]['AFTER_MAX_INCREASE'], $displayarray[$topcostkey]['AFTER_PERF_GRID_TOTCOST'], $displayarray[$topcostkey]['AFTER_LEVEL1_SWAP_TOTCOST'], $displayarray[$topcostkey]['AFTER_IMPERFECT_MC_TOTCOST'], $settingscheck, $displayarray[$topcostkey]['AFTER_IMPERFECT_GRID5_TOTCOST']);
//define costs
    $CurrScoreTotal = $displayarray[$topcostkey]['SCORE_TOTALSCORE'];
    $CurrScoreWalk = $displayarray[$topcostkey]['SCORE_WALKSCORE'];
    $CurrScoreReplen = $displayarray[$topcostkey]['SCORE_REPLENSCORE'];
    $MaxScoreReplen = $displayarray[$topcostkey]['MOVESCORE_AFTER_MAX_INCREASE'];
    $PerfScoreTotal = $displayarray[$topcostkey]['TOTSCORE_AFTER_PERF_GRID'];
    $PerfScoreWalk = $displayarray[$topcostkey]['WALKSCORE_AFTER_PERF_GRID'];
    $PerfScoreReplen = $displayarray[$topcostkey]['MOVESCORE_AFTER_PERF_GRID'];
    $Level1ScoreTotal = $displayarray[$topcostkey]['TOTSCORE_AFTER_LEVEL1_SWAP'];
    $Level1ScoreWalk = $displayarray[$topcostkey]['WALKSCORE_AFTER_LEVEL1_SWAP'];
    $Level1ScoreReplen = $displayarray[$topcostkey]['MOVESCORE_AFTER_LEVEL1_SWAP'];
    $ImpGRID5ScoreTotal = $displayarray[$topcostkey]['TOTSCORE_AFTER_IMP_GRID'];
    $ImpGRID5ScoreWalk = $displayarray[$topcostkey]['WALKSCORE_AFTER_IMP_GRID'];
    $ImpGRID5ScoreReplen = $displayarray[$topcostkey]['MOVESCORE_AFTER_IMP_GRID'];
    $ImpMCScoreTotal = $displayarray[$topcostkey]['TOTSCORE_AFTER_IMPERFECT_MC'];
    $ImpMCScoreWalk = $displayarray[$topcostkey]['WALKSCORE_AFTER_IMPERFECT_MC'];
    $ImpMCScoreReplen = $displayarray[$topcostkey]['MOVESCORE_AFTER_IMPERFECT_MC'];


    $finalrec = _reslotrecommendation(abs($CurrScoreTotal), abs($CurrScoreWalk), abs($CurrScoreReplen), abs($MaxScoreReplen), abs($PerfScoreTotal), abs($PerfScoreWalk), abs($PerfScoreReplen), abs($Level1ScoreTotal), abs($Level1ScoreWalk), abs($Level1ScoreReplen), abs($ImpMCScoreTotal), abs($ImpMCScoreWalk), abs($ImpMCScoreReplen), abs($settingscheck), abs($ImpGRID5ScoreTotal), abs($ImpGRID5ScoreWalk), abs($ImpGRID5ScoreReplen));
    $displayarray[$topcostkey]['RecText'] = $finalrec['TEXT'];
    $displayarray[$topcostkey]['FinalSavings'] = $finalrec['CostSavingsTotal'];
    $displayarray[$topcostkey]['ReslotScenario'] = $finalrec['Scenario'];
    if (is_numeric($finalrec['CostSavingsTotal'])) {
        $avg_score_inc += $finalrec['CostSavingsTotal'];
    }
} //end of master loop

$avg_score_inc = $avg_score_inc / ($topcostkey + 1);
?>
<!--Look at customer returns page to toggle down for detailed info
Need to add right borders to main info to include other pertinent info-->


<div class="row"> 
    <div class="col-sm-12" style="padding-bottom: 5px;">
        <section class="panel">
            <header class="panel-heading bg bg-inverse h2"> Total Average Score Increase: <?php echo number_format($avg_score_inc * 100, 2) . '%' ?> </header>
            <?php
            foreach ($displayarray as $key2 => $value2) {
                //what is assigned task class?
                if (!is_null($displayarray[$key2]['ASSNTSM'])) {
                    $assnclass = ' recentcomment';
                } else {
                    $assnclass = '';
                }
                ?> 
                <div style="border-bottom: 3px solid #ccc;" class="<?php echo $assnclass ?>">
                    <div class="media" > 
                        <div class="row">
                            <div class="col-sm-3  text-center" style="padding-bottom: 5px;">
                                <div class="col-sm-12 h3" style="padding-bottom: 5px;">
                                    <a href="itemquery.php?itemnum=<?php echo $displayarray[$key2]['ITEM_NUMBER'] . '&userid=' . $var_userid; ?>" target="_blank"><?php echo $displayarray[$key2]['ITEM_NUMBER'] ?></a>
                                    <?php
                                    if (!is_null($displayarray[$key2]['ASSNTSM'])) {
                                        echo '<div class="h4">Assigned to: ' . strtoupper($displayarray[$key2]['ASSNTSM']) . '</div>';
                                    } else {
                                        ?>
                                        <i  id="<?php echo $displayarray[$key2]['ITEM_NUMBER']; ?>" class="fa fa-tasks addaction" style="cursor: pointer;margin-left: 5px;"data-toggle='tooltip' data-title='Assign Task' data-placement='top' data-container='body' ></i> 
                                    <?php } ?>
                                </div> 
                                <div class="col-sm-12 text-muted h5" style="padding-bottom: 0px;">Item Code</div>
                            </div>
                            <div class="col-sm-3  text-center" style="padding-bottom: 5px;">
                                <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo $displayarray[$key2]['CUR_LOCATION'] ?></div> 
                                <div class="col-sm-12 text-muted h5" style="padding-bottom: 0px;">Location</div>
                            </div>
                            <div class="col-sm-3  text-center" style="padding-bottom: 5px;">
                                <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo $displayarray[$key2]['RecText'] ?></div> 
                                <div class="col-sm-12 text-muted h5" style="padding-bottom: 0px;">Recommendation</div>
                            </div>
                            <div class="col-sm-3 text-center" style="padding-bottom: 5px;">
                                <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php
                                    if ($displayarray[$key2]['FinalSavings'] == 'N/A') {
                                        echo 'N/A';
                                    } else {
                                        echo number_format($displayarray[$key2]['FinalSavings'] * 100, 2) . '%';
                                    }
                                    ?><i class="fa fa-chevron-circle-down clicktotoggle-chevron-test" style="float: right; cursor: pointer;"></i></div> 
                                <div class="col-sm-12 text-muted h5" style="padding-bottom: 0px;">Score Increase</div>
                            </div>
                        </div>
                    </div> 


                    <!--Start of hidden data-->
                    <div class="hiddencostdetail" style="padding: 30px 0px; display: none;">
                        <section class="panel-body "> 
                            <article class="media borderedcontainer"> 
                                <div class="media-body"> 
                                    <div class="row">
                                        <div class="col-sm-4 bordered">
                                            <!--SECTION 1-->
                                            <?php include 'reslotdetailbynumber.php'; // include file to determine detail how to obtain cost savings based on returned number from $finalrecommendation array from the _reslotrecommendation in the slottingfunctions.php file                  ?>
                                        </div>
                                        <div class="col-sm-4 bordered">
                                            <!--SECTION 2--> 
                                            <?php include 'costsavingsdetailbynumber.php'; // include file to determine detail how to obtain cost savings based on returned number from $finalrecommendation array from the _reslotrecommendation in the slottingfunctions.php file                  ?>
                                        </div>
                                        <div class="col-sm-4 bordered"> 
                                            <!--SECTION 3-->
                                            <h3 class="sub-page-header">Slotting Detail</h3>
                                            <div class="row">
                                                <div class="col-md-6 bordered nopadding_bottom">
                                                    <div class="h5">Curr. Grid5: <strong><?php echo ' ' . $displayarray[$key2]['LMGRD5'] ?></strong></div>
                                                </div>
                                                <div class="col-md-6 bordered nopadding_bottom">
                                                    <div class="h5">Curr. Tier: <strong><?php echo ' ' . $displayarray[$key2]['LMTIER'] ?></strong></div>
                                                </div>
                                                <div class="col-md-6 bordered nopadding_bottom">
                                                    <div class="h5">Curr. Max: <strong><?php echo ' ' . $displayarray[$key2]['CURMAX'] ?></strong></div>
                                                </div>
                                                <div class="col-md-6 bordered nopadding_bottom">
                                                    <div class="h5">Avg Daily Qty: <strong><?php echo ' ' . number_format($displayarray[$key2]['AVG_DAILY_UNIT'], 1) ?></strong></div>
                                                </div>
                                                <div class="col-md-6 bordered nopadding_bottom">
                                                    <div class="h5">Imp Moves/Yr: <strong><?php echo ' ' . intval($displayarray[$key2]['CURRENT_IMPMOVES'] * 253) ?></strong></div>
                                                </div>
                                                <div class="col-md-6 bordered nopadding_bottom">
                                                    <div class="h5">Optimal Bay: <strong><?php echo ' ' . $displayarray[$key2]['OPT_OPTBAY'] ?></strong></div>
                                                </div>
                                                <div class="col-md-6 bordered nopadding_bottom">
                                                    <div class="h5">New Grid5: <strong><?php echo ' ' . $displayarray[$key2]['SUGGESTED_GRID5'] ?></strong></div>
                                                </div>
                                                <div class="col-md-6 bordered nopadding_bottom">
                                                    <div class="h5">New Tier: <strong><?php echo ' ' . $displayarray[$key2]['SUGGESTED_TIER'] ?></strong></div>
                                                </div>
                                                <div class="col-md-6 bordered nopadding_bottom">
                                                    <div class="h5">New Sugg. Max: <strong><?php echo ' ' . $displayarray[$key2]['SUGGESTED_MAX'] ?></strong></div>
                                                </div>
                                                <div class="col-md-6 bordered nopadding_bottom">
                                                    <div class="h5">Avg Daily Picks: <strong><?php echo ' ' . number_format($displayarray[$key2]['AVG_DAILY_PICK'], 1) ?></strong></div>
                                                </div>
                                                <div class="col-md-6 bordered nopadding_bottom">
                                                    <div class="h5">New Moves/Yr: <strong><?php echo ' ' . intval($displayarray[$key2]['SUGGESTED_IMPMOVES'] * 253) ?></strong></div>
                                                </div>
                                                <div class="col-md-6 bordered nopadding_bottom">
                                                    <div class="h5">Current Bay: <strong><?php echo ' ' . intval($displayarray[$key2]['OPT_CURRBAY']) ?></strong></div>
                                                </div>
                                            </div> 
                                        </div> 
                                    </div> 
                                </div> 
                            </article> 
                        </section>
                    </div>
                </div>
                <!--End of hidden data-->
            <?php } ?>
        </section>
    </div>
</div>
