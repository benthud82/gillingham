
<?php

ini_set('max_execution_time', 99999);
include_once '../connection/connection_details.php';

$var_whse = 'GB0001';
$var_date = date('Y-m-d',  strtotime($_GET['datesel']));


$bayreport = $conn1->prepare("SELECT 
                                    picksbybay_BAY,
                                    picksbybay_PICKS,
                                    round(WALKFEET / 1000,2),
                                    round((picksbybay_PICKS * (WALKFEET / 1000)), 2) as TOTFEET    
                                FROM
                                    gillingham.picksbybay
                                        join
                                    gillingham.vectormap ON BAY = picksbybay_BAY
                                WHERE
                                    picksbybay_DATE = '$var_date'
                                ORDER BY picksbybay_PICKS * WALKFEET desc ;");
$bayreport->execute();
$bayreportarray = $bayreport->fetchAll(pdo::FETCH_ASSOC);
      

$output = array(
    "aaData" => array()
);
$row = array();

foreach ($bayreportarray as $key => $value) {
    $row[] = array_values($bayreportarray[$key]);
}


$output['aaData'] = $row;
echo json_encode($output);
