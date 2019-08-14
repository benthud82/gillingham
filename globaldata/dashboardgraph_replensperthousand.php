<?php

ini_set('max_execution_time', 99999);
include_once '../connection/NYServer.php';
include '../../globalfunctions/custdbfunctions.php';
$var_userid = $_GET['userid'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from slotting.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];
$table = ($var_whse) . 'invlinesshipped';
$table2 = ($var_whse) . 'dailymovecount';


$time = strtotime("-1 year", time());
$date = date("Y-m-d", $time);

$result1 = $conn1->prepare("SELECT 
                                                            @curRank:=@curRank + 1 AS rank,
                                                            replengroup_date,
                                                            replengroup_count / (INVLINES / 1000) AS TOT_REPLENS
                                                        FROM
                                                            gillingham.replen_grouped
                                                                JOIN
                                                            gillingham.invlinesshipped ON INVDATE = replengroup_date
                                                                AND INVCSLS = replengroup_type
                                                                LEFT JOIN
                                                            gillingham.excl_replenperthousand ON replengroup_date = replenexcl_date
                                                                JOIN
                                                            (SELECT @curRank:=0) AS init
                                                        WHERE
                                                            replengroup_type = 'LSE'
                                                                AND replenexcl_date IS NULL
                                                                AND replengroup_date >= '$date'
                                                        ORDER BY replengroup_date");
$result1->execute();
$scorearray = $result1->fetchAll(pdo::FETCH_ASSOC);
$rankarray = array_column($scorearray, 'rank');
$totalscorearray = array_column($scorearray, 'TOT_REPLENS');
$totalscoretrend = linear_regression($rankarray, $totalscorearray);

$rows = array();
$rows['name'] = 'Date';
$rows1 = array();
$rows1['name'] = 'Replens';
$rows2 = array();
$rows2['name'] = 'Trend Line';


foreach ($scorearray as $row) {
    $rows['data'][] = $row['replengroup_date'];
    $rows1['data'][] = ($row['TOT_REPLENS']) * 1.0;
}
$startx = 1;
$starty = $totalscoretrend['b'];
$endx = sizeof($totalscorearray);
$endy = $totalscoretrend['b'] + ($totalscoretrend['m'] *$endx) ;

$rows2['data'][] = [$startx, $starty];
$rows2['data'][] = [$endx, $endy];

$result = array();
array_push($result, $rows);
array_push($result, $rows1);
array_push($result, $rows2);

print json_encode($result);
