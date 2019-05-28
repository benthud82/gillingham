
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


$dimcount = count($row);
//update the maxmin badge
$sql = "UPDATE gillingham.badges SET vectormap =  $dimcount WHERE whse = 1;";
$query = $conn1->prepare($sql);
$query->execute();




$output['aaData'] = $row;
echo json_encode($output);
