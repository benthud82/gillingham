<?php

$EMPTYLOC_result = $conn1->prepare("SELECT 
                                                                            CONCAT(M.TIER, LOC_DIM, V.WALKFEET * 1) AS KEYVAL,
                                                                            CONCAT(M.TIER, LOC_DIM, WALKBAY * 1) AS KEYVAL2,
                                                                            M.LOCATION,
                                                                            M.USE_DEPTH,
                                                                            M.USE_HEIGHT,
                                                                            M.USE_WIDTH,
                                                                            M.USE_CUBE,
                                                                            M.TIER,
                                                                            M.LOC_DIM,
                                                                            V.WALKFEET,
                                                                            WALKBAY
                                                                        FROM
                                                                            gillingham.location_master M
                                                                                JOIN
                                                                            gillingham.bay_location B ON B.LOCATION = M.LOCATION
                                                                                JOIN
                                                                            gillingham.vectormap V ON V.BAY = B.BAY
                                                                                JOIN
                                                                            gillingham.emptylocations on emptylocation = M.LOCATION
                                                                        WHERE
                                                                            ALLOW_PICK = 'Y'");  
$EMPTYLOC_result->execute();
$EMPTYLOC_array = $EMPTYLOC_result->fetchAll(pdo::FETCH_ASSOC);

