
<?php

include_once '../connection/NYServer.php';
include '../sessioninclude.php';
$var_userid = $_SESSION['MYUSER'];
$var_whse = 'GB0001';
date_default_timezone_set('Europe/London');
$datetime = date('Y-m-d H:i:s');

$var_commentmodal = ($_POST['commentmodal']);
$var_itemmodal = ($_POST['itemmodal']);
$var_assignedtsm = ($_POST['assignedtsm']);
$status = 'OPEN';



//update completed item task table and mark status as 'COMPLETE'


$sql = "INSERT INTO gillingham.slottingdb_itemactions (openactions_id, openactions_whse, openactions_item, openactions_assignedby, openactions_assignedto, openactions_assigneddate, openactions_comment, openactions_status) VALUES (0, '$var_whse', $var_itemmodal, '$var_userid', '$var_assignedtsm', '$datetime', '$var_commentmodal', '$status');";
$query = $conn1->prepare($sql);
$query->execute();


