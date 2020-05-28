
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
                                    ITEM_NUMBER,
                                    CUR_LOCATION,
                                    DAYS_FRM_SLE,
                                    AVG_INV_OH,
                                    NBR_SHIP_OCC,
                                    LMGRD5,
                                    LMVOL9
                                FROM
                                    gillingham.my_npfmvc
                                    JOIN gillingham.location_master on LOCATION = CUR_LOCATION
                                WHERE
                                    DAYS_FRM_SLE >= 300
                                    AND TIER = '$var_tier'
                                ORDER BY CUR_LOCATION");
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
