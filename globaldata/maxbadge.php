<?php

//get whse for user
if (isset($_SESSION['MYUSER'])) {
    $var_userid = $_SESSION['MYUSER'];


    $badge = $conn1->prepare("SELECT  count(*) as opencount FROM gillingham.slottingdb_itemactions WHERE openactions_assignedto = '$var_userid' and openactions_status = 'OPEN'");
    $badge->execute();
    $badgearray = $badge->fetchAll(pdo::FETCH_ASSOC);
}
if (isset($badgearray)) {
    $badgecount = $badgearray[0]['opencount'];
} else {
    $badgecount = 0;
}