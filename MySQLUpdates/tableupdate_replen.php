<?php

//updates gillingham.location_master table
//include_once '../globalincludes/google_connect.php';
include_once '../connection/NYServer.php';

ini_set('memory_limit', '-1'); //max size 32m
ini_set('max_execution_time', 99999);

$fileglob = glob('../../ftproot/ftpuk/replen*.csv');  //glob wildcard searches for any file

if (count($fileglob) > 0) {
    $filename = $fileglob[0];
}

$result = array();
$fp = fopen($filename, 'r');
if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
    if ($headers) {
        while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($line) {
                if (sizeof($line) == sizeof($headers)) {
                    $result[] = array_combine($headers, $line);
                }
            }
        }
    }
}
fclose($fp);

//insert into item_master table
$columns = 'replen_id, replen_date, replen_code, replen_item, replen_qty, replen_pkgu, replen_fromloc, replen_zone, replen_toloc, replen_pickzone';
$maxrange = 999;
$counter = 0;
$rowcount = count($result);

do {
    if ($maxrange > $rowcount) {  //prevent undefined offset
        $maxrange = $rowcount - 1;
    }

    $data = array();
    $values = array();
    while ($counter <= $maxrange) { //split into 5,000 lines segments to insert into merge table //sub loop through items by whse to pull in CPC settings by whse/item
        $replen_date = date_create_from_format('d/m/y', ($result[$counter]['Date Printed']));
        $formattedate = $replen_date->format('Y-m-d');

        $dayofweek = date('w', strtotime($formattedate));
        if ($dayofweek == 6) {
            $date = date('Y-m-d', strtotime($formattedate . ' + 2 day'));
        } elseif ($dayofweek == 0) {
            $date = date('Y-m-d', strtotime($formattedate . ' + 1 day'));
        } else {
            $date = $formattedate;
        }

        $replen_code = ($result[$counter]['Code']);
        $replen_item = intval($result[$counter]['Item']);
        $replen_qty = ($result[$counter]['Total Quantity']);
        $replen_pkgu = ($result[$counter]['UOM']);
        $replen_fromloc = ($result[$counter]['From Location']);
        $replen_zone = ($result[$counter]['From Location Replen Zone']);
        $replen_toloc = ($result[$counter]['To Location']);
        $replen_pickzone = ($result[$counter]['To Location Pick Zone']);

        $data[] = "(0, '$date', '$replen_code', $replen_item, $replen_qty, '$replen_pkgu', '$replen_fromloc', '$replen_zone', '$replen_toloc', '$replen_pickzone')";
        $counter += 1;
    }


    $values = implode(',', $data);

    if (empty($values)) {
        break;
    }
    $sql = "INSERT INTO gillingham.replen ($columns) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();
    $maxrange += 1000;
} while ($counter <= $rowcount); //end of item by whse loop


foreach ($fileglob as $deletefile) {
    unlink(realpath($deletefile));
}
