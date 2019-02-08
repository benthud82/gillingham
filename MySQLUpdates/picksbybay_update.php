<?php

set_time_limit(99999);
ini_set('memory_limit', '-1');
$picksbybay_WHSE = 'GB0001';
include_once '../connection/connection_details.php';
$previous7days = date('Y-m-d', strtotime('-7 days'));
$idpicksbybay = 0;


$result = $conn1->prepare("SELECT 
                                                        PICKDATE,
                                                            L.BAY as BAY,
                                                        COUNT(*) AS PICKCOUNT
                                                    FROM
                                                        gillingham.gill_raw
                                                            JOIN
                                                        gillingham.slotmaster ON slotmaster_item = ITEM
                                                        JOIN gillingham.bay_location L on L.LOCATION = slotmaster_loc
                                                    WHERE
                                                        PICKDATE >= '2017-08-01'
                                                    GROUP BY PICKDATE , BAY");
$result->execute();
$resultarray = $result->fetchAll(PDO::FETCH_ASSOC);

$columns = 'picksbybay_WHSE, picksbybay_DATE, picksbybay_BAY, picksbybay_PICKS';

$maxrange = 999;
$counter = 0;
$rowcount = count($resultarray);

do {
    if ($maxrange > $rowcount) {  //prevent undefined offset
        $maxrange = $rowcount - 1;
    }
    $data = array();
    $values = array();

    while ($counter <= $maxrange) {


        $picksbybay_DATE = date('Y-m-d', strtotime($resultarray[$counter]['PICKDATE']));
        $picksbybay_BAY = $resultarray[$counter]['BAY'];
        $picksbybay_PICKS = $resultarray[$counter]['PICKCOUNT'];

        $data[] = "('$picksbybay_WHSE', '$picksbybay_DATE', '$picksbybay_BAY', $picksbybay_PICKS)";
        $counter += 1;
    }

    $values = implode(',', $data);
    if (empty($values)) {
        break;
    }

    $sql = "INSERT IGNORE INTO gillingham.picksbybay ($columns) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();
    $maxrange += 1000;
} while ($counter <= $rowcount);





