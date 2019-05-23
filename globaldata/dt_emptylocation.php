
<?php

ini_set('max_execution_time', 99999);
include_once '../connection/NYServer.php';

$var_userid = $_GET['userid'];
$var_tier = $_GET['tier'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

$var_whse = intval($whssqlarray[0]['slottingDB_users_PRIMDC']);

$emptylocsql = $conn1->prepare("SELECT 
    LOCATION,
    TIER,
    LOC_DIM,
    PICK_ZONE,
    USE_HEIGHT,
    USE_DEPTH,
    USE_WIDTH,
    USE_CUBE
FROM
    gillingham.emptylocations
        JOIN
    gillingham.location_master ON emptylocation = LOCATION
WHERE
    ALLOW_PICK = 'Y'
    and TIER = '$var_tier'");
$emptylocsql->execute();
$emptylocarray = $emptylocsql->fetchAll(pdo::FETCH_ASSOC);


$output = array(
    "aaData" => array()
);
$row = array();

foreach ($emptylocarray as $key => $value) {
    $row[] = array_values($emptylocarray[$key]);
}


$output['aaData'] = $row;
echo json_encode($output);
