<?php

//NO REPLEN DATA!!
ini_set('max_execution_time', 99999);
include_once '../connection/NYServer.php';
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
                                                    replengroup_date, replengroup_count / (INVLINES / 1000) as TOT_REPLENS
                                                FROM
                                                    gillingham.replen_grouped
                                                        JOIN
                                                    gillingham.invlinesshipped ON INVDATE = replengroup_date
                                                        AND INVCSLS = replengroup_type
                                                           LEFT JOIN
                                                    gillingham.excl_replenperthousand ON replengroup_date = replenexcl_date
                                                WHERE
                                                    replengroup_type = 'LSE'
                                                    AND replenexcl_date IS NULL
                                                        AND replengroup_date >= '$date'");
$result1->execute();



$rows = array();
$rows['name'] = 'Date';
$rows1 = array();
$rows1['name'] = 'Replens';



foreach ($result1 as $row) {
    $rows['data'][] = $row['replengroup_date'];
    $rows1['data'][] = ($row['TOT_REPLENS']) * 1.0;
}


$result = array();
array_push($result, $rows);
array_push($result, $rows1);


print json_encode($result);
