<?php

//find assocatiated location and item for input at item query


include_once '../connection/connection_details.php';

$var_itemnum = intval($_POST['itemnum']);
$var_location = ($_POST['location']);

if ($var_itemnum == 0) {
    $sqlfilter = "slotmaster_loc = '$var_location'";
} else {
    $sqlfilter = "slotmaster_item = $var_itemnum";
} 

$itemloc = $conn1->prepare("SELECT 
                                                                slotmaster_loc,
                                                                slotmaster_item
                                                        FROM
                                                            gillingham.slotmaster
                                                        WHERE
                                                            $sqlfilter");
$itemloc->execute();
$itemlocarray = $itemloc->fetchAll(pdo::FETCH_ASSOC);
$returnloc = $itemlocarray[0]['slotmaster_loc'];
$returnitem = $itemlocarray[0]['slotmaster_item'];

echo json_encode(array($returnloc, $returnitem));
