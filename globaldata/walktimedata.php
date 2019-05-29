
<?php

ini_set('max_execution_time', 99999);
include_once '../connection/NYServer.php';

$var_userid = $_GET['userid'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

$var_whse = intval($whssqlarray[0]['slottingDB_users_PRIMDC']);

$var_report = ($_GET['reportsel']);



switch ($var_report) {  //build sql statement for report
    case 'highwalk':

        $whercaluse = ' B.OPT_ADDTLFTPERDAY > 0 ';
        $orderby = ' B.OPT_ADDTLFTPERDAY DESC';
        break;

    case 'negativewalk':

        $whercaluse = ' B.OPT_ADDTLFTPERDAY < 0' ;
        $orderby = ' B.OPT_ADDTLFTPERDAY ASC';
        break;
}


$dopoundsql = $conn1->prepare("SELECT DISTINCT
                                                                A.WAREHOUSE,
                                                                A.ITEM_NUMBER,
                                                                A.CUR_LOCATION,
                                                                B.OPT_ADDTLFTPERDAY,
                                                                A.DAYS_FRM_SLE,
                                                                A.AVGD_BTW_SLE,
                                                                A.LMGRD5,
                                                                A.LMDEEP,
                                                                B.OPT_CURRBAY,
                                                                A.SUGGESTED_GRID5,
                                                                A.SUGGESTED_DEPTH,
                                                                B.OPT_OPTBAY,
                                                                A.SUGGESTED_MAX,
                                                                A.SUGGESTED_MIN,
                                                                CAST(A.SUGGESTED_IMPMOVES * 253 AS UNSIGNED),
                                                                CAST(A.CURRENT_IMPMOVES * 253 AS UNSIGNED),
                                                                CAST(A.AVG_DAILY_PICK AS DECIMAL (4 , 2 )),
                                                                CAST(A.AVG_DAILY_UNIT AS DECIMAL (4 , 2 )),
                                                                CONCAT(CAST(E.SCORE_REPLENSCORE * 100 AS DECIMAL (5 , 2 )),
                                                                        '%'),
                                                                CONCAT(CAST(E.SCORE_WALKSCORE * 100 AS DECIMAL (5 , 2 )),
                                                                        '%'),
                                                                CONCAT(CAST(E.SCORE_TOTALSCORE * 100 AS DECIMAL (5 , 2 )),
                                                                        '%')
                                                            FROM
                                                                gillingham.my_npfmvc A
                                                                    JOIN
                                                                gillingham.optimalbay B ON A.ITEM_NUMBER = B.OPT_ITEM
                                                                    AND A.PACKAGE_UNIT = B.OPT_PKGU
                                                                    AND A.PACKAGE_TYPE = B.OPT_CSLS
                                                                    JOIN
                                                                gillingham.slottingscore E ON E.SCORE_ITEM = A.ITEM_NUMBER
                                                                    AND E.SCORE_PKGU = A.PACKAGE_UNIT
                                                                    AND E.SCORE_ZONE = A.PACKAGE_TYPE
                                                            WHERE
                                                                 A.SUGGESTED_TIER in ('BIN','FLOW', 'ECAP')
                                                                    AND $whercaluse
                                                                    ORDER BY $orderby
                                                                LIMIT 1000");
$dopoundsql->execute();
$dopoundsqlarray = $dopoundsql->fetchAll(pdo::FETCH_ASSOC);


$output = array(
    "aaData" => array()
);
$row = array();

foreach ($dopoundsqlarray as $key => $value) {
    $row[] = array_values($dopoundsqlarray[$key]);
}


$output['aaData'] = $row;
echo json_encode($output);
