
<?php
include_once '../connection/NYServer.php';
$var_userid = $_POST['userid'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from slotting.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];
$var_item = $_POST['itemnum'];

?>

<h4>Go To Move Assistant<a href= "moveassist.php?itemnum=<?php echo $var_item ?>&userid=<?php echo $var_userid ?>"  target=_blank><i class='fa fa-external-link-square' style='cursor: pointer;     margin-left: 5px;' data-toggle='tooltip' data-title='Go to Move Assistant' data-placement='top' data-container='body' ></i></a></h4>


