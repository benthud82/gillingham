<?php

include_once '../connection/NYServer.php';
$var_userid = strtoupper($_GET['userid']);


$allopentasks = $conn1->prepare("SELECT 
                                                                openactions_assignedto,
                                                                openactions_item,
                                                                date(openactions_assigneddate),
                                                                openactions_comment,
                                                                openactions_status
                                                            FROM
                                                                gillingham.slottingdb_itemactions
                                                            WHERE
                                                               openactions_assignedby = '$var_userid'");
$allopentasks->execute();
$allopentasksarray= $allopentasks->fetchAll(pdo::FETCH_ASSOC);


$output = array(
    "aaData" => array()
);
$row = array();

foreach ($allopentasksarray as $key => $value) {
    $row[] = array_values($allopentasksarray[$key]);
}


$output['aaData'] = $row;
echo json_encode($output);
