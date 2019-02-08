
<?php
include_once '../connection/connection_details.php';
$var_userid = $_POST['userid'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);
$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];


ini_set('max_execution_time', 99999);

date_default_timezone_set('Europe/London');

$var_zone = $_POST['zonesel'];  //pulled from heatmap.php
$var_datesel = date("Y-m-d", strtotime($_POST['datesel']));  //pulled from heatmap.php


if ($var_zone == 'L%') {


    $baycolor = $conn1->prepare("SELECT 
                                vectormap . *,
                                BAY,
                                case
                                    when BAY like 'NON%' then 'GRAY'
                                    when BAY like 'NOT%' then 'GRAY'
                                    when BAY  like 'BLACK' then 'BLACK'
                                    when BAY  like 'BF0100' then '#BF0100'
                                    when BAY  like 'C84007' then '#C84007'
                                    when BAY  like 'D1800F' then '#D1800F'
                                    when BAY  like 'DAC118' then '#DAC118'
                                    when BAY  like 'C4E321' then '#C4E321'
                                    when BAY  like 'GREEN' then 'GREEN'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) >= 30 then 'BLACK'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) >= 25 then '#BF0100'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) >= 12 then '#C84007'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) >= 7 then '#D1800F'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) >= 3 then '#DAC118'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) >= 1 then '#C4E321'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) <= 0 then 'WHITESMOKE'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) is null then 'WHITESMOKE'
                                    else 'GREEN'
                                end as SCORECOLOR,
                                sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) as TOTPICK,
                                sum(optbayhist_count) as COUNT
                                FROM
                                gillingham.vectormap
                                    LEFT JOIN
                                gillingham.optimalbay_hist ON  optbayhist_bay = BAY
                                GROUP BY BAY;");  //$orderby pulled from: include 'slopecat_switch_orderby.php';
    $baycolor->execute();
    $baycolorarray = $baycolor->fetchAll(pdo::FETCH_ASSOC);
} else {


    $baycolor = $conn1->prepare("SELECT 
                                vectormap . *,
                                BAY,
                                case
                                    when BAY like 'NON%' then 'GRAY'
                                    when BAY like 'NOT%' then 'GRAY'
                                    when BAY  like 'BLACK' then 'BLACK'
                                    when BAY  like 'BF0100' then '#BF0100'
                                    when BAY  like 'C84007' then '#C84007'
                                    when BAY  like 'D1800F' then '#D1800F'
                                    when BAY  like 'DAC118' then '#DAC118'
                                    when BAY  like 'C4E321' then '#C4E321'
                                    when BAY  like 'GREEN' then 'GREEN'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) >= 30 then 'BLACK'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) >= 25 then '#BF0100'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) >= 12 then '#C84007'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) >= 7 then '#D1800F'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) >= 3 then '#DAC118'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) >= 1 then '#C4E321'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) <= 0 then 'WHITESMOKE'
                                    when sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) is null then 'WHITESMOKE'
                                    else 'GREEN'
                                end as SCORECOLOR,
                                sum(case when optbayhist_date = '$var_datesel' then optbayhist_pick else 0 end) as TOTPICK,
                                sum(optbayhist_count) as COUNT
                                FROM
                                gillingham.vectormap
                                    LEFT JOIN
                                gillingham.optimalbay_hist ON optbayhist_bay = BAY 
                                GROUP BY BAY;");  //$orderby pulled from: include 'slopecat_switch_orderby.php';
    $baycolor->execute();
    $baycolorarray = $baycolor->fetchAll(pdo::FETCH_ASSOC);
}





if ($var_zone == 'L%') {
    $loosetext = $conn1->prepare("SELECT 
                                *
                            FROM
                                gillingham.loosetext
                            WHERE
                                TEXT not like '%Replens%'");  //$orderby pulled from: include 'slopecat_switch_orderby.php';
    $loosetext->execute();
    $loosetextarray = $loosetext->fetchAll(pdo::FETCH_ASSOC);
} else {
    $loosetext = $conn1->prepare("SELECT 
                                *
                            FROM
                                gillingham.casetext
                            WHERE
                                 $sparkstextsql");  //$orderby pulled from: include 'slopecat_switch_orderby.php';
    $loosetext->execute();
    $loosetextarray = $loosetext->fetchAll(pdo::FETCH_ASSOC);
}
$screenfactor = 1;

$posfactor = 1;
$heiwidfactor = 5;

include 'heatmapdatecontainer.php';
?>



<div class="borderedcontainer">
    <svg id="svg2" width="100%" height="100%" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" >
        <?php
        //Populate text if checkbox is checked.
        foreach ($loosetextarray as $key => $value) {
            ?>
            <text transform = "translate(<?php echo $loosetextarray[$key]['XTRANS'] . ', ' . $loosetextarray[$key]['YTRANS'] . ') rotate(' . $loosetextarray[$key]['ROTATE'] . ')' ?>" font-family="'Open Sans', sans-serif" font-size="<?php echo $loosetextarray[$key]['FONTSIZE'] ?>" ><?php echo $loosetextarray[$key]['TEXT'] ?></text>
        <?php } ?>

        <?php foreach ($baycolorarray as $key => $value) { ?>
            <rect id="<?php echo $baycolorarray[$key]['BAY'] ?>" class="clickablesvg" x="<?php echo $baycolorarray[$key]['XPOS'] * $screenfactor ?>" y="<?php echo $baycolorarray[$key]['YPOS'] * $screenfactor ?>" width="<?php echo $baycolorarray[$key]['BAYWIDTH'] * $screenfactor ?>" height="<?php echo $baycolorarray[$key]['BAYHEIGHT'] * $screenfactor ?>" style="stroke:#464646; fill: <?php echo $baycolorarray[$key]['SCORECOLOR'] ?> "><title><?php echo $baycolorarray[$key]['BAY'] . ': ' . $baycolorarray[$key]['TOTPICK'] . ' Picks' ?></title></rect>
                <?php } ?>
    </svg>
</div>
