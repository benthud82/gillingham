<?php
//creates table gillingham.nptsld
$holidays = array();
ini_set('max_execution_time', 99999);
ini_set('memory_limit', '-1');
include_once '../connection/connection_details.php';
include_once 'globalfunctions.php';

$sqldelete = "TRUNCATE gillingham.gill_grouped";
$querydelete = $conn1->prepare($sqldelete);
$querydelete->execute();

$sqldelete2 = "TRUNCATE gillingham.gill_raw_30day";
$querydelete2 = $conn1->prepare($sqldelete2);
$querydelete2->execute();

//create 30 day table
$sql_30day = $conn1->prepare("INSERT into gillingham.gill_raw_30day (idGill_Test, ITEM, PKGU, PKTYPE, UNITS, PICKDATE, LOCATION)
                                                                SELECT 
                                                                    idGill_Test,
                                                                    ITEM,
                                                                    PKGU,
                                                                    PKTYPE,
                                                                    UNITS,
                                                                    PICKDATE,
                                                                    LOCATION
                                                                FROM
                                                                    (SELECT 
                                                                        A.*,
                                                                            @currcount:=IF(@currvalue = CONCAT(ITEM, PICKDATE), @currcount, IF(SUBSTRING(@currvalue, 1, 7) = ITEM, @currcount + 1, 1)) AS rank,
                                                                            @currvalue:=CONCAT(ITEM, PICKDATE) AS whatever
                                                                    FROM
                                                                        gillingham.gill_raw A
                                                                    ORDER BY ITEM , PICKDATE DESC) AS whatever
                                                                WHERE
                                                                    rank <= 61 AND PKTYPE IN 'EA'");
$sql_30day->execute();



//would only want to go back certain number of days
$rawsql = $conn1->prepare("SELECT 
                                                        A.ITEM,
                                                        A.PKGU,
                                                        A.PKTYPE,
                                                        A.PICKDATE,
                                                        COUNT(*) AS PICK_COUNT,
                                                        SUM(A.UNITS) AS UNITS_SUM,
                                                        CONCAT(A.ITEM, A.PKGU, A.PKTYPE) AS KEYVAL,
                                                        ceil(AVG(V.AVG_OH)) AS INV_OH
                                                    FROM
                                                        gillingham.gill_raw_30day A
                                                            JOIN
                                                        gillingham.npfcpcsettings ON CPCITEM = ITEM
                                                    GROUP BY A.ITEM , A.PKGU , A.PKTYPE , A.PICKDATE
                                                    ORDER BY A.ITEM ASC , A.PICKDATE DESC");
$rawsql->execute();
$groupedarray = $rawsql->fetchAll(pdo::FETCH_ASSOC);
$columns = 'GROUPED_ITEM, GROUPED_PKGU, GROUPED_PKTYPE, GROUPED_DATE, GROUPED_PICKS, GROUPED_UNITS, GROUPED_PREVSALE, GROUPED_DSLS, GROUPED_INVOH';
$maxrange = 20000;
$counter = 0;
$rowcount = count($groupedarray);
do {
    if ($maxrange > $rowcount) {  //prevent undefined offset
        $maxrange = $rowcount - 1;
    }

    $data = array();
    $values = array();
    while ($counter <= $maxrange) { //split into 10,000 lines segments to insert into merge table //sub loop through items by whse to pull in CPC settings by whse/item
        $ITEM = intval($groupedarray[$counter]['ITEM']);
        $PKGU = intval($groupedarray[$counter]['PKGU']);
        $PKTYPE = $groupedarray[$counter]['PKTYPE'];
        $PICKDATE = date('Y-m-d', strtotime($groupedarray[$counter]['PICKDATE']));
        $PICK_COUNT = intval($groupedarray[$counter]['PICK_COUNT']);
        $UNITS_SUM = intval($groupedarray[$counter]['UNITS_SUM']);
        $INV_OH = intval($groupedarray[$counter]['INV_OH']);
        $KEYVAL = ($groupedarray[$counter]['KEYVAL']);

        //when item changes, don't calc DSLS
        if ($maxrange === $counter) {
            $previousdate = '0000-00-00';
            $DSLS = 0;
        } else if ($KEYVAL !== $groupedarray[$counter + 1]['KEYVAL']) {
            $previousdate = '0000-00-00';
            $DSLS = 0;
        } else {
            $previousdate = date('Y-m-d', strtotime($groupedarray[$counter + 1]['PICKDATE']));
            $DSLS = intval(getWorkingDays($previousdate, $PICKDATE, $holidays));
        }
        $data[] = "($ITEM, $PKGU, '$PKTYPE', '$PICKDATE', $PICK_COUNT, $UNITS_SUM, '$previousdate', $DSLS, $INV_OH)";
        $counter += 1;
    }
    $values = implode(',', $data);

    if (empty($values)) {
        break;
    }
    $sql = "INSERT IGNORE INTO gillingham.gill_grouped ($columns) VALUES $values";
    $query = $conn1->prepare($sql);
    $query->execute();
    $maxrange += 20000;
} while ($counter <= $rowcount); //end of item by whse loop
//Truncate NPTLSD file
$sqldelete = "TRUNCATE gillingham.nptsld";
$querydelete = $conn1->prepare($sqldelete);
$querydelete->execute();

//Create NPTSLD file from gill_grouped
$sql2 = "INSERT IGNORE INTO gillingham.nptsld
                         SELECT 
                                A.GROUPED_ITEM,
                                A.GROUPED_PKGU,
                                A.GROUPED_PKTYPE,
                                COUNT(*),
                                B.RECENTDSLS,
                                AVG(A.GROUPED_DSLS),
                                STDDEV(A.GROUPED_DSLS),
                                AVG(A.GROUPED_PICKS),
                                STDDEV(A.GROUPED_PICKS),
                                AVG(A.GROUPED_UNITS),
                                STDDEV(A.GROUPED_UNITS),
                                CEIL(AVG(A.GROUPED_INVOH))
                             FROM
                                gillingham.gill_grouped A
                                    INNER JOIN
                                (SELECT 
                                    GROUPED_ITEM,
                                        GROUPED_PKGU,
                                        GROUPED_PKTYPE,
                                        GROUPED_DSLS AS RECENTDSLS
                                FROM
                                    gillingham.gill_grouped
                                GROUP BY GROUPED_ITEM , GROUPED_PKGU , GROUPED_PKTYPE) B ON A.GROUPED_ITEM = B.GROUPED_ITEM
                                    AND A.GROUPED_PKGU = B.GROUPED_PKGU
                                    AND A.GROUPED_PKTYPE = B.GROUPED_PKTYPE
                               WHERE A.GROUPED_DSLS <> 0
                            GROUP BY A.GROUPED_ITEM , A.GROUPED_PKGU , A.GROUPED_PKTYPE";
$query2 = $conn1->prepare($sql2);
$query2->execute();
