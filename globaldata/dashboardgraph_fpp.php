
<?php

ini_set('max_execution_time', 99999);
include_once '../connection/NYServer.php';
include '../../globalfunctions/custdbfunctions.php';
$time = strtotime("-1 year", time());
$date = date("Y-m-d", $time);

$result1 = $conn1->prepare("SELECT 
                                                        @curRank:=@curRank + 1 AS rank, fpp_date, fpp_fpp
                                                    FROM
                                                        gillingham.feetperpick_summary
                                                            JOIN
                                                        (SELECT @curRank:=0) AS init
                                                    WHERE
                                                        fpp_date >= '$date' 
                                                    ORDER BY fpp_date asc ");
$result1->execute();
$scorearray = $result1->fetchAll(pdo::FETCH_ASSOC);
$rankarray = array_column($scorearray, 'rank');
$totalscorearray = array_column($scorearray, 'fpp_fpp');


$totalscoretrend = linear_regression($rankarray, $totalscorearray);

$rows = array();
$rows['name'] = 'Date';
$rows1 = array();
$rows1['name'] = 'Meters Per Pick';
$rows2 = array();
$rows2['name'] = 'Trend Line';


foreach ($scorearray as $row) {
    $rows['data'][] = $row['fpp_date'];
    $rows1['data'][] = $row['fpp_fpp'] * 1;
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

