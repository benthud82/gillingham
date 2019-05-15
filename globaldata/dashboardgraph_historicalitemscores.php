<?php

include_once '../connection/NYServer.php';
$var_userid = $_GET['userid'];
$var_itemnum = $_GET['itemnum'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];


$result1 = $conn1->prepare("SELECT 
                                                        histitem_date,
                                                        TRUNCATE((histitem_totalscore * 100), 2) AS histitem_totalscore,
                                                        TRUNCATE((histitem_opttotalscore * 100),
                                                            2) AS histitem_opttotalscore,
                                                        TRUNCATE((histitem_replenscore * 100),
                                                            2) AS histitem_replenscore,
                                                        TRUNCATE((histitem_optreplenscore * 100),
                                                            2) AS histitem_optreplenscore,
                                                        TRUNCATE((histitem_walkscore * 100), 2) AS histitem_walkscore,
                                                        TRUNCATE((histitem_optwalkscore * 100),
                                                            2) AS histitem_optwalkscore
                                                    FROM
                                                        gillingham.slottingscore_hist_item
                                                    WHERE
                                                        histitem_item = $var_itemnum;");
$result1->execute();



$rows = array();
$rows['name'] = 'Date';
$rows1 = array();
$rows1['name'] = 'Total Score';
$rows2 = array();
$rows2['name'] = 'Opt. Total Score';
$rows3 = array();
$rows3['name'] = 'Replen Score';
$rows4 = array();
$rows4['name'] = 'Opt. Replen Score';
$rows5 = array();
$rows5['name'] = 'Walk Score';
$rows6 = array();
$rows6['name'] = 'Opt. Walk Score';


foreach ($result1 as $row) {
    $rows['data'][] = $row['histitem_date'];  //Push fiscal month-year to array
    $rows1['data'][] = ($row['histitem_totalscore']) * 1;
    $rows2['data'][] = ($row['histitem_opttotalscore']) * 1;
    $rows3['data'][] = ($row['histitem_replenscore']) * 1;
    $rows4['data'][] = ($row['histitem_optreplenscore']) * 1;  
    $rows5['data'][] = ($row['histitem_walkscore']) * 1;
    $rows6['data'][] = ($row['histitem_optwalkscore']) * 1;  

}



$result = array();
array_push($result, $rows);
array_push($result, $rows1);
array_push($result, $rows2);
array_push($result, $rows3);
array_push($result, $rows4);
array_push($result, $rows5);
array_push($result, $rows6);



print json_encode($result);
