<?php

include_once '../connection/connection_details.php';

$walkred_loose = $conn1->prepare("SELECT 
                                        SUM(OPT_ADDTLFTPERDAY) / 1000 as WALKTIMEREDLOOSE
                                    FROM
                                        gillingham.optimalbay
                                    WHERE
                                        OPT_CSLS in ('EA')");
$walkred_loose->execute();
$walkred_loosearray = $walkred_loose->fetchAll(pdo::FETCH_ASSOC);

echo number_format($walkred_loosearray[0]['WALKTIMEREDLOOSE'],1) . ' Kilometers';