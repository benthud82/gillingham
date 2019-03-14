<?php

ini_set('max_execution_time', 99999);
ini_set('memory_limit', '-1');
//include_once '../../globalincludes/google_connect.php';
include_once '../connection/NYServer.php';
include_once 'globalfunctions.php';
include_once '../globalfunctions/newitem.php';
include_once '../globalfunctions/slottingfunctions.php';

//what is capacity?
$usevol_sql = $conn1->prepare("SELECT 
                                SUM(USE_CUBE) AS avail_cube,
                                SUM(USE_CUBE) * .85 as cap_85
                            FROM
                                gillingham.location_master");
$usevol_sql->execute();
$usevol_array = $usevol_sql->fetchAll(pdo::FETCH_ASSOC);

$capacity = $usevol_array[0]['cap_85'];

//pull in top item based of next grid flag

