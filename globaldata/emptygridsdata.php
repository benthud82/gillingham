<?php
$suggestedtier = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_TIER'];



$EMPTYGRID_result = $conn1->prepare(" SELECT DISTINCT
                                                                                    LOC_DIM AS LMGRD5,
                                                                                    M.TIER AS LMTIER,
                                                                                    USE_HEIGHT AS LMHIGH,
                                                                                    USE_DEPTH AS LMDEEP,
                                                                                    USE_WIDTH AS LMWIDE,
                                                                                    CONCAT(M.TIER, LOC_DIM, WALKFEET * 1) AS EMPTYGRID,
                                                                                    USE_CUBE
                                                                                FROM
                                                                                    gillingham.location_master M
                                                                                        JOIN
                                                                                    gillingham.bay_location B ON B.LOCATION = M.LOCATION
                                                                                        JOIN
                                                                                    gillingham.vectormap V ON V.BAY = B.BAY
                                                                                        JOIN
                                                                                    gillingham.emptylocations ON emptylocation = M.LOCATION
                                                                                WHERE
                                                                                    ALLOW_PICK = 'Y' AND M.TIER = '$suggestedtier'
                                                                                        AND WALKFEET = $OPT_OPTWALKFEET
                                                                                ORDER BY USE_CUBE DESC");
$EMPTYGRID_result->execute();
$EMPTYGRID_array = $EMPTYGRID_result->fetchAll(pdo::FETCH_ASSOC);
