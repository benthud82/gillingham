
<?php

ini_set('max_execution_time', 99999);
include_once '../connection/NYServer.php';
include_once '../../globalfunctions/slottingfunctions.php';
include_once '../../globalfunctions/newitem.php';

$var_userid = $_GET['userid'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];

$var_report = ($_GET['reportsel']);

$includetoggle = $_GET['includeaudit'];

if ($includetoggle == 0) {
    $includesql = ' and minmax_reviewdate IS NULL';
} else {
    $includesql = ' ';
}

switch ($var_report) {  //build sql statement for report
    case 'MAX':

        $bayreport = $conn1->prepare("SELECT DISTINCT
    A.WAREHOUSE,
    A.ITEM_NUMBER,
    A.CUR_LOCATION,
    slotmaster_normreplen + slotmaster_maxreplen AS CURMAX,
    slotmaster_normreplen AS CURMIN,
    slotmaster_impmoves AS CURRENT_IMPMOVES,
    slotmaster_currtf AS SUGGSTEDMAX,
    SHIP_QTY_MN,
    AVG_DAILY_UNIT,
    AVG_INV_OH,
    AVGD_BTW_SLE,
    CONCAT(minmax_reviewdate, ' | ', minmax_tsmid) AS minmax_reviewdate,
    CASE
        WHEN itemcomments_id > 0 THEN 'SHOW COMMENTS'
    END AS COMMENTS,
    CASETF,
    A.LMGRD5,
    CPCCLEN,
    CPCCHEI,
    CPCCWID,
    A.LMDEEP,
    A.LMHIGH,
    A.LMWIDE,
    A.CPCCPKU
FROM
    gillingham.my_npfmvc A
        JOIN
    gillingham.slotmaster ON slotmaster_item = ITEM_NUMBER
        AND slotmaster_loc = CUR_LOCATION
        LEFT JOIN
    gillingham.minmaxreview ON minmax_item = ITEM_NUMBER
        AND minmax_location = CUR_LOCATION
        LEFT JOIN
    gillingham.slotting_itemcomments ON itemcomments_item = ITEM_NUMBER
        LEFT JOIN
    gillingham.item_settings S ON S.ITEM = A.ITEM_NUMBER
        AND S.PKGU = A.PACKAGE_UNIT
        AND S.PKGU_TYPE = A.PACKAGE_TYPE
WHERE
    slotmaster_currtf > (slotmaster_normreplen + slotmaster_maxreplen)
        AND A.LMTIER IN ('BIN' , 'FLOW', 'ECAP')
        $includesql
        AND slotmaster_impmoves >= .05");
        $bayreport->execute();
        $bayreportarray = $bayreport->fetchAll(pdo::FETCH_ASSOC);
        break;

    case 'MIN':
        //need to add SQL statement
        break;
}


foreach ($bayreportarray as $key => $value) {

    $WHSE = $bayreportarray[$key]['WAREHOUSE'];
    $ITEM_NUMBER = $bayreportarray[$key]['ITEM_NUMBER'];
    $CUR_LOCATION = $bayreportarray[$key]['CUR_LOCATION'];
    $CURMAX = $bayreportarray[$key]['CURMAX'];
    $CURMIN = $bayreportarray[$key]['CURMIN'];
    $var_grid5 = $bayreportarray[$key]['LMGRD5'];
    $var_PCCHEIin = $bayreportarray[$key]['CPCCHEI'];
    $var_PCCLENin = $bayreportarray[$key]['CPCCLEN'];
    $var_PCCWIDin = $bayreportarray[$key]['CPCCWID'];
    $var_gridheight = $bayreportarray[$key]['LMHIGH'];
    $var_griddepth = $bayreportarray[$key]['LMDEEP'];
    $var_gridwidth = $bayreportarray[$key]['LMWIDE'];
    $var_caseqty = $bayreportarray[$key]['CPCCPKU'];
    $var_PCLIQU = '  ';
    $var_casetf = $bayreportarray[$key]['CASETF'];

    if ($var_casetf == 'Y' && substr($var_grid5, 2, 1) == 'S' && ($var_PCCHEIin * $var_PCCLENin * $var_PCCWIDin * $var_caseqty > 0 )) {
        $SUGGESTED_MAX_array = _truefitgrid2iterations_case($var_grid5, $var_gridheight, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCCHEIin, $var_PCCLENin, $var_PCCWIDin, $var_caseqty);
        $SUGGSTEDMAX = $SUGGESTED_MAX_array[1];
    } else {
        $SUGGSTEDMAX = $bayreportarray[$key]['SUGGSTEDMAX'];
    }


    $CURRENT_IMPMOVES = intval($bayreportarray[$key]['CURRENT_IMPMOVES'] * 253);
    $SHIP_QTY_MN = $bayreportarray[$key]['SHIP_QTY_MN'];
    $CPCCPKU = 1;
    $AVG_DAILY_UNIT = $bayreportarray[$key]['AVG_DAILY_UNIT'];
    $AVG_INV_OH = $bayreportarray[$key]['AVG_INV_OH'];
    $AVGD_BTW_SLE = $bayreportarray[$key]['AVGD_BTW_SLE'];
    $minmax_reviewdate = $bayreportarray[$key]['minmax_reviewdate'];
    $COMMENTS = $bayreportarray[$key]['COMMENTS'];

    $newmin = ceil(_minloc($SUGGSTEDMAX, $AVG_DAILY_UNIT, $CPCCPKU));
    $newimpliedmoves = intval(_implied_daily_moves_nomin($SUGGSTEDMAX, $AVG_DAILY_UNIT, $AVG_INV_OH));
    $replenreduction = intval($CURRENT_IMPMOVES - $newimpliedmoves);
    $bayreportarray[$key]['SUGGSTEDMIN'] = $newmin;
    $bayreportarray[$key]['IMP_IMPMOVES'] = $newimpliedmoves;
    $bayreportarray[$key]['REPLENRED'] = $replenreduction;
    $bayreportarray[$key]['CURRENT_IMPMOVES'] = $CURRENT_IMPMOVES;
    unset($bayreportarray[$key]['SHIP_QTY_MN']);
    unset($bayreportarray[$key]['AVG_DAILY_UNIT']);
    unset($bayreportarray[$key]['AVG_INV_OH']);
    unset($bayreportarray[$key]['AVGD_BTW_SLE']);


    if (($newimpliedmoves - $CURRENT_IMPMOVES) <= -.1) {

        $rowpush = array(' ', $WHSE, $ITEM_NUMBER, $CUR_LOCATION, $CURMAX, $CURMIN, $CURRENT_IMPMOVES, $SUGGSTEDMAX, $newmin, $newimpliedmoves, $replenreduction, $minmax_reviewdate, $COMMENTS,);
        $row[] = array_values($rowpush);
    }
}


//How many have been marked as reviewed
$badge = $conn1->prepare("SELECT 
                                                            COUNT(*) AS opencount
                                                        FROM
                                                            gillingham.minmaxreview
                                                                JOIN
                                                            gillingham.slotmaster ON slotmaster_item = minmax_item
                                                                AND slotmaster_loc = minmax_location ");
$badge->execute();
$badgearray = $badge->fetchAll(pdo::FETCH_ASSOC);

if (isset($badgearray)) {
    $badgecount = $badgearray[0]['opencount'];
} else {
    $badgecount = 0;
}

$maxmincount = count($row) - $badgecount;


//update the maxmin badge
$sql = "UPDATE gillingham.badges SET maxmin =  $maxmincount WHERE whse = 1;";
$query = $conn1->prepare($sql);
$query->execute();

$output = array(
    "aaData" => array()
);

$output['aaData'] = $row;
echo json_encode($output);
