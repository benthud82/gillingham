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

$truncatetables = array('inventory_restricted', 'my_npfmvc', 'item_truefits', 'rpc_reductions', 'currgrid', 'nextgrid', 'item_truefits_ext');
foreach ($truncatetables as $value) {
    $querydelete2 = $conn1->prepare("TRUNCATE gillingham.$value");
    $querydelete2->execute();
}


//now called during itemtruefits.php
//include 'npfmvc_fullpallet.php';
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
  --                              and M.ITEM = 1039246
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
                                                            AND TIER IN ('ECAP')
                                                    GROUP BY LOC_DIM , USE_HEIGHT , USE_DEPTH , USE_WIDTH , USE_CUBE , TIER
                                                    ORDER BY USE_CUBE ASC");
$gridsql->execute();
$gridarray = $gridsql->fetchAll(pdo::FETCH_ASSOC);

//loop through each item and assign the smallest grid to hold one unit
foreach ($itemarray as $key => $value) {
    //reset variables
    //$array_itemtf = array();
    $implieddailymoves = 999;
    $grid5 = 'NOFIT';
    $truefit_tworound = 0;
    $nextgrid = 1;
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

        //what is true fit of selected grid
        $truefitarray = _truefitgrid2iterations($grid5, $gridhigh, $griddeep, $gridwide, ' ', $ea_height, $ea_depth, $ea_width);
        //$truefitarray = _truefit($grid5, $gridhigh, $griddeep, $gridwide, ' ', $ea_height, $ea_depth, $ea_width, $var_EachSLOTQTY, 999);
        $truefit_tworound = $truefitarray[1];
        //if new tf is less than previous TF, continue
        if ($truefit_tworound <= ($previousTF * 1.1)) {
            continue;
        }
        $previousTF = $truefit_tworound;
        //test if true fit > slotquantity
        if ($truefit_tworound >= $var_EachSLOTQTY) {
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
            $rpc = ($implieddailymoves / $gridcube) * 1000;

            //push to array
            $array_itemtf[] = "($item, '$grid5', '$implieddailymoves','$gridcube',$nextgrid, '$rpc', '$gridtype')";
            $array_itemtf_ext[] = "($item, '$grid5', '$implieddailymoves','$gridcube',$nextgrid, '$rpc', '$gridtype', '$griddeep', $truefit_tworound, $min, $truefit_tworound, '$gridcube', 1)";


            if ($nextgrid == 1) {
                $nextgrid = 2;
            } else {
                $nextgrid = 0;
            }

            if ($implieddailymoves == '0.0000') {
                break;
            }
        }
    }
    $columns_itemtf = 'itemtf_item, itemtf_grid, itemtf_impmoves, itemtf_gridvol, itemtf_nextgrid, itemtf_rpc, itemtf_loctype';
    $columns_itemtf_ext = 'itemtf_item, itemtf_grid, itemtf_impmoves, itemtf_gridvol, itemtf_nextgrid, itemtf_rpc, itemtf_loctype, itemtf_griddep, itemtf_max, itemtf_min, itemtf_slotqty, itemtf_locvol, itemtf_daystostock';
}


$values2 = implode(',', $array_itemtf_ext);
$sql2 = "INSERT IGNORE INTO gillingham.item_truefits_ext ($columns_itemtf_ext) VALUES $values2";
$query2 = $conn1->prepare($sql2);
$query2->execute();

//delete from tables the duplicates to only include the grid5 with the lowest replen per cuble
$sql3 = "DELETE FROM gillingham.item_truefits_ext 
 WHERE itemtf_rpc NOT IN (SELECT * 
                    FROM (SELECT MIN(n.itemtf_rpc)
                            FROM gillingham.item_truefits_ext  n
                        GROUP BY n.itemtf_item) x)";
$query3= $conn1->prepare($sql3);
$query3->execute();


//available ECAP locations
$sql = $conn1->prepare("SELECT 
                                                LOC_DIM, COUNT(*) as DIMCOUNT
                                            FROM
                                                gillingham.location_master
                                                    LEFT JOIN
                                                gillingham.grid_exclusions ON exclude_grid = LOC_DIM
                                            WHERE
                                                exclude_grid IS NULL
                                                    AND TIER IN ('ECAP')
                                            GROUP BY LOC_DIM
                                            ORDER BY USE_CUBE ASC");
$sql->execute();
$gridarray_ecap = $sql->fetchAll(pdo::FETCH_ASSOC);

foreach ($gridarray_ecap as $key => $value) {
    $grid5  = $gridarray_ecap[$key]['LOC_DIM'];
    $grid5_count  = $gridarray_ecap[$key]['DIMCOUNT'];
    
    //pull in items with no implied moves ordered by PPC desc, limit on the number of available locations
    
    $sqlinsert1 = "insert into gillingham.my_npfmvc (
        SELECT DISTINCT
    'GB00001' AS WAREHOUSE,
    A.ITEM AS ITEM_NUMBER,
    A.PKGU AS PACKAGE_UNIT,
    A.PKTYPE AS PACKAGE_TYPE,
    D.slotmaster_loc AS LMLOC,
    A.DSLS AS DAYS_FRM_SLE,
    A.ADBS AS AVGD_BTW_SLE,
    A.AVG_INVOH AS AVG_INV_OH,
    A.DAYCOUNT AS NBR_SHIP_OCC,
    A.AVG_PICK AS PICK_QTY_MN,
    A.PICK_STD AS PICK_QTY_SD,
    A.AVG_UNITS AS SHIP_QTY_MN,
    A.UNIT_STD AS SHIP_QTY_SD,
    X.PKGU_EA AS CPCEPKU,
    X.PKGU_CA AS CPCCPKU,
    'Y' AS CPCFLOW,
    'Y' AS CPCTOTE,
    'Y' AS CPCSHLF,
    'Y' AS CPCROTA,
    0 AS CPCESTK,
    ' ' AS CPCLIQU,
    X.EA_DEPTH AS CPCELEN,
    X.EA_HEIGHT AS CPCEHEI,
    X.EA_WIDTH AS CPCEWID,
    X.CA_DEPTH AS CPCCLEN,
    X.CA_HEIGHT AS CPCCHEI,
    X.CA_WIDTH AS CPCCWID,
    D.slotmaster_usehigh AS LMHIGH,
    D.slotmaster_usedeep AS LMDEEP,
    D.slotmaster_usewide AS LMWIDE,
    D.slotmaster_usecube AS LMVOL9,
    D.slotmaster_tier AS LMTIER,
    D.slotmaster_dimgroup AS LMGRD5,
    CASE
        WHEN X.EA_DEPTH * X.EA_HEIGHT * X.EA_WIDTH > 0 THEN (A.AVG_DAILY_UNIT * X.EA_DEPTH * X.EA_HEIGHT * X.EA_WIDTH)
        ELSE (A.AVG_DAILY_UNIT) * X.CA_DEPTH * X.CA_HEIGHT * X.CA_WIDTH / X.PKGU_CA
    END AS DLY_CUBE_VEL,
    CASE
        WHEN X.EA_DEPTH * X.EA_HEIGHT * X.EA_WIDTH > 0 THEN (A.AVG_DAILY_PICK) * X.EA_DEPTH * X.EA_HEIGHT * X.EA_WIDTH
        ELSE (A.AVG_DAILY_PICK) * X.CA_DEPTH * X.CA_HEIGHT * X.CA_WIDTH
    END AS DLY_PICK_VEL,
    itemtf_loctype,
    itemtf_grid,
    itemtf_griddep,
    itemtf_max,
    itemtf_min,
    itemtf_slotqty,
    itemtf_impmoves,
    slotmaster_impmoves AS CURRENT_IMPMOVES,
    itemtf_locvol,
    itemtf_daystostock,
    A.AVG_DAILY_PICK AS DAILYPICK,
    A.AVG_DAILY_UNIT AS DAILYUNIT,
    0 AS JAX_ENDCAP,
    A.AVG_DAILY_PICK / itemtf_locvol * 1000,
    slotmaster_currtf AS VCCTRF
FROM
    gillingham.nptsld A
        JOIN
    gillingham.item_master X ON X.ITEM = A.ITEM
        JOIN
    gillingham.slotmaster D ON D.slotmaster_item = A.ITEM
        JOIN
    gillingham.item_truefits_ext ON A.ITEM = itemtf_item
        LEFT JOIN
    gillingham.my_npfmvc F ON F.ITEM_NUMBER = A.ITEM
WHERE
    F.ITEM_NUMBER IS NULL
        AND D.slotmaster_pkgu = 'EA'
        AND A.PKTYPE = 'EA'
        AND CHAR_GROUP NOT IN ('D' , 'J', 'T')
        AND itemtf_grid = '$grid5'
ORDER BY itemtf_impmoves ASC , AVG_DAILY_PICK / itemtf_locvol * 1000 DESC
LIMIT $grid5_count)";
$queryinsert1 = $conn1->prepare($sqlinsert1);
$queryinsert1->execute();
       
    
}

$truncatetables = array( 'item_truefits', 'rpc_reductions', 'currgrid', 'nextgrid', 'item_truefits_ext');
foreach ($truncatetables as $value) {
    $querydelete2 = $conn1->prepare("TRUNCATE gillingham.$value");
    $querydelete2->execute();
}




