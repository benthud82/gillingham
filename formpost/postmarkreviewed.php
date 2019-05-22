
<?php

include_once '../connection/NYServer.php';
//date_default_timezone_set('America/New_York');
$datetime = date('Y-m-d');
$autoid = 0;

$itemnum = intval($_POST['itemnum']);
$userid = ($_POST['userid']);
$location = ($_POST['location']);





$columns = 'minmax_id, minmax_item, minmax_location, minmax_tsmid, minmax_reviewdate';
$values = "0, '$itemnum', '$location', '$userid' , '$datetime'";


$sql = "INSERT INTO gillingham.minmaxreview ($columns) VALUES ($values)";
$query = $conn1->prepare($sql);
$query->execute();

