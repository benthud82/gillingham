<?php

include_once '../sessioninclude.php';
include_once '../connection/NYServer.php';
include_once '../../globalfunctions/custdbfunctions.php';

ini_set('max_execution_time', 99999);
$var_userid = strtoupper($_SESSION['MYUSER']);
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE UPPER(idslottingDB_users_ID) = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);
$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];


$startdate = date('Y-m-d', strtotime($_GET['startdate']));
$enddate = date('Y-m-d', strtotime($_GET['enddate']));


$movedata = $conn1->prepare("SELECT 
    replen_item,
    replen_fromloc,
    replen_toloc,
    replen_zone,
    replen_code,
    replen_date,
    replen_qty
FROM
    gillingham.replen
WHERE
 replen_toloc < '69*'
 AND   replen_date BETWEEN '$startdate' AND '$enddate'");
$movedata->execute();
$movedata_array = $movedata->fetchAll(pdo::FETCH_ASSOC);



$output = array(
    "aaData" => array()
);
$row = array();

foreach ($movedata_array as $key => $value) {
    $row[] = array_values($movedata_array[$key]);
}


$output['aaData'] = $row;
echo json_encode($output);
