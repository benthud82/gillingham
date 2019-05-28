
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

$includetoggle = $_GET['includeaudit'];

if ($includetoggle == 0){
    $includesql =  ' and locdim_reviewdate IS NULL';
} else{
    $includesql = ' ';
}


$dimissuesql = $conn1->prepare("SELECT DISTINCT
                                                                ' ',
                                                                slotmaster_loc,
                                                                slotmaster_item,
                                                                slotmaster_dimgroup,
                                                                slotmaster_usehigh,
                                                                slotmaster_usedeep,
                                                                slotmaster_usewide,
                                                                slotmaster_currtf,
                                                                (slotmaster_normreplen + slotmaster_maxreplen) AS loc_max,
                                                                EA_DEPTH,
                                                                EA_HEIGHT,
                                                                EA_WIDTH,
                                                                CONCAT(locdim_reviewdate, ' | ', locdim_tsmid) AS locdim_reviewdate,
                                                                CASE
                                                                    WHEN itemcomments_id > 0 THEN 'SHOW COMMENTS'
                                                                END AS COMMENTS
                                                            FROM
                                                                gillingham.slotmaster
                                                                    JOIN
                                                                gillingham.item_master ON slotmaster_item = ITEM
                                                                    LEFT JOIN
                                                                gillingham.locdimreview ON locdim_item = slotmaster_item
                                                                    AND locdim_location = slotmaster_loc
                                                                    LEFT JOIN
                                                                gillingham.slotting_itemcomments ON itemcomments_item = slotmaster_item
                                                            WHERE
                                                                slotmaster_currtf < (slotmaster_normreplen + slotmaster_maxreplen)
                                                                    AND slotmaster_tier = 'BIN'
                                                                    $includesql
                                                            ORDER BY slotmaster_currtf / (slotmaster_normreplen + slotmaster_maxreplen) , (slotmaster_normreplen + slotmaster_maxreplen) DESC");
$dimissuesql->execute();
$dimissuearray = $dimissuesql->fetchAll(pdo::FETCH_ASSOC);



$output = array(
    "aaData" => array()
);
$row = array();

foreach ($dimissuearray as $key => $value) {
    $row[] = array_values($dimissuearray[$key]);
}


//How many have been marked as reviewed
    $badge = $conn1->prepare("SELECT 
                                                            COUNT(*) AS opencount
                                                        FROM
                                                            gillingham.locdimreview
                                                                JOIN
                                                            gillingham.slotmaster ON slotmaster_item = locdim_item
                                                                AND slotmaster_loc = locdim_location ");
    $badge->execute();
    $badgearray = $badge->fetchAll(pdo::FETCH_ASSOC);

if (isset($badgearray)) {
    $badgecount = $badgearray[0]['opencount'];
} else {
    $badgecount = 0;
}


$dimcount = count($row) - $badgecount;
//update the maxmin badge
$sql = "UPDATE gillingham.badges SET dimissues =  $dimcount WHERE whse = 1;";
$query = $conn1->prepare($sql);
$query->execute();


$output['aaData'] = $row;
echo json_encode($output);
