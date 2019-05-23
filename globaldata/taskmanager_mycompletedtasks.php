<?php

include_once '../connection/NYServer.php';
$var_userid = strtoupper($_GET['userid']);


$allopentasks = $conn1->prepare("SELECT 
                                                                openactions_assignedby,
                                                                openactions_item,
                                                                date(openactions_completeddate),
                                                                openactions_comment,
                                                                openactions_completedcomment
                                                            FROM
                                                                gillingham.slottingdb_itemactions
                                                            WHERE
                                                               openactions_completeduser = '$var_userid'
                                                                    AND openactions_status = 'COMPLETED';");
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
