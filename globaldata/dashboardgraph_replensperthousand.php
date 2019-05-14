<?php
//NO REPLEN DATA!!
//ini_set('max_execution_time', 99999);
//include_once '../connection/NYServer.php';
//$var_userid = $_GET['userid'];
//$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from slotting.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
//$whssql->execute();
//$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);
//
//$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];
//$table = ($var_whse) . 'invlinesshipped';
//$table2 = ($var_whse) . 'dailymovecount';
//
//
//$time = strtotime("-1 year", time());
//$date = date("Y-m-d", $time);
//
//$result1 = $conn1->prepare("SELECT 
//                                INVDATE,
//                                ASOCount / (INVLINES / 1000) as ASO,
//                                AUTOCount / (INVLINES / 1000) as AUTO
//                            FROM
//                                {$table}
//                                JOIN
//                                 {$table2} ON MoveDate = INVDATE
//                            LEFT JOIN slotting.excl_replenperthousand on replenexcl_whse = INVWHSE and replenexcl_date = INVDATE
//                            WHERE INVDATE >= '$date' and replenexcl_date is null
//                            GROUP BY INVDATE");
//$result1->execute();
//
//
//
//$rows = array();
//$rows['name'] = 'Date';
//$rows1 = array();
//$rows1['name'] = 'ASOs';
//$rows2 = array();
//$rows2['name'] = 'AUTOs';
//
//
//foreach ($result1 as $row) {
//    $rows['data'][] = $row['INVDATE'];
//    $rows1['data'][] = ($row['ASO']) * 1.0;
//    $rows2['data'][] = ($row['AUTO']) * 1.0;
//
//}
//
//
//$result = array();
//array_push($result, $rows);
//array_push($result, $rows1);
//array_push($result, $rows2);
//
//
//print json_encode($result);
