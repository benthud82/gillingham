<?php
//Need replen data!!


//include_once '../connection/NYServer.php';
//$var_userid = $_GET['userid'];
//$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from slotting.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
//$whssql->execute();
//$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);
//
//$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];
//$table = ($var_whse) . 'dailymovecount';
//
//$time = strtotime("-1 year", time());
//$date = date("Y-m-d", $time);
//
//$result1 = $conn1->prepare("SELECT * FROM {$table} LEFT JOIN slotting.excl_replenphistorical on  MoveDate = replenexcl_date and replenexcl_whse = $var_whse  WHERE MoveDate >= '$date' and  replenexcl_date is null  ");
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
//$rows3 = array();
//$rows3['name'] = 'Total';
//
//
//foreach ($result1 as $row) {
//    $rows['data'][] = $row['MoveDate'];
//    $rows1['data'][] = intval($row['ASOCount']);
//    $rows2['data'][] = intval($row['AUTOCount']);
//    $rows3['data'][] = intval($row['AUTOCount']) + intval($row['ASOCount']);
//}
//
//
//$result = array();
//array_push($result, $rows);
//array_push($result, $rows1);
//array_push($result, $rows2);
//array_push($result, $rows3);
//
//
//print json_encode($result);

