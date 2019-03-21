<?php

$date = date('Y-m-d H:i:s');
echo $date . '<br>';
ini_set('max_execution_time', 999999);
ini_set('memory_limit', '-1');
ini_set('max_allowed_packet', '104857600');
//include_once '../globalincludes/google_connect.php';
include_once '../connection/NYServer.php';
include_once 'globalfunctions.php';
include_once '../globalfunctions/newitem.php';
include_once '../globalfunctions/slottingfunctions.php';



$bin_totalcube = $flow_totalcube = 0;
$totacubecounter = 0;
$prevtotcube = 0;
//what is capacity?
$usevol_sql = $conn1->prepare("SELECT 
                                                        SUM(CASE
                                                            WHEN LOC_DIM LIKE 'CL%' THEN (USE_CUBE * .85)
                                                            ELSE 0
                                                        END) AS cap_flow,
                                                        SUM(CASE
                                                            WHEN USE_DEPTH < 80 THEN (USE_CUBE * .85)
                                                            ELSE 0
                                                        END) AS cap_bb
                                                    FROM
                                                        gillingham.location_master
                                                            LEFT JOIN
                                                        gillingham.grid_exclusions ON exclude_grid = LOC_DIM
                                                    WHERE
                                                        exclude_grid IS NULL");
$usevol_sql->execute();
$usevol_array = $usevol_sql->fetchAll(pdo::FETCH_ASSOC);


$cap_flow = $usevol_array[0]['cap_flow'];
$cap_bb = $usevol_array[0]['cap_bb'];
//$cap_bb = 5000;
//$cap_flow = 113681;
do {

    //if all flow capacity is used, delete from available rpc_reductions and reload next grid table.
    if ($flow_totalcube > $cap_flow) {

        //delete flow from next grid
        $sqldelete3 = "DELETE FROM gillingham.nextgrid where nextgrid_loctype = 'FLOW'";
        $querydelete3 = $conn1->prepare($sqldelete3);
        $querydelete3->execute();

        //Delete flow records and records already used from the item tf table reduction table
        $sqldelete4 = "DELETE
                                    item_truefits.*
                                FROM
                                    gillingham.item_truefits
                                        LEFT JOIN
                                    gillingham.rpc_reductions ON itemtf_item = rpc_item
                                        AND itemtf_grid = rpc_grid
                                        WHERE (rpc_item is null or itemtf_loctype = 'FLOW')";
        $querydelete4 = $conn1->prepare($sqldelete4);
        $querydelete4->execute();
        
        //set min grid vol from the itemtf table as next grid (value of 2).  Note that current grids are in current grid table, value of 1

        //insert current grids record into the item tf table to determine replen reduction going to next grid
        $sqlinsert4 = "insert ignore into gillingham.item_truefits SELECT currgrid_item, currgrid_grid, currgrid_impmoves, currgrid_gridvol, 1, currgrid_rpc, currgrid_loctype FROM gillingham.currgrid";
        $queryinsert4 = $conn1->prepare($sqlinsert4);
        $queryinsert4->execute();
        
        //run replen reduction on item tf table again
        
        //truncate next grid table
        
        //update next grid table 
    }



//pull in top item based of next grid flag
    $sql_topitem = $conn1->prepare("SELECT 
                                    nextgrid_grid,
                                    nextgrid_rpc,
                                    nextgrid_loctype,
                                    nextgrid_rpcdecrease,
                                    nextgrid_item,
                                    nextgrid_impmoves,
                                    nextgrid_gridvol
                                FROM
                                    gillingham.nextgrid
                                ORDER BY nextgrid_rpcdecrease DESC
                                LIMIT 1");
    $sql_topitem->execute();
    $array_topitem = $sql_topitem->fetchAll(pdo::FETCH_ASSOC);

    $topitem = $array_topitem[0]['nextgrid_item'];


    //top item selected
    //update as current grid in currgrid table with top item
    $sqlinsert3 = "UPDATE gillingham.currgrid
                                                    JOIN
                                                gillingham.nextgrid ON currgrid_item = nextgrid_item 
                                            SET 
                                                currgrid_grid = nextgrid_grid,
                                                currgrid_rpc = nextgrid_rpc,
                                                currgrid_loctype = nextgrid_loctype,
                                                currgrid_rpcdecrease = nextgrid_rpcdecrease,
                                                currgrid_impmoves = nextgrid_impmoves,
                                                currgrid_gridvol = nextgrid_gridvol
                                            WHERE
                                                currgrid_item = $topitem";
    $queryinsert3 = $conn1->prepare($sqlinsert3);
    $queryinsert3->execute();

    //Pull in the next grid from the rpc_reductions table based off next smallest grid for target item

    $sql_nextgrid = $conn1->prepare("SELECT 
                                        rpc_grid,
                                        2 as rpc_nextgrid,
                                        rpc_rpc,
                                        rpc_loctype,
                                        rpc_rpcdecrease,
                                        rpc_impmoves,
                                        rpc_gridvol
                                    FROM
                                        gillingham.rpc_reductions
                                    WHERE
                                        rpc_item = $topitem
                                    ORDER BY rpc_gridvol ASC
                                    LIMIT 1");
    $sql_nextgrid->execute();
    $array_nextgrid = $sql_nextgrid->fetchAll(pdo::FETCH_ASSOC);




    if (count($array_nextgrid) > 0) {
        //if another grid is availble, delete record from nextgrid table
        $querydelete2 = $conn1->prepare("DELETE FROM gillingham.nextgrid where nextgrid_item = $topitem");
        $querydelete2->execute();

        //insert into next record from rpc_reductions table
        $queryinsert2 = $conn1->prepare("INSERT INTO gillingham.nextgrid SELECT 
                                            rpc_grid,
                                            2,
                                            rpc_rpc,
                                            rpc_loctype,
                                            rpc_rpcdecrease,
                                            rpc_item,
                                            rpc_impmoves,
                                            rpc_gridvol
                                        FROM
                                            gillingham.rpc_reductions
                                        WHERE
                                            rpc_item = 1000000
                                        ORDER BY rpc_gridvol ASC
                                        LIMIT 1");
        $queryinsert2->execute();

        //delete record from rpc_reductions table based of grid (min grid volume for item)
        $querydelete1 = $conn1->prepare("DELETE FROM gillingham.rpc_reductions 
                                        WHERE
                                            rpc_item = $topitem ORDER BY rpc_gridvol ASC LIMIT 1");
        $querydelete1->execute();
    } else {
        //else, delete item from nextgrid table
        $querydelete2 = $conn1->prepare("DELETE FROM gillingham.nextgrid where nextgrid_item = $topitem");
        $querydelete2->execute();

        //delete record from rpc_reductions table based of grid (min grid volume for item)
        $querydelete1 = $conn1->prepare("DELETE FROM gillingham.rpc_reductions 
                                        WHERE
                                            rpc_item = $topitem ORDER BY rpc_gridvol ASC LIMIT 1");
        $querydelete1->execute();
    }




//how much capacity is now used
    $sql_totalcap = $conn1->prepare("SELECT 
                                        SUM(CASE
                                            WHEN currgrid_loctype = 'BIN' THEN currgrid_gridvol
                                            ELSE 0
                                        END) AS bin_totalcube,
                                        SUM(CASE
                                            WHEN currgrid_loctype = 'FLOW' THEN currgrid_gridvol
                                            ELSE 0
                                        END) AS flow_totalcube,
                                        SUM(currgrid_impmoves) AS totalmoves
                                    FROM
                                        gillingham.currgrid");
    $sql_totalcap->execute();
    $array_totalcap = $sql_totalcap->fetchAll(pdo::FETCH_ASSOC);
    $bin_totalcube = $array_totalcap[0]['bin_totalcube'];
    $flow_totalcube = $array_totalcap[0]['flow_totalcube'];
    $totalmoves = $array_totalcap[0]['totalmoves'];
    $newcube = $bin_totalcube + $flow_totalcube;

    echo 'BINCUBE: ' . $bin_totalcube . ' | FLOWCUBE: ' . $flow_totalcube . ' | MOVES: ' . $totalmoves . '<br>';
} while (($bin_totalcube < $cap_bb) || ($flow_totalcube < $cap_flow));

$date = date('Y-m-d H:i:s');
echo $date . '<br>';
