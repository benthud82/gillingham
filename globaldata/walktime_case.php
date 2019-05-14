<?php

include_once '../connection/NYServer.php';

$walkred_loose = $conn1->prepare("SELECT 
                                        (SUM(OPT_WALKCOST) / 253) / 19 as WALKTIMEREDCASE
                                    FROM
                                        slotting.optimalbay
                                    WHERE
                                        OPT_CSLS in ('CSE' , 'PFR')");
$walkred_loose->execute();
$walkred_loosearray = $walkred_loose->fetchAll(pdo::FETCH_ASSOC);

//echo number_format($walkred_loosearray[0]['WALKTIMEREDCASE'],1) . ' Hours';
echo number_format(0,1) . ' Hours';




