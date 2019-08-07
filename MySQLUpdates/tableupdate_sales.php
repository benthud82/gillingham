<?php

//include_once '../globalincludes/google_connect.php';
include_once '../connection/NYServer.php';

ini_set('memory_limit', '-1'); //max size 32m
ini_set('max_execution_time', 99999);

$fileglob = glob('../../ftproot/ftpuk/sales*.csv');  //glob wildcard searches for any file

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
$columns = 'idGill_Test, ITEM, PKGU, PKTYPE, UNITS, PICKDATE, LOCATION, AVGINV';
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
        $sales_item = intval($result[$counter]['Item']);
        If ($sales_item == '' || $sales_item == 0) {
            $counter += 1;
            continue;
        }
        $sales_pkgu = ($result[$counter]['Qty Level 1']);
        $sales_pktype = ($result[$counter]['UOM Level 1']);
        $sales_units = ($result[$counter]['Total Qty']);
        $sales_date = date_create_from_format('d/m/y', $result[$counter]['Date Printed']);
        $formattedate = $sales_date->format('Y-m-d');
        $sales_location = ($result[$counter]['Location']);
        $sales_avginv = ($result[$counter]['Average Qty']);



        $data[] = "(0, $sales_item, $sales_pkgu, '$sales_pktype', $sales_units, '$formattedate', '$sales_location', $sales_avginv)";
        $counter += 1;
    }


    $values = implode(',', $data);

    if (empty($values)) {
        break;
    }
    $sql = "INSERT INTO gillingham.gill_raw ($columns) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();
    $maxrange += 1000;
} while ($counter <= $rowcount); //end of item by whse loop


foreach ($fileglob as $deletefile) {
    unlink(realpath($deletefile));
}

//update average inventory table
$sqlmerge2 = "INSERT into gillingham.avg_inv (BRANCH, ITEM, AVG_OH) SELECT 'GB0001', b.ITEM , b.AVGINV FROM gillingham.gill_raw b WHERE b.PICKDATE = '$formattedate' and ITEM = b.ITEM on duplicate key update AVG_OH=b.AVGINV";
$querymerge2 = $conn1->prepare($sqlmerge2);
$querymerge2->execute();

//update lines shipped table
 $startdate = date('Y-m-d', strtotime('-10 days'));
 $sqlmerge3 = "INSERT INTO gillingham.invlinesshipped
                                    SELECT 
                                        0,
                                        PICKDATE,
                                        COUNT(*) AS TOT_LINES,
                                        CASE
                                            WHEN PKTYPE = 'EA' AND LOCATION < '69*' THEN 'LSE'
                                            ELSE 'CSE'
                                        END AS TYPE
                                    FROM
                                        gillingham.gill_raw
                                        WHERE PICKDATE >= '$startdate'
                                    GROUP BY PICKDATE , CASE
                                        WHEN PKTYPE = 'EA' AND LOCATION < '69*' THEN 'LSE'
                                        ELSE 'CSE'
                                    END
                                    ON DUPLICATE KEY UPDATE INVLINES=values(INVLINES)";
$querymerge3 = $conn1->prepare($sqlmerge3);
$querymerge3->execute();