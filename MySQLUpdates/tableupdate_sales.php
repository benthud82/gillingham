<?php

include_once '../globalincludes/google_connect.php';
//include_once '../connection/NYServer.php';

ini_set('memory_limit', '-1'); //max size 32m
ini_set('max_execution_time', 99999);

$fileglob = glob('../../ftproot/ftpuk/Sales*.csv');  //glob wildcard searches for any file

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

//insert into gill_raw table
$columns = 'idGill_Test, ITEM, PKGU, PKTYPE, UNITS, PICKDATE, LOCATION';
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
        $sales_item = ($result[$counter]['Item']);
        $sales_pkgu = ($result[$counter]['Qty Level 1']);
        $sales_pktype = ($result[$counter]['UOM Level 1']);
        $sales_units = ($result[$counter]['Total Qty']);
        $sales_date = $result[$counter]['Date Printed'];
        $sales_location = ($result[$counter]['Location']);
        


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
//foreach ($fileglob as $deletefile) {
//    unlink(realpath($deletefile));
//}



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
    BAY
FROM
    gillingham.slotmaster
        LEFT JOIN
    gillingham.bay_location ON LOCATION = slotmaster_loc)
                                    ON DUPLICATE KEY UPDATE 
                                    slotmaster_bay=VALUES(slotmaster_bay)";
$querymerge2 = $conn1->prepare($sqlmerge2);
$querymerge2->execute();

