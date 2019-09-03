<?php

//creates table 

ini_set('max_execution_time', 99999);
ini_set('memory_limit', '-1');
//include_once '../globalincludes/google_connect.php';
//include_once '../../connections/conn_printvis.php';
include_once '../connection/NYServer.php';
include_once 'globalfunctions.php';
include_once '../globalfunctions/newitem.php';
include_once '../globalfunctions/slottingfunctions.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$array_itemtf = array();
$array_itemtf_ext = array();
//now truncated during itemtruefits_ecap.php
$truncatetables = array('item_truefits', 'rpc_reductions', 'currgrid', 'nextgrid', 'item_truefits_ext');
foreach ($truncatetables as $value) {
    $querydelete2 = $conn1->prepare("TRUNCATE gillingham.$value");
    $querydelete2->execute();
}

//assign full pallet items
include 'npfmvc_fullpallet.php';
$maxdaysoh = 200;

//smallest location to hold one unit of product
//this will be the starting point for moves per cubic inch
//pull in all "normal" items
$itemsql = $conn1->prepare("SELECT 
                                M.ITEM,
                                M.EA_DEPTH,
                                M.EA_HEIGHT,
                                M.EA_WIDTH,
                                (M.EA_DEPTH * M.EA_HEIGHT * M.EA_WIDTH) / 1000 AS ITEMCUBE,
                                D.AVG_DAILY_UNIT,
                                D.AVG_DAILY_PICK,
                                D.AVG_INVOH,
                                D.AVG_UNITS,
                                D.ADBS,
                                D.DSLS,
                                (SELECT 
                                        MIN(adbs_days)
                                    FROM
                                        gillingham.adbs_mindays
                                    WHERE
                                        D.ADBS >= adbs_adbs) AS DAYS_TO_STORE
                            FROM
                                gillingham.item_master M
                                    JOIN
                                gillingham.nptsld D ON D.ITEM = M.ITEM
                                 LEFT JOIN
                              gillingham.my_npfmvc F ON F.ITEM_NUMBER = M.ITEM
                            WHERE
                                LINE_TYPE IN ('ST' , 'SW') 
                             --   and D.AVG_DAILY_UNIT > 0
                                and PKTYPE = 'EA'
                                and F.ITEM_NUMBER IS NULL
              --                  and M.ITEM = 1111499
                                    AND CHAR_GROUP NOT IN ('D' , 'J', 'T')");
$itemsql->execute();
$itemarray = $itemsql->fetchAll(pdo::FETCH_ASSOC);


//pull in all grid sizes
$gridsql = $conn1->prepare("SELECT 
                                                        LOC_DIM,
                                                        USE_HEIGHT,
                                                        USE_DEPTH,
                                                        USE_WIDTH,
                                                        USE_CUBE,
                                                        TIER AS LOC_TYPE,
                                                        COUNT(*)
                                                    FROM
                                                        gillingham.location_master
                                                            LEFT JOIN
                                                        gillingham.grid_exclusions ON exclude_grid = LOC_DIM
                                                    WHERE
                                                        exclude_grid IS NULL
                                                            AND TIER IN ('FLOW' , 'BIN')
                                                    GROUP BY LOC_DIM , USE_HEIGHT , USE_DEPTH , USE_WIDTH , USE_CUBE , TIER
                                                    ORDER BY USE_CUBE ASC");
$gridsql->execute();
$gridarray = $gridsql->fetchAll(pdo::FETCH_ASSOC);

$array_keys = array_keys($gridarray);
$lastgrid_key = end($array_keys);

//loop through each item and assign the smallest grid to hold one unit
foreach ($itemarray as $key => $value) {
    //reset variables

    $implieddailymoves = 999;
    $grid5 = 'NOFIT';
    $truefit_tworound = 0;
    $nextgrid = intval(1);
    $rpc = '0';
    $previousTF = 0;
    $daysohcount = 0;
    $item = $itemarray[$key]['ITEM'];
    $ea_depth = $itemarray[$key]['EA_DEPTH'];
    if ($ea_depth == 0) {
        $ea_depth = 1;
    }
    $ea_height = $itemarray[$key]['EA_HEIGHT'];
    if ($ea_height == 0) {
        $ea_height = 1;
    }
    $ea_width = $itemarray[$key]['EA_WIDTH'];
    if ($ea_width == 0) {
        $ea_width = 1;
    }
    $ea_cube = $itemarray[$key]['ITEMCUBE'];
    $daystostore = $itemarray[$key]['DAYS_TO_STORE'];
    $DSLS = $itemarray[$key]['DSLS'];
    $daily_pick_qty = $itemarray[$key]['AVG_DAILY_PICK'];
    $adbs = $itemarray[$key]['ADBS'];
    $shipqtymn = $itemarray[$key]['AVG_UNITS'];
    $var_EachSLOTQTY = intval($daystostore * $shipqtymn);
    //loop trhough grids in ascending to order
    foreach ($gridarray as $key2 => $value) {
        //currrent grid 5
        $grid5 = $gridarray[$key2]['LOC_DIM'];
        $gridhigh = $gridarray[$key2]['USE_HEIGHT'];
        $griddeep = $gridarray[$key2]['USE_DEPTH'];
        $gridwide = $gridarray[$key2]['USE_WIDTH'];
        $gridcube = $gridarray[$key2]['USE_CUBE'];
        $gridtype = $gridarray[$key2]['LOC_TYPE'];

        //if cube of one unit is greater than cube of grid, then continue
        if ($ea_cube > $gridcube) {
            continue;
        }

        //if DSLS is greater than 30 then not a flow candidate
        if ($DSLS > 10 && $gridtype == 'FLOW') {
            continue;
        }

        //if pick qty <= .5 then not a flow candidate
        if ($daily_pick_qty <= .5 && $gridtype == 'FLOW') {
            continue;
        }

        //what is true fit of selected grid
        $truefitarray = _truefitgrid2iterations($grid5, $gridhigh, $griddeep, $gridwide, ' ', $ea_height, $ea_depth, $ea_width);
        //$truefitarray = _truefit($grid5, $gridhigh, $griddeep, $gridwide, ' ', $ea_height, $ea_depth, $ea_width, $var_EachSLOTQTY, 999);
        $truefit_tworound = $truefitarray[1];
        //if new tf is less than previous TF, continue
        if ($truefit_tworound <= ($previousTF * 1.1)) {
            if ($lastgrid_key !== $key2) {
                continue;
            }
        }
        $previousTF = $truefit_tworound;
        //test if true fit > slotquantity
        if ($truefit_tworound >= $var_EachSLOTQTY || $lastgrid_key == $key2) {
            //what is next grid size?
            //what is the implied daily moves at this TF
            $daily_ship_qty = $itemarray[$key]['AVG_DAILY_UNIT'];
            if ($daily_ship_qty == 0) {
                $daysoh = 1;
            } else {
                $daysoh = intval($truefit_tworound / $daily_ship_qty);
            }

            if ($daysoh >= $maxdaysoh && $daysohcount > 1) {
                break;
            }
            $daysohcount += 1;
            $daily_pick_qty = $itemarray[$key]['AVG_DAILY_PICK'];
            $avginv = $itemarray[$key]['AVG_INVOH'];


            $min = _minloc($truefit_tworound, $shipqtymn, 1);
            $implieddailymoves = number_format(_implied_daily_moves_nomin($truefit_tworound, $daily_ship_qty, $avginv), 4);
            $rpc = number_format(($implieddailymoves / $gridcube) * 1000, 6);

            //push to array
            $array_itemtf[] = "($item, '$grid5', '$implieddailymoves','$gridcube',$nextgrid, '$rpc', '$gridtype')";
            $array_itemtf_ext[] = "($item, '$grid5', '$implieddailymoves','$gridcube',$nextgrid, '$rpc', '$gridtype', '$griddeep', $truefit_tworound, $min, $truefit_tworound, '$gridcube', 1)";


            if ($nextgrid == 1) {
                $nextgrid = intval(2);
            } else {
                $nextgrid = intval(0);
            }

            if ($implieddailymoves == '0.0000') {
                break;
            }
        }
    }
    $columns_itemtf = 'itemtf_item, itemtf_grid, itemtf_impmoves, itemtf_gridvol, itemtf_nextgrid, itemtf_rpc, itemtf_loctype';
    $columns_itemtf_ext = 'itemtf_item, itemtf_grid, itemtf_impmoves, itemtf_gridvol, itemtf_nextgrid, itemtf_rpc, itemtf_loctype, itemtf_griddep, itemtf_max, itemtf_min, itemtf_slotqty, itemtf_locvol, itemtf_daystostock';
}

$values = implode(',', $array_itemtf);
$sql = "INSERT IGNORE INTO gillingham.item_truefits ($columns_itemtf) VALUES $values";
$query = $conn1->prepare($sql);
$query->execute();

$values2 = implode(',', $array_itemtf_ext);
$sql2 = "INSERT IGNORE INTO gillingham.item_truefits_ext ($columns_itemtf_ext) VALUES $values2";
$query2 = $conn1->prepare($sql2);
$query2->execute();

//insert the replen reduction per increase in cube to table gillingham.rpc_reductions
$sqlinsert = "INSERT IGNORE INTO  gillingham.rpc_reductions (SELECT 
                            TF.itemtf_grid,
                            TF.itemtf_nextgrid,
                            TF.itemtf_rpc,
                            TF.itemtf_loctype,
                            IF(@lastitem = TF.itemtf_item,
                                (@lastimpmove - TF.itemtf_impmoves) / (TF.itemtf_gridvol - @lastgridvol),
                          --      (@lastimpmove - TF.itemtf_impmoves),
                                0000.00) AS decrease_rpc,
                            @lastitem:=TF.itemtf_item,
                            @lastimpmove:=TF.itemtf_impmoves,
                            @lastgridvol:=TF.itemtf_gridvol
                        FROM
                            gillingham.item_truefits TF,
                            (SELECT @lastitem:=0, @lastimpmove:=0, @lastgridvol:=0) SQLVars
                        ORDER BY itemtf_item ASC , itemtf_gridvol ASC)
                        ";
$queryinsert = $conn1->prepare($sqlinsert);
$queryinsert->execute();

//update the currgrid and nextgrid tables
$sqlinsert2 = "INSERT IGNORE INTO gillingham.currgrid (currgrid_grid, currgrid_nextgrid, currgrid_rpc, currgrid_loctype, currgrid_rpcdecrease, currgrid_item, currgrid_impmoves, currgrid_gridvol)
                            SELECT * FROM
                                gillingham.rpc_reductions
                            WHERE
                                rpc_nextgrid = 1";
$queryinsert2 = $conn1->prepare($sqlinsert2);
$queryinsert2->execute();

$sqlinsert3 = "INSERT IGNORE INTO gillingham.nextgrid (nextgrid_grid, nextgrid_nextgrid, nextgrid_rpc, nextgrid_loctype, nextgrid_rpcdecrease, nextgrid_item, nextgrid_impmoves, nextgrid_gridvol)
                            SELECT * FROM
                                gillingham.rpc_reductions
                            WHERE
                                rpc_nextgrid = 2";
$queryinsert3 = $conn1->prepare($sqlinsert3);
$queryinsert3->execute();

//delete any items ranked as 1 or 2 from rpc_reductions as these are in the currgrid or nextgrid table
$querydelete2 = $conn1->prepare("DELETE FROM gillingham.rpc_reductions WHERE rpc_nextgrid in (1,2)");
$querydelete2->execute();
