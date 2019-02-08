<?php


$userset = $conn1->prepare("SELECT idslottingDB_users_ID from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$sessionuser'");
$userset->execute();
$usersetarray = $userset->fetchAll(pdo::FETCH_ASSOC);

