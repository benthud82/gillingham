<?php
include_once '../connection/NYServer.php';
include '../../globalfunctions/custdbfunctions.php';

$time = strtotime("-1 year", time());
$date = date("Y-m-d", $time);

$result1 = $conn1->prepare("SELECT 
                                                        @curRank:=@curRank + 1 AS rank,
                                                        replen_date,
                                                        COUNT(*) AS TOTAL
                                                    FROM
                                                        gillingham.replen
                                                            LEFT JOIN
                                                        gillingham.excl_replenphistorical ON replen_date = replenexcl_date
                                                            JOIN
                                                        (SELECT @curRank:=0) AS init
                                                    WHERE
                                                        replen_date >= ''
                                                            AND replen_toloc < '69*'
                                                            AND replenexcl_date IS NULL
                                                    GROUP BY replen_date
                                                    ORDER BY replen_date");
$result1->execute();
$scorearray = $result1->fetchAll(pdo::FETCH_ASSOC);
$rankarray = array_column($scorearray, 'rank');
$totalscorearray = array_column($scorearray, 'TOTAL');
$totalscoretrend = linear_regression($rankarray, $totalscorearray);

$rows = array();
$rows['name'] = 'Date';
$rows1 = array();
$rows1['name'] = 'Total Replens';
$rows2 = array();
$rows2['name'] = 'Trend Line';

foreach ($scorearray as $row) {
    $rows['data'][] = $row['replen_date'];
    $rows1['data'][] = intval($row['TOTAL']);
//    $rows2['data'][] = intval($row['AUTOCount']);
//    $rows3['data'][] = intval($row['AUTOCount']) + intval($row['ASOCount']);
}

$startx = 1;
$starty = $totalscoretrend['b'];
$endx = sizeof($totalscorearray);
$endy = $totalscoretrend['b'] + ($totalscoretrend['m'] *$endx) ;

$rows2['data'][] = [$startx, $starty];
$rows2['data'][] = [$endx, $endy];

$result = array();
array_push($result, $rows);
array_push($result, $rows1);
array_push($result, $rows2);


print json_encode($result);

