<?php

ini_set('max_execution_time', 99999);
ini_set('memory_limit', '-1');
include_once '../../globalincludes/google_connect.php';
//include_once '../connection/NYServer.php';
include_once 'globalfunctions.php';
include_once '../globalfunctions/newitem.php';
include_once '../globalfunctions/slottingfunctions.php';

//what is capacity?
$usevol_sql = $conn1->prepare("SELECT 
                                SUM(USE_CUBE) AS avail_cube,
                                SUM(USE_CUBE) * .85 as cap_85
                            FROM
                                gillingham.location_master");
$usevol_sql->execute();
$usevol_array = $usevol_sql->fetchAll(pdo::FETCH_ASSOC);



//*********WARNING, WILL HAVE TO SPLIT THIS OUT FOR BLUE BINS AND FLOW RACK *************
$capacity = $usevol_array[0]['cap_85'] * .00500;
do {
//pull in top item based of next grid flag
    $sql_topitem = $conn1->prepare("SELECT 
                                        rpc_item, rpc_grid, rpc_nextgrid, rpc_rpcdecrease
                                    FROM
                                        gillingham.rpc_reductions
                                    WHERE
                                        rpc_item = (SELECT 
                                                rpc_item
                                            FROM
                                                gillingham.rpc_reductions
                                            WHERE
                                                rpc_nextgrid = 2
                                            ORDER BY rpc_rpcdecrease DESC
                                            LIMIT 1)
                                    ORDER BY rpc_gridvol ASC");
    $sql_topitem->execute();
    $array_topitem = $sql_topitem->fetchAll(pdo::FETCH_ASSOC);


    foreach ($array_topitem as $key => $value) {
        //loop until next grid = 2.  This is the next grid for this item to be upsized.
        if ($array_topitem[$key]['rpc_nextgrid'] <> 2) {
            continue;
        }
        //once nextgrid has been found, update the rpc_nextgrid to 1 to indicate now current grid
        //and next line update rpc_nextgrid to 2 for next line
        $item = intval($array_topitem[$key]['rpc_item']);
        $grid = $array_topitem[$key]['rpc_grid'];
        $item_next = intval($array_topitem[$key + 1]['rpc_item']);
        $grid_next = $array_topitem[$key + 1]['rpc_grid'];
        $item_prev = intval($array_topitem[$key - 1]['rpc_item']);
        $grid_prev = $array_topitem[$key - 1]['rpc_grid'];

        //sql updates
        $sqlupdate1 = "UPDATE gillingham.rpc_reductions SET rpc_nextgrid = 1 WHERE rpc_item = $item and rpc_grid = '$grid'";
        $queryupdate1 = $conn1->prepare($sqlupdate1);
        $queryupdate1->execute();

        //sql updates
        $sqlupdate2 = "UPDATE gillingham.rpc_reductions SET rpc_nextgrid = 2 WHERE rpc_item = $item_next and rpc_grid = '$grid_next'";
        $queryupdate2 = $conn1->prepare($sqlupdate2);
        $queryupdate2->execute();

        //sql updates
        $sqlupdate3 = "UPDATE gillingham.rpc_reductions SET rpc_nextgrid = 0 WHERE rpc_item = $item_prev and rpc_grid = '$grid_prev'";
        $queryupdate3 = $conn1->prepare($sqlupdate3);
        $queryupdate3->execute();

        break;
    }






//how much capacity is now used
//*********WARNING, WILL HAVE TO SPLIT THIS OUT FOR BLUE BINS AND FLOW RACK *************
    $sql_totalcap = $conn1->prepare("SELECT 
                                    SUM(rpc_gridvol) as totalcube,
                                    SUM(rpc_impmoves) as totalmoves
                                FROM
                                    gillingham.rpc_reductions
                                WHERE
                                    rpc_nextgrid = 1;");
    $sql_totalcap->execute();
    $array_totalcap = $sql_totalcap->fetchAll(pdo::FETCH_ASSOC);
    $totalcube = $array_totalcap[0]['totalcube'];
    $totalmoves = $array_totalcap[0]['totalmoves'];

    echo 'CUBE: ' . $totalcube . ' | MOVES: ' . $totalmoves . '<br>';
} while ($totalcube < $capacity);
