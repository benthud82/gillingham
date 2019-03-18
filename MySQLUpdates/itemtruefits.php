<?php

//creates table 

ini_set('max_execution_time', 99999);
ini_set('memory_limit', '-1');
include_once '../../globalincludes/google_connect.php';
//include_once '../connection/NYServer.php';
include_once 'globalfunctions.php';
include_once '../globalfunctions/newitem.php';
include_once '../globalfunctions/slottingfunctions.php';


$sqldelete3 = "TRUNCATE gillingham.item_truefits";
$querydelete3 = $conn1->prepare($sqldelete3);
$querydelete3->execute();

$sqldelete4 = "TRUNCATE gillingham.rpc_reductions";
$querydelete4 = $conn1->prepare($sqldelete4);
$querydelete4->execute();

//smallest location to hold one unit of product
//this will be the starting point for moves per cubic inch
//pull in all "normal" items
$itemsql = $conn1->prepare("SELECT 
                                M.ITEM,
                                M.EA_DEPTH,
                                M.EA_HEIGHT,
                                M.EA_WIDTH,
                                (M.EA_DEPTH * M.EA_HEIGHT * M.EA_WIDTH) / 1000 AS ITEMCUBE,
                                AVG_DAILY_UNIT,
                                AVG_DAILY_PICK,
                                AVG_INVOH,
                                AVG_UNITS,
                                ADBS,
                                (SELECT 
                                        MIN(adbs_days)
                                    FROM
                                        gillingham.adbs_mindays
                                    WHERE
                                        ADBS >= adbs_adbs) AS DAYS_TO_STORE
                            FROM
                                gillingham.item_master M
                                    JOIN
                                gillingham.nptsld D ON D.ITEM = M.ITEM
                            WHERE
                                LINE_TYPE IN ('ST' , 'SW')
                                    AND CHAR_GROUP NOT IN ('D' , 'J', 'T')");
$itemsql->execute();
$itemarray = $itemsql->fetchAll(pdo::FETCH_ASSOC);


//pull in all grid sizes
$gridsql = $conn1->prepare("SELECT 
                                                    slotmaster_dimgroup,
                                                    slotmaster_usehigh,
                                                    slotmaster_usedeep,
                                                    slotmaster_usewide,
                                                    slotmaster_usecube,
                                                    CASE
                                                        WHEN slotmaster_dimgroup LIKE 'CL%' THEN 'FLOW'
                                                        WHEN slotmaster_usedeep < 80 THEN 'BIN'
                                                        ELSE 'PALL'
                                                    END AS LOC_TYPE,
                                                    COUNT(*)
                                                FROM
                                                    gillingham.slotmaster
                                                        LEFT JOIN
                                                        gillingham.grid_exclusions ON exclude_grid = slotmaster_dimgroup
                                                WHERE
                                                    exclude_grid IS NULL
                                                GROUP BY slotmaster_dimgroup , slotmaster_usehigh , slotmaster_usedeep , slotmaster_usewide , slotmaster_usecube , CASE
                                                    WHEN slotmaster_dimgroup LIKE 'CL%' THEN 'FLOW'
                                                    WHEN slotmaster_usedeep < 80 THEN 'BIN'
                                                    ELSE 'PALL'
                                                END
                                                ORDER BY slotmaster_usecube ASC");
$gridsql->execute();
$gridarray = $gridsql->fetchAll(pdo::FETCH_ASSOC);

//loop through each item and assign the smallest grid to hold one unit
foreach ($itemarray as $key => $value) {
    //reset variables
    $array_itemtf = array();
    $implieddailymoves = 999;
    $grid5 = 'NOFIT';
    $truefit_tworound = 0;
    $nextgrid = 1;
    $rpc = '0';
    $previousTF = 0;
    $item = $itemarray[$key]['ITEM'];
    $ea_depth = $itemarray[$key]['EA_DEPTH'];
    $ea_height = $itemarray[$key]['EA_HEIGHT'];
    $ea_width = $itemarray[$key]['EA_WIDTH'];
    $ea_cube = $itemarray[$key]['ITEMCUBE'];
    $daystostore = $itemarray[$key]['DAYS_TO_STORE'];
    $adbs = $itemarray[$key]['ADBS'];
    $shipqtymn = $itemarray[$key]['AVG_UNITS'];
    $var_EachSLOTQTY = intval($daystostore * $shipqtymn);
    //loop trhough grids in ascending to order
    foreach ($gridarray as $key2 => $value) {
        //currrent grid 5
        $grid5 = $gridarray[$key2]['slotmaster_dimgroup'];
        $gridhigh = $gridarray[$key2]['slotmaster_usehigh'];
        $griddeep = $gridarray[$key2]['slotmaster_usedeep'];
        $gridwide = $gridarray[$key2]['slotmaster_usewide'];
        $gridcube = $gridarray[$key2]['slotmaster_usecube'];
        $gridtype = $gridarray[$key2]['LOC_TYPE'];

        //if cube of one unit is greater than cube of grid, then continue
        if ($ea_cube > $gridcube) {
            continue;
        }

        //what is true fit of selected grid
        $truefitarray = _truefitgrid2iterations($grid5, $gridhigh, $griddeep, $gridwide, ' ', $ea_height, $ea_depth, $ea_width);
        //$truefitarray = _truefit($grid5, $gridhigh, $griddeep, $gridwide, ' ', $ea_height, $ea_depth, $ea_width, $var_EachSLOTQTY, 999);
        $truefit_tworound = $truefitarray[1];
        //if new tf is less than previous TF, continue
        if ($truefit_tworound <= $previousTF) {
            continue;
        }
        $previousTF = $truefit_tworound;
        //test if true fit > slotquantity
        if ($truefit_tworound > $var_EachSLOTQTY) {
            //what is next grid size?
            //what is the implied daily moves at this TF
            $daily_ship_qty = $itemarray[$key]['AVG_DAILY_UNIT'];
            $daily_pick_qty = $itemarray[$key]['AVG_DAILY_PICK'];
            $avginv = $itemarray[$key]['AVG_INVOH'];


            $min = _minloc($truefit_tworound, $shipqtymn, 1);
            $implieddailymoves = number_format(_implied_daily_moves_nomin($truefit_tworound, $daily_ship_qty, $avginv), 2);
            $rpc = ($implieddailymoves / $gridcube) * 1000;

            //push to array
            $array_itemtf[] = "($item, '$grid5', '$implieddailymoves','$gridcube',$nextgrid, '$rpc', '$gridtype')";
            if (count($array_itemtf) == 1) {
                $nextgrid = 2;
            } else {
                $nextgrid = 0;
            }

            if ($implieddailymoves == '0.00') {
                break;
            }
        }
    }
    $columns_itemtf = 'itemtf_item, itemtf_grid, itemtf_impmoves, itemtf_gridvol, itemtf_nextgrid, itemtf_rpc, itemtf_loctype';
//after looping through all items, write to smallest_grid table
    if (count($array_itemtf) == 0) {
        $array_itemtf[] = "($item, 'NOFIT', '1','4512',0, '0', 'NOFIT' )";
    }
    $values = implode(',', $array_itemtf);
    $sql = "INSERT IGNORE INTO gillingham.item_truefits ($columns_itemtf) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();
}

//insert the replen reduction per increase in cube to table gillingham.rpc_reductions
$sqlinsert = "INSERT INTO gillingham.rpc_reductions SELECT 
                            TF.itemtf_grid,
                            TF.itemtf_nextgrid,
                            TF.itemtf_rpc,
                            TF.itemtf_loctype,
                            IF(@lastitem = TF.itemtf_item,
                                (@lastimpmove - TF.itemtf_impmoves) / (TF.itemtf_gridvol - @lastgridvol),
                                0000.00) AS decrease_rpc,
                            @lastitem:=TF.itemtf_item,
                            @lastimpmove:=TF.itemtf_impmoves,
                            @lastgridvol:=TF.itemtf_gridvol
                        FROM
                            gillingham.item_truefits TF,
                            (SELECT @lastitem:=0, @lastimpmove:=0, @lastgridvol:=0) SQLVars
                        ORDER BY itemtf_item ASC , itemtf_gridvol ASC
                        ";
$queryinsert = $conn1->prepare($sqlinsert);
$queryinsert->execute();

