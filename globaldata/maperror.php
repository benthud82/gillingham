
<?php
ini_set('max_execution_time', 99999);


include_once '../connection/NYServer.php';

$vectormapdata = $conn1->prepare("SELECT 
                                                                            ' ', A.*
                                                                        FROM
                                                                            gillingham.vectormaperrors A
                                                                                LEFT JOIN
                                                                            gillingham.vectormap B ON maperror_bay = BAY
                                                                        WHERE
                                                                            BAY IS NULL
                                                                        ORDER BY maperror_bay ASC");
$vectormapdata->execute();
$vectormapdataarray = $vectormapdata->fetchAll(pdo::FETCH_ASSOC);



$output = array(
    "aaData" => array()
);
$row = array();

foreach ($vectormapdataarray as $key => $value) {
    $row[] = array_values($vectormapdataarray[$key]);
}


$output['aaData'] = $row;
echo json_encode($output);
