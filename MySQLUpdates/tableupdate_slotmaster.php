<?php

//include_once '../globalincludes/google_connect.php';
include_once '../connection/NYServer.php';

ini_set('memory_limit', '-1'); //max size 32m
ini_set('max_execution_time', 99999);

$fileglob = glob('../../ftproot/ftpuk/slot*.csv');  //glob wildcard searches for any file

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


$sqldelete3 = "TRUNCATE gillingham.slotmaster ";
$querydelete3 = $conn1->prepare($sqldelete3);
$querydelete3->execute();

//insert into slotmaster table
$columns = 'slotmaster_branch,slotmaster_loc,slotmaster_item,slotmaster_grhigh,slotmaster_grdeep,slotmaster_grwide,slotmaster_grcube,slotmaster_usehigh,slotmaster_usedeep,slotmaster_usewide,slotmaster_usecube,slotmaster_pkgu,slotmaster_chargroup,slotmaster_pickzone,slotmaster_dimgroup,slotmaster_normreplen,slotmaster_minreplen,slotmaster_maxreplen,slotmaster_allowpick,slotmaster_allowreplen,slotmaster_tier,slotmaster_bay, slotmaster_impmoves';
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
        $slotmaster_branch = trim($result[$counter]['Branch']);
        $slotmaster_loc = $result[$counter]['Location'];
        If ($slotmaster_loc == '') {
            $counter += 1;
            continue;
        }
        $slotmaster_item = $result[$counter]['Item'];
        If ($slotmaster_item == '') {
            $counter += 1;
            continue;
        }
        $slotmaster_grhigh = $result[$counter]['Gross Height'];
        $slotmaster_grdeep = $result[$counter]['Gross Depth'];
        $slotmaster_grwide = $result[$counter]['Gross Width'];
        $slotmaster_grcube = $result[$counter]['Gross Cube'];
        $slotmaster_usehigh = $result[$counter]['Usable Height'];
        $slotmaster_usedeep = $result[$counter]['Usable Depth'];
        $slotmaster_usewide = $result[$counter]['Usable Width'];
        $slotmaster_usecube = $result[$counter]['Usable Cube'];
        $slotmaster_pkgu = $result[$counter]['Unit of Measure'];
        $slotmaster_chargroup = $result[$counter]['Location Characteristics'];
        $slotmaster_pickzone = $result[$counter]['Picking Zone'];
        $slotmaster_dimgroup = $result[$counter]['Location Dimensions'];
        $slotmaster_normreplen = $result[$counter]['Normal Replen Point'];
        $slotmaster_minreplen = $result[$counter]['Min Replen Point'];
        $slotmaster_maxreplen = $result[$counter]['Max Replen Point'];
        $slotmaster_allowpick = $result[$counter]['Allow Pick'];
        $slotmaster_allowreplen = $result[$counter]['Allow Replenishment'];
        $slotmaster_tier = 'XXXX'; //will overwrite at end based of value from location master 

        $slotmaster_bay = 'x';
        $slotmaster_impmoves = 0;  //this is later updated through current_implied_moves.php


        $data[] = "('$slotmaster_branch','$slotmaster_loc',$slotmaster_item,'$slotmaster_grhigh','$slotmaster_grdeep','$slotmaster_grwide','$slotmaster_grcube','$slotmaster_usehigh',"
                . "'$slotmaster_usedeep','$slotmaster_usewide','$slotmaster_usecube','$slotmaster_pkgu','$slotmaster_chargroup','$slotmaster_pickzone','$slotmaster_dimgroup',"
                . "$slotmaster_normreplen,$slotmaster_minreplen,$slotmaster_maxreplen,'$slotmaster_allowpick','$slotmaster_allowreplen','$slotmaster_tier','$slotmaster_bay','$slotmaster_impmoves')";
        $counter += 1;
    }


    $values = implode(',', $data);

    if (empty($values)) {
        break;
    }
    $sql = "INSERT INTO gillingham.slotmaster ($columns) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();
    $maxrange += 1000;
} while ($counter <= $rowcount); //end of item by whse loop

foreach ($fileglob as $deletefile) {
    unlink(realpath($deletefile));
}

//Pull in vector map bay from bay_loc and overwrite $slotmaster_bay in the slotmaster table
$sqlmerge2 = "INSERT INTO gillingham.slotmaster  (SELECT 
    slotmaster_branch,
    slotmaster_loc,
    slotmaster_item,
    slotmaster_grhigh,
    slotmaster_grdeep,
    slotmaster_grwide,
    slotmaster_grcube,
    slotmaster_usehigh,
    slotmaster_usedeep,
    slotmaster_usewide,
    slotmaster_usecube,
    slotmaster_pkgu,
    slotmaster_chargroup,
    slotmaster_pickzone,
    slotmaster_dimgroup,
    slotmaster_normreplen,
    slotmaster_minreplen,
    slotmaster_maxreplen,
    slotmaster_allowpick,
    slotmaster_allowreplen,
    slotmaster_tier,
    BAY,
    slotmaster_impmoves
FROM
    gillingham.slotmaster
        LEFT JOIN
    gillingham.bay_location ON LOCATION = slotmaster_loc)
                                    ON DUPLICATE KEY UPDATE 
                                    slotmaster_bay=VALUES(slotmaster_bay)";
$querymerge2 = $conn1->prepare($sqlmerge2);
$querymerge2->execute();


//update the tier in the slotmaster table with the calculated tier from the location master
$sqlmerge3 = "UPDATE gillingham.slotmaster
                                    INNER JOIN
                                gillingham.location_master ON slotmaster_loc = LOCATION 
                            SET 
                                slotmaster_tier = TIER";
$querymerge3 = $conn1->prepare($sqlmerge3);
$querymerge3->execute();
