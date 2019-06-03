<?php

//updates gillingham.location_master table
//include_once '../globalincludes/google_connect.php';
include_once '../connection/NYServer.php';
$tier = 'BLANK';

ini_set('memory_limit', '-1'); //max size 32m
ini_set('max_execution_time', 99999);

//truncate item_master
$sqldelete = "TRUNCATE  gillingham.location_master";
$querydelete = $conn1->prepare($sqldelete);
$querydelete->execute();

$fileglob = glob('../../ftproot/ftpuk/storage*.csv');  //glob wildcard searches for any file

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
$columns = 'BRANCH,LOCATION,HEIGHT,DEPTH,WIDTH,CUBE,USE_HEIGHT,USE_DEPTH,USE_WIDTH,USE_CUBE,LOC_CHAR,PICK_ZONE,AISLE,LOC_DIM,ALLOW_PICK,ALLOW_REPLEN,ALLOW_PUT, TIER';
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
        $store_branch = ($result[$counter]['Branch']);
        $store_loc = ($result[$counter]['Location']);
        $store_hei = ($result[$counter]['Height']);
        $store_dep = ($result[$counter]['Depth']);
        $store_wid = ($result[$counter]['Width']);
        $store_cube = ($result[$counter]['Cube']);
        $store_usehei = ($result[$counter]['Usable Height']);
        $store_usedep = ($result[$counter]['Usable Depth']);
        $store_usewid = ($result[$counter]['Usable Width']);
        $store_usecube = ($result[$counter]['Usable Cube']);
        $store_locchar = ($result[$counter]['Location Characteristics']);
        $store_zone = ($result[$counter]['Picking Zone']);
        $store_aisle = ($result[$counter]['Aisle']);
        $store_dimgroup = ($result[$counter]['Group Location Dimension']);
        $store_pick = ($result[$counter]['Allow Pick']);
        $store_replen = ($result[$counter]['Allow Replen']);
        $store_put = ($result[$counter]['Allow Putaway']);


        $data[] = "('$store_branch', '$store_loc', '$store_hei', '$store_dep', '$store_wid', '$store_cube', '$store_usehei', '$store_usedep', '$store_usewid', '$store_usecube', '$store_locchar', '$store_zone',"
                . "'$store_aisle', '$store_dimgroup', '$store_pick', '$store_replen', '$store_put', '$tier')";
        $counter += 1;
    }


    $values = implode(',', $data);

    if (empty($values)) {
        break;
    }
    $sql = "INSERT INTO gillingham.location_master ($columns) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();
    $maxrange += 1000;
} while ($counter <= $rowcount); //end of item by whse loop


foreach ($fileglob as $deletefile) {
    unlink(realpath($deletefile));
}


//update tier column
//Pull in vector map bay from bay_loc and overwrite $slotmaster_bay in the slotmaster table
$sqlupdate2 = "UPDATE gillingham.location_master 
                            SET 
                                TIER = CASE
                                    WHEN LOCATION like '01%' then 'DEAD'
                                    WHEN LOCATION like '67%' then 'DEAD' 
                                    WHEN LOCATION >= '69*' THEN 'CASE'
                                    WHEN SUBSTRING(LOC_DIM, 1, 2) = 'CL' THEN 'FLOW'
                                    WHEN DEPTH between 38 and 42 then 'ECAP'
                                    WHEN USE_DEPTH < 80 THEN 'BIN'
                                    WHEN LOC_DIM = 'MSFP1' THEN 'PALL'
                                    ELSE 'OTHER'
                                END;";
$queryupdate2 = $conn1->prepare($sqlupdate2);
$queryupdate2->execute();
