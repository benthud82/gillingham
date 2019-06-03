
<?php

include_once '../connection/NYServer.php';
include '../sessioninclude.php';
$var_userid = $_SESSION['MYUSER'];

$var_whse = 'GB0001';

date_default_timezone_set('Europe/London');
$datetime = date('Y-m-d H:i:s');

$var_descriptionmodal = ($_POST['descriptionmodal']);
$var_commentmodal = ($_POST['commentmodal']);
$var_itemmodal = ($_POST['itemmodal']);



$columns = 'itemcomments_id, itemcomments_whse, itemcomments_item, itemcomments_tsm, itemcomments_date, itemcomments_header, itemcomments_comment';
$values = "0, '$var_whse', $var_itemmodal, '$var_userid' , '$datetime', '$var_descriptionmodal', '$var_commentmodal'";


$sql = "INSERT INTO gillingham.slotting_itemcomments ($columns) VALUES ($values)";
$query = $conn1->prepare($sql);
$query->execute();

