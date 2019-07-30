<?php

//include_once '../globalincludes/google_connect.php';
include_once '../connection/NYServer.php';

ini_set('memory_limit', '-1'); //max size 32m
ini_set('max_execution_time', 99999);

//truncate item_master
$sqldelete = "TRUNCATE  gillingham.item_master";
$querydelete = $conn1->prepare($sqldelete);
$querydelete->execute();

$fileglob = glob('../../ftproot/ftpuk/item*.csv');  //glob wildcard searches for any file

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
$columns = 'BRANCH,ITEM,PKGU_EA,PKGU_CA,PKGU_PL,EA_DEPTH,EA_HEIGHT,EA_WIDTH,CA_DEPTH,CA_HEIGHT,CA_WIDTH,PA_DEPTH,PA_HEIGHT,PA_WIDTH,LINE_TYPE,CHAR_GROUP, ITEM_WEIGHT, DESC1, DESC2';
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
        $item_branch = ($result[$counter]['Branch']);
        $item_item = intval($result[$counter]['Item']);
        $item_eapkgu = ($result[$counter]['Each Unit']);
        $item_capkgu = ($result[$counter]['Case Unit']);
        $item_plpkgu = ($result[$counter]['Pallet Unit']);
        $item_eadep = ($result[$counter]['Each Depth']);
        $item_eahei = ($result[$counter]['Each Height']);
        $item_eawid = ($result[$counter]['Each Width']);
        $item_cadep = ($result[$counter]['Case Depth']);
        $item_cahei = ($result[$counter]['Case Height']);
        $item_cawid = ($result[$counter]['Case Width']);
        $item_padep = ($result[$counter]['Pallet Depth']);
        $item_pahei = ($result[$counter]['Pallet Height']);
        $item_pawid = ($result[$counter]['Pallet Width']);
        $item_linetype = ($result[$counter]['Line Type']);
        $item_chargroup = ($result[$counter]['Characteristics Group']);
        $item_weight = ($result[$counter]['Item Weight']);
         $item_desc1 = preg_replace('/[^ \w]+/', '', $result[$counter]['Description 1']);
         $item_desc2 = preg_replace('/[^ \w]+/', '', $result[$counter]['Description 2']);


        $data[] = "('$item_branch', $item_item, $item_eapkgu, $item_capkgu, $item_plpkgu, '$item_eadep', '$item_eahei', '$item_eawid', '$item_cadep', '$item_cahei', '$item_cawid', '$item_padep',
            '$item_pahei', '$item_pawid','$item_linetype', '$item_chargroup', '$item_weight', '$item_desc1', '$item_desc2')";
        $counter += 1;
    }


    $values = implode(',', $data);

    if (empty($values)) {
        break;
    }
    $sql = "INSERT INTO gillingham.item_master ($columns) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();
    $maxrange += 1000;
} while ($counter <= $rowcount); //end of item by whse loop


foreach ($fileglob as $deletefile) {
    unlink(realpath($deletefile));
}
