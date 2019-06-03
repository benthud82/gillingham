
<?php

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
ini_set('max_execution_time', 99999);

include_once '../connection/NYServer.php';

$userid = strtoupper($_GET['userid']);
$baynum = intval($_GET['baynum']);
$tiersel = ($_GET['tiersel']);

$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);
$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];


//pull in distict grids either used or suggested
$distinctgridsql = $conn1->prepare("SELECT DISTINCT
                                                                        GRID5_DEP, LMVOL9
                                                                    FROM
                                                                        (SELECT 
                                                                            SUGGESTED_GRID5 AS GRID5_DEP, SUGGESTED_NEWLOCVOL AS LMVOL9
                                                                        FROM
                                                                            gillingham.my_npfmvc M
                                                                        JOIN gillingham.optimalbay ON OPT_ITEM = ITEM_NUMBER
                                                                            AND OPT_PKGU = PACKAGE_UNIT
                                                                        WHERE
                                                                            SUGGESTED_TIER = '$tiersel'
                                                                                AND OPT_OPTBAY = $baynum UNION SELECT DISTINCT
                                                                            LOC_DIM AS GRID5_DEP, CUBE AS LMVOL9
                                                                        FROM
                                                                            gillingham.location_master L
                                                                        JOIN gillingham.bay_location X ON L.LOCATION = X.LOCATION
                                                                        WHERE
                                                                            LOC_DIM <> ' ' AND WALKBAY = $baynum
                                                                                AND L.TIER = '$tiersel') T
                                                                    ORDER BY LMVOL9");
$distinctgridsql->execute();
$distinctgridarray = $distinctgridsql->fetchAll(pdo::FETCH_ASSOC);

//pull in suggested count by grid5_dep
$suggestedgridsql = $conn1->prepare("SELECT 
                                                                        SUGGESTED_GRID5 AS SUG_GRID5_DEP, COUNT(*) AS SUG_COUNT
                                                                    FROM
                                                                        gillingham.my_npfmvc
                                                                            JOIN
                                                                        gillingham.optimalbay ON OPT_ITEM = ITEM_NUMBER
                                                                            AND OPT_PKGU = PACKAGE_UNIT
                                                                    WHERE
                                                                        SUGGESTED_TIER = '$tiersel'
                                                                            AND OPT_OPTBAY = $baynum
                                                                    GROUP BY CONCAT(SUGGESTED_GRID5, SUGGESTED_DEPTH)
                                                                    ORDER BY SUGGESTED_NEWLOCVOL ASC");
$suggestedgridsql->execute();
$suggestedgridarray = $suggestedgridsql->fetchAll(pdo::FETCH_ASSOC);

//pull in current count by grid5_dep
$currentgridsql = $conn1->prepare("SELECT 
                                                                    LOC_DIM AS CUR_GRID5_DEP, COUNT(*) AS CUR_COUNT
                                                                FROM
                                                                    gillingham.location_master L
                                                                        JOIN
                                                                    gillingham.bay_location X ON L.LOCATION = X.LOCATION
                                                                WHERE
                                                                   WALKBAY = $baynum
                                                                        AND TIER = '$tiersel'
                                                                GROUP BY LOC_DIM
                                                                ORDER BY USE_CUBE;");
$currentgridsql->execute();
$currentgridarray = $currentgridsql->fetchAll(pdo::FETCH_ASSOC);

$output = array(
    "aaData" => array()
);
$row = array();


//join all three arrays for complete needs wants table
foreach ($distinctgridarray as $key => $value) {
    $grid5_dep = $distinctgridarray[$key]['GRID5_DEP'];
    //find if grid5 is in suggtested array
    $suggestedkey = array_search($grid5_dep, array_column($suggestedgridarray, 'SUG_GRID5_DEP'));
    if ($suggestedkey !== FALSE) {
        $distinctgridarray[$key]['SUG_COUNT'] = intval($suggestedgridarray[$suggestedkey]['SUG_COUNT']);
    } else {
        $distinctgridarray[$key]['SUG_COUNT'] = 0;
    }


    //find if grid5 is in current array
    $currentkey = array_search($grid5_dep, array_column($currentgridarray, 'CUR_GRID5_DEP'));
    if ($currentkey !== FALSE) {
        $distinctgridarray[$key]['CUR_COUNT'] = intval($currentgridarray[$currentkey]['CUR_COUNT']);
    } else {
        $distinctgridarray[$key]['CUR_COUNT'] = 0;
    }

    //push count +/-
    $distinctgridarray[$key]['PLUS_MINUS_COUNT'] = intval($distinctgridarray[$key]['CUR_COUNT']) - intval($distinctgridarray[$key]['SUG_COUNT']);

    //push volume +/-
$distinctgridarray[$key]['PLUS_MINUS_VOL'] = ($distinctgridarray[$key]['LMVOL9']) * (intval($distinctgridarray[$key]['CUR_COUNT']) - intval($distinctgridarray[$key]['SUG_COUNT']));

    $row[] = array_values($distinctgridarray[$key]);
}

$output['aaData'] = $row;
echo json_encode($output);
