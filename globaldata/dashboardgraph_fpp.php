<?php
ini_set('max_execution_time', 99999);
include_once '../connection/NYServer.php';
$time = strtotime("-1 year", time());
$date = date("Y-m-d", $time);

$result1 = $conn1->prepare("SELECT * FROM gillingham.feetperpick_summary  WHERE
                                 fpp_date >= '$date'");
$result1->execute();

   

$rows = array();
$rows['name'] = 'Date';
$rows1 = array();
$rows1['name'] = 'FPP';


foreach ($result1 as $row) {
    $rows['data'][] = $row['fpp_date'];
    $rows1['data'][] = $row['fpp_fpp'] * 1;
}


$result = array();
array_push($result, $rows);
array_push($result, $rows1);



print json_encode($result);

