<?php
$suggestedtier = $TOP_REPLEN_COST_array[$topcostkey]['SUGGESTED_TIER'];



$EMPTYGRID_result = $conn1->prepare("SELECT DISTINCT
                                                                                        slotmaster_dimgroup AS LMGRD5,
                                                                                        slotmaster_tier AS LMTIER,
                                                                                        slotmaster_usehigh AS LMHIGH,
                                                                                        slotmaster_usedeep AS LMDEEP,
                                                                                        slotmaster_usewide AS LMWIDE,
                                                                                        CONCAT(slotmaster_tier,
                                                                                                slotmaster_dimgroup,
                                                                                                WALKFEET * 1) AS EMPTYGRID,
                                                                                        slotmaster_usecube
                                                                                    FROM
                                                                                        gillingham.slotmaster
                                                                                            JOIN
                                                                                        gillingham.vectormap ON BAY = slotmaster_bay
                                                                                    WHERE
                                                                                        slotmaster_allowpick = 'Y'
                                                                                            AND slotmaster_item IS NULL
                                                                                    and slotmaster_tier = '$suggestedtier' and WALKFEET = $OPT_OPTBAYWALKFEET
                                                                                    ORDER BY slotmaster_usecube DESC");
$EMPTYGRID_result->execute();
$EMPTYGRID_array = $EMPTYGRID_result->fetchAll(pdo::FETCH_ASSOC);
