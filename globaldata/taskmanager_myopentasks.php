<?php

include_once '../connection/NYServer.php';
$var_userid = strtoupper($_GET['userid']);

$opentasks = $conn1->prepare("SELECT 
                                                                openactions_id,
                                                                openactions_assignedby,
                                                                openactions_item,
                                                                CUR_LOCATION,
                                                                date(openactions_assigneddate),
                                                                openactions_comment,
                                                                ' '
                                                            FROM
                                                                gillingham.slottingdb_itemactions
                                                                    LEFT JOIN
                                                                gillingham.my_npfmvc ON ITEM_NUMBER = openactions_item
                                                            WHERE
                                                                UPPER(openactions_assignedto) = '$var_userid'
                                                                    AND openactions_status = 'OPEN';");
$opentasks->execute();
$opentasksarray = $opentasks->fetchAll(pdo::FETCH_ASSOC);


$output = array(
    "aaData" => array()
);
$row = array();

foreach ($opentasksarray as $key => $value) {
    $row[] = array_values($opentasksarray[$key]);
}


$output['aaData'] = $row;
echo json_encode($output);
