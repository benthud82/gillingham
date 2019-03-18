<?php

$date = date('Y-m-d H:i:s');
echo $date . '<br>';
ini_set('max_execution_time', 999999);
ini_set('memory_limit', '-1');
ini_set('max_allowed_packet', '104857600');
//include_once '../../globalincludes/google_connect.php';
include_once '../connection/NYServer.php';
include_once 'globalfunctions.php';
include_once '../globalfunctions/newitem.php';
include_once '../globalfunctions/slottingfunctions.php';
$bin_totalcube = $flow_totalcube = 0;
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
//$cap_flow = 5000;
do {
//pull in top item based of next grid flag
    $sql_topitem = $conn1->prepare("SELECT 
                                        rpc_item, rpc_grid, rpc_nextgrid, rpc_rpcdecrease, rpc_loctype
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

        //determine if capacity has been met for either bin or flow and update rpc_nextgrid to 0 for current record
        if (($bin_totalcube >= $cap_bb) && $array_topitem[$key]['rpc_loctype'] == 'BIN') {
            //bin capacity is full, set next grid to 0 and key+ 1 to new 2 then continue.  This does NOT change anything in the table, just the array
            $array_topitem[$key]['rpc_nextgrid'] = 0;
            $array_topitem[$key + 1]['rpc_nextgrid'] = 2;
            continue;
        }

        //determine if capacity has been met for either bin or flow and update rpc_nextgrid to 0 for current record
        if (($flow_totalcube >= $cap_flow) && $array_topitem[$key]['rpc_loctype'] == 'FLOW') {
            //flow capacity is full, set next grid to 0 and key+ 1 to new 2 then continue.  This does NOT change anything in the table, just the array
            $array_topitem[$key]['rpc_nextgrid'] = 0;
            //if there is a next grid set to "2" and continue, else break
            if (isset($array_topitem[$key + 1])) {
                $array_topitem[$key + 1]['rpc_nextgrid'] = 2;
                continue;
            } else {
                break;
            }
        }

        //once nextgrid has been found, update the rpc_nextgrid to 1 to indicate now current grid
        //and next line update rpc_nextgrid to 2 for next line
        $item = intval($array_topitem[$key]['rpc_item']);
        $grid = $array_topitem[$key]['rpc_grid'];
        //sql updates
        $sqlupdate3 = "UPDATE gillingham.rpc_reductions SET rpc_nextgrid = 0 WHERE rpc_item = $item";
        $queryupdate3 = $conn1->prepare($sqlupdate3);
        $queryupdate3->execute();
        //sql updates
        $sqlupdate1 = "UPDATE gillingham.rpc_reductions SET rpc_nextgrid = 1 WHERE rpc_item = $item and rpc_grid = '$grid'";
        $queryupdate1 = $conn1->prepare($sqlupdate1);
        $queryupdate1->execute();
        //sql updates
        if (isset($array_topitem[$key + 1]['rpc_grid'])) {
            $grid_next = $array_topitem[$key + 1]['rpc_grid'];
            $sqlupdate2 = "UPDATE gillingham.rpc_reductions SET rpc_nextgrid = 2 WHERE rpc_item = $item and rpc_grid = '$grid_next'";
            $queryupdate2 = $conn1->prepare($sqlupdate2);
            $queryupdate2->execute();
        }




        break;
    }






//how much capacity is now used
//*********WARNING, WILL HAVE TO SPLIT THIS OUT FOR BLUE BINS AND FLOW RACK *************
    $sql_totalcap = $conn1->prepare("SELECT 
                                                                SUM(CASE 
                                                                    WHEN rpc_loctype = 'BIN' THEN rpc_gridvol
                                                                    ELSE 0
                                                                END) AS bin_totalcube,
                                                                SUM(CASE
                                                                    WHEN rpc_loctype = 'FLOW' THEN rpc_gridvol
                                                                    ELSE 0
                                                                END) AS flow_totalcube,
                                                                SUM(rpc_impmoves) AS totalmoves
                                                            FROM
                                                                gillingham.rpc_reductions
                                                            WHERE
                                                                rpc_nextgrid = 1");
    $sql_totalcap->execute();
    $array_totalcap = $sql_totalcap->fetchAll(pdo::FETCH_ASSOC);
    $bin_totalcube = $array_totalcap[0]['bin_totalcube'];
    $flow_totalcube = $array_totalcap[0]['flow_totalcube'];
    $totalmoves = $array_totalcap[0]['totalmoves'];

    echo 'BINCUBE: ' . $bin_totalcube . ' | FLOWCUBE: ' . $flow_totalcube . ' | MOVES: ' . $totalmoves . '<br>';
} while (($bin_totalcube < $cap_bb) || ($flow_totalcube < $cap_flow));

$date = date('Y-m-d H:i:s');
echo $date . '<br>';
