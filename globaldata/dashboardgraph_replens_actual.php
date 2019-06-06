<?php

//Need replen data!!


include_once '../connection/NYServer.php';

$time = strtotime("-1 year", time());
$date = date("Y-m-d", $time);

$result1 = $conn1->prepare("SELECT 
                                                    replen_date, COUNT(*) AS TOTAL
                                                FROM
                                                    gillingham.replen
                                                        LEFT JOIN
                                                    gillingham.excl_replenphistorical ON replen_date = replenexcl_date
                                                WHERE
                                                    replen_date >= '$date'
                                                        AND replen_toloc < '69*'
                                                        AND replenexcl_date IS NULL
                                                GROUP BY replen_date");
$result1->execute();

$rows = array();
$rows['name'] = 'Date';
$rows1 = array();
$rows1['name'] = 'Total Replens';
//$rows2 = array();
//$rows2['name'] = 'AUTOs';
//$rows3 = array();
//$rows3['name'] = 'Total';

foreach ($result1 as $row) {
    $rows['data'][] = $row['replen_date'];
    $rows1['data'][] = intval($row['TOTAL']);
//    $rows2['data'][] = intval($row['AUTOCount']);
//    $rows3['data'][] = intval($row['AUTOCount']) + intval($row['ASOCount']);
}


$result = array();
array_push($result, $rows);
array_push($result, $rows1);
//array_push($result, $rows2);
//array_push($result, $rows3);


print json_encode($result);

