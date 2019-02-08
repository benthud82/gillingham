<?php
$var_whse = 'GB0001';


$lo4sql = $conn1->prepare("SELECT slotmaster_tier, sum(slotmaster_usecube) * 1000 as L04VOL FROM gillingham.slotmaster WHERE slotmaster_tier = 'L04' GROUP BY slotmaster_tier;");
$lo4sql->execute();
$lo4sqlarray = $lo4sql->fetchAll(pdo::FETCH_ASSOC);

$availl04vol = number_format($lo4sqlarray[0]['L04VOL'],2);

$lo4sql2 = $conn1->prepare("SELECT SUGGESTED_TIER, SUM(SUGGESTED_NEWLOCVOL) as USEDL04VOL, COUNT(*) FROM gillingham.my_npfmvc WHERE  SUGGESTED_TIER = 'L04' group by SUGGESTED_TIER;");
$lo4sql2->execute();
$lo4sql2array = $lo4sql2->fetchAll(pdo::FETCH_ASSOC);

$usedl04vol = number_format($lo4sql2array[0]['USEDL04VOL'],2);

$l04capacity = $usedl04vol / $availl04vol;
