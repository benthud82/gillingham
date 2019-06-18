
<?php

ini_set('max_execution_time', 99999);
include_once '../connection/NYServer.php';

$var_userid = $_GET['userid'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];
$var_bay = intval($_GET['baynum']);
$var_report = ($_GET['reportsel']);
$var_tier = ($_GET['tiersel']);
$var_grid5sel = trim(strtok($_GET['grid5sel'], '-'));
$var_returncount = ($_GET['returncount']);

switch ($var_report) {  //build sql statement for report
    case 'MOVEIN':
        if ($var_tier == 'PALL') {
            $reportsql = " A.SUGGESTED_TIER = 'PALL' and A.LMTIER <> 'PALL' ";
        } else if ($var_tier == 'FLOW') {
            $reportsql = " A.SUGGESTED_TIER = 'FLOW' and A.LMTIER <> 'FLOW' ";
        } else {
            $reportsql = " B.OPT_OPTBAY = $var_bay and B.OPT_CURRBAY <> $var_bay  and A.SUGGESTED_GRID5 like '$var_grid5sel' and A.SUGGESTED_TIER in ('$var_tier')   ";
        }
        break;

    case 'MOVEOUT':
        if ($var_tier == 'PALL') {
            $reportsql = " A.SUGGESTED_TIER <> 'PALL' and A.LMTIER = 'PALL' ";
        } else if ($var_tier == 'FLOW') {
            $reportsql = " A.SUGGESTED_TIER <> 'FLOW' and A.LMTIER = 'FLOW' ";
        } else {
            $reportsql = " B.OPT_OPTBAY <> $var_bay and B.OPT_CURRBAY = $var_bay  and A.LMGRD5  like '$var_grid5sel' and A.SUGGESTED_TIER  in ('$var_tier') and A.LMTIER <> 'DEAD' ";
        }
        break;

    case 'CURRENT':
        $reportsql = " B.OPT_CURRBAY = $var_bay";
        break;

    case 'SHOULD':
        $reportsql = " B.OPT_OPTBAY = $var_bay";
        break;
}


$bayreport = $conn1->prepare("SELECT 
                                                                A.WAREHOUSE,
                                                                A.ITEM_NUMBER,
                                                                A.CUR_LOCATION,
                                                                PICK_ZONE,
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
                                                                    JOIN gillingham.location_master L on CUR_LOCATION = L.LOCATION
                                                             WHERE
                                                                $reportsql
                                                            ORDER BY E.SCORE_REPLENSCORE ASC
                                                            LIMIT $var_returncount");
$bayreport->execute();
$bayreportarray = $bayreport->fetchAll(pdo::FETCH_ASSOC);


$output = array(
    "aaData" => array()
);
$row = array();

foreach ($bayreportarray as $key => $value) {
    $row[] = array_values($bayreportarray[$key]);
}


$output['aaData'] = $row;
echo json_encode($output);
