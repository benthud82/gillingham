<?php

include_once '../connection/NYServer.php';
$replenred_loose = $conn1->prepare("SELECT 
                                SUM(CURRENT_IMPMOVES) - 
                                    SUM(SUGGESTED_IMPMOVES) as REPLENREDCASE
                            FROM
                                gillingham.my_npfmvc
                            WHERE
                                PACKAGE_TYPE in ('CSE' , 'PFR')");
$replenred_loose->execute();
$replenred_loosearray = $replenred_loose->fetchAll(pdo::FETCH_ASSOC);

echo intval($replenred_loosearray[0]['REPLENREDCASE']) . ' Moves';