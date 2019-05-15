
<?php
include_once '../connection/NYServer.php';
include_once '../../globalfunctions/custdbfunctions.php';

$var_userid = $_POST['userid'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];
$var_item = $_POST['itemnum'];

$time = strtotime("-90 days", time());
$date = date("Y-m-d", $time);

$scoresql = $conn1->prepare("SELECT 
                                                                @curRank:=@curRank + 1 AS rank,
                                                                histitem_totalscore,
                                                                histitem_replenscore,
                                                                histitem_walkscore
                                                            FROM
                                                                gillingham.slottingscore_hist_item p,
                                                                (SELECT @curRank:=0) r
                                                            WHERE
                                                                    histitem_item = $var_item
                                                                    AND histitem_date >= $date
                                                            ORDER BY histitem_date ASC; ");
$scoresql->execute();
$scorearray = $scoresql->fetchAll(pdo::FETCH_ASSOC);

$rankarray = array_column($scorearray, 'rank');
$totalscorearray = array_column($scorearray, 'histitem_totalscore');
$replenscorearray = array_column($scorearray, 'histitem_replenscore');
$walkscorearray = array_column($scorearray, 'histitem_walkscore');

$totalscoretrend = linear_regression($rankarray, $totalscorearray);
$totalscoretrend_m = $totalscoretrend['m'];
if ($totalscoretrend_m >= 0) {
    $totalscoretrend_color = 'green-jungle';
} else {
    $totalscoretrend_color = 'red-intense';
}

$replenscoretrend = linear_regression($rankarray, $replenscorearray);
$replenscoretrend_m = $replenscoretrend['m'];
if ($replenscoretrend_m >= 0) {
    $replenscoretrend_color = 'green-jungle';
} else {
    $replenscoretrend_color = 'red-intense';
}

$walkscoretrend = linear_regression($rankarray, $walkscorearray);
$walkscoretrend_m = $walkscoretrend['m'];
if ($walkscoretrend_m >= 0) {
    $walkscoretrend_color = 'green-jungle';
} else {
    $walkscoretrend_color = 'red-intense';
}
?>

<div class="row" style="padding-top: 25px">
    <div class="col-lg-4 " id="stat_totalscoretrend">
        <div class="dashboard-stat dashboard-stat-v2 <?php echo $totalscoretrend_color; ?>">  
            <div class="visual">
                <i class="fa fa-info-circle"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="1349"><?php echo number_format($totalscoretrend_m * 100, 2); ?></span>
                </div>
                <div class="desc"><?php echo count($totalscorearray); ?> Day Total Score Trend</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 " id="stat_replenscoretrend">
        <div class="dashboard-stat dashboard-stat-v2 <?php echo $replenscoretrend_color; ?>">  
            <div class="visual">
                <i class="fa fa-info-circle"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="1349"><?php echo number_format($replenscoretrend_m * 100, 2); ?></span>
                </div>
                <div class="desc"><?php echo count($replenscorearray); ?> Day Replen Score Trend</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 " id="stat_walkscoretrend">
        <div class="dashboard-stat dashboard-stat-v2 <?php echo $walkscoretrend_color; ?>">  
            <div class="visual">
                <i class="fa fa-info-circle"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="1349"><?php echo number_format($walkscoretrend_m * 100, 2); ?></span>
                </div>
                <div class="desc"><?php echo count($walkscorearray); ?> Day Replen Score Trend</div>
            </div>
        </div>
    </div>



</div>
