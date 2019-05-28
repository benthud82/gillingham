
<?php

include_once '../connection/NYServer.php';
date_default_timezone_set('Europe/London');
$datetime = date('Y-m-d');
$autoid = 0;

$itemnum = intval($_POST['itemnum']);
$userid = ($_POST['userid']);
$location = ($_POST['location']);





$columns = 'locdim_id, locdim_item, locdim_location, locdim_tsmid, locdim_reviewdate';
$values = "0, '$itemnum', '$location', '$userid' , '$datetime'";


$sql = "INSERT INTO gillingham.locdimreview ($columns) VALUES ($values)";
$query = $conn1->prepare($sql);
$query->execute();

