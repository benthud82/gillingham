
<?php
ini_set('max_execution_time', 99999);


include_once '../connection/NYServer.php';

$vectormapdata = $conn1->prepare("SELECT 
                                                                        ' ', baylocerr_loc, LOC_DIM
                                                                    FROM
                                                                        gillingham.bayloc_errors A
                                                                            JOIN
                                                                        gillingham.location_master B ON baylocerr_loc = B.LOCATION
                                                                            LEFT JOIN
                                                                        gillingham.bay_location C ON C.LOCATION = A.baylocerr_loc
                                                                    WHERE
                                                                        C.LOCATION IS NULL
                                                                    ORDER BY baylocerr_loc ASC");
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
