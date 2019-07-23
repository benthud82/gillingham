<?php

include_once '../connection/NYServer.php';
$var_userid = strtoupper($_GET['userid']);
$var_itemnum = strtoupper($_GET['itemnum']);


$opentasks = $conn1->prepare("SELECT 
                                                            openactions_id,
                                                            openactions_assignedby,
                                                            openactions_assignedto,
                                                            openactions_assigneddate,
                                                            openactions_comment,
                                                            ' '
                                                        FROM
                                                            gillingham.slottingdb_itemactions
                                                        WHERE
                                                            UPPER(openactions_item) = $var_itemnum
                                                                    and openactions_status = 'OPEN';");
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
