<?php

include_once '../connection/connection_details.php';

$replenred_loose = $conn1->prepare("SELECT 
                                SUM(CURRENT_IMPMOVES) - 
                                    SUM(SUGGESTED_IMPMOVES) as REPLENREDLOOSE
                            FROM
                                gillingham.my_npfmvc
                            WHERE
                                 PACKAGE_TYPE in ('EA')");
$replenred_loose->execute();
$replenred_loosearray = $replenred_loose->fetchAll(pdo::FETCH_ASSOC);

echo intval($replenred_loosearray[0]['REPLENREDLOOSE']) . ' Moves';




