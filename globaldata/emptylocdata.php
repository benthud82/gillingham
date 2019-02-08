<?php

$EMPTYLOC_result = $conn1->prepare("SELECT 
                                                                            CONCAT(slotmaster_tier,
                                                                                    slotmaster_dimgroup,
                                                                                    WALKFEET * 1) AS KEYVAL,
                                                                            CONCAT(slotmaster_tier,
                                                                                    slotmaster_dimgroup,
                                                                                    WALKBAY * 1) AS KEYVAL2,
                                                                            slotmaster_loc,
                                                                            slotmaster_item,
                                                                            slotmaster_allowpick,
                                                                            slotmaster_grdeep,
                                                                            slotmaster_grhigh,
                                                                            slotmaster_grwide,
                                                                            slotmaster_grcube,
                                                                            slotmaster_tier,
                                                                            slotmaster_dimgroup,
                                                                            WALKFEET,
                                                                            WALKBAY
                                                                        FROM
                                                                            gillingham.slotmaster
                                                                                JOIN
                                                                            gillingham.vectormap ON BAY = slotmaster_bay
                                                                                JOIN
                                                                            gillingham.bay_location ON LOCATION = slotmaster_loc
                                                                        WHERE
                                                                            slotmaster_allowpick = 'Y'
                                                                                AND slotmaster_item IS NULL");  
$EMPTYLOC_result->execute();
$EMPTYLOC_array = $EMPTYLOC_result->fetchAll(pdo::FETCH_ASSOC);