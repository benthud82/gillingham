
<?php
$var_whse = 'GB0001';
include_once '../connection/connection_details.php';
include '../sessioninclude.php';
$var_userid = $_SESSION['MYUSER'];

date_default_timezone_set('Europe/London');
$datetime = date('Y-m-d H:i:s');

$var_commentmodal = ($_POST['commentmodal']);

$var_assigntask_id = intval($_POST['assigntask_id']);


//update completed item task table and mark status as 'COMPLETE'


$sql = "UPDATE gillingham.slottingdb_itemactions SET openactions_completedcomment= '$var_commentmodal', openactions_completeduser = '$var_userid', openactions_status = 'COMPLETED', openactions_completeddate = '$datetime' WHERE openactions_id = $var_assigntask_id;";
$query = $conn1->prepare($sql);
$query->execute();


