
<?php

ini_set('max_execution_time', 99999);
include_once '../connection/NYServer.php';

$var_reportsel = $_GET['reportsel'];

switch ($var_reportsel) {
    case 'FROMPALL':
        $sql_where = "LMTIER = 'PALL'
        AND SUGGESTED_TIER <> 'PALL'
        ORDER BY PICK_QTY_MN ASC";
        break;
    case 'TOPALL':
        $sql_where = "LMTIER <> 'PALL'
        AND SUGGESTED_TIER = 'PALL'
        ORDER BY PICK_QTY_MN DESC";
        break;

    default:
        break;
}


$bayreport = $conn1->prepare("SELECT 
                                                                ITEM_NUMBER,
                                                                CUR_LOCATION,
                                                                DAYS_FRM_SLE,
                                                                AVGD_BTW_SLE,
                                                                AVG_INV_OH,
                                                                PICK_QTY_MN,
                                                                SHIP_QTY_MN,
                                                                LMTIER,
                                                                LMGRD5,
                                                                SUGGESTED_TIER,
                                                                SUGGESTED_GRID5,
                                                                SUGGESTED_MAX,
                                                                SUGGESTED_MIN,
                                                                CURRENT_IMPMOVES * 253 as CURRENT_IMPMOVES,
                                                                SUGGESTED_IMPMOVES * 253 as SUGGESTED_IMPMOVES
                                                            FROM
                                                                gillingham.my_npfmvc
                                                            WHERE $sql_where");
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
