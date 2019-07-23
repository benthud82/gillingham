<?php

include_once '../connection/NYServer.php';
$var_userid = strtoupper($_GET['userid']);
$var_itemnum = intval($_GET['itemnum']);

$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE UPPER(idslottingDB_users_ID) = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);
$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];


$closedtasks = $conn1->prepare("SELECT 
                                                                    histitem_date,
                                                                    histitem_location,
                                                                    histitem_currtier,
                                                                    histitem_suggtier,
                                                                    histitem_currgrid5,
                                                                    histitem_sugggrid5,
                                                                    histitem_suggdepth,
                                                                    histitem_suggslotqty,
                                                                    histitem_suggmax,
                                                                    CAST(histitem_currmoves * 253 AS UNSIGNED) AS histitem_currmoves,
                                                                    CAST(histitem_impmoves * 253 AS UNSIGNED) AS histitem_impmoves,
                                                                    TRUNCATE(histitem_avgpick, 1) AS histitem_avgpick,
                                                                    TRUNCATE(histitem_avgunit, 1) AS histitem_avgunit,
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
$closedtasks->execute();
$closedtasksarray = $closedtasks->fetchAll(pdo::FETCH_ASSOC);


$output = array(
    "aaData" => array()
);
$row = array();

foreach ($closedtasksarray as $key => $value) {
    $row[] = array_values($closedtasksarray[$key]);
}


$output['aaData'] = $row;
echo json_encode($output);
