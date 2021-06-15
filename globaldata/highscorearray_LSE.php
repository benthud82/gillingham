<?php
$TOP_SCORE = $conn1->prepare("SELECT 
                                                                        A.*,
                                                                        B.OPT_PPCCALC,
                                                                        B.OPT_OPTBAY,
                                                                        B.OPT_CURRBAY,
                                                                        B.OPT_CURRDAILYFT,
                                                                        B.OPT_SHLDDAILYFT,
                                                                        B.OPT_ADDTLFTPERPICK,
                                                                        B.OPT_ADDTLFTPERDAY,
                                                                        B.OPT_WALKCOST,
                                                                        C.slotmaster_normreplen + slotmaster_maxreplen AS CURMAX,
                                                                        C.slotmaster_normreplen AS CURMIN,
                                                                        E.SCORE_TOTALSCORE,
                                                                        E.SCORE_REPLENSCORE,
                                                                        E.SCORE_WALKSCORE,
                                                                        E.SCORE_TOTALSCORE_OPT,
                                                                        E.SCORE_REPLENSCORE_OPT,
                                                                        E.SCORE_WALKSCORE_OPT,
                                                                        'Y' AS CPCPFRC,
                                                                        'Y' AS CPCPFRA,
                                                                        (SELECT 
                                                                                walkfeet_feet
                                                                            FROM
                                                                                gillingham.walkfeet_standard
                                                                            WHERE
                                                                                walkfeet_bay = B.OPT_OPTBAY) AS SUGG_WALKFEET,
                                                                        openactions_assignedto,
                                                                        openactions_comment
                                                                    FROM
                                                                        gillingham.my_npfmvc A
                                                                            JOIN
                                                                        gillingham.optimalbay B ON A.ITEM_NUMBER = B.OPT_ITEM
                                                                            AND OPT_CSLS = PACKAGE_TYPE
                                                                            JOIN
                                                                        gillingham.slotmaster C ON C.slotmaster_item = A.ITEM_NUMBER
                                                                            AND slotmaster_pkgu = PACKAGE_TYPE
                                                                            JOIN
                                                                        gillingham.slottingscore E ON E.SCORE_ITEM = A.ITEM_NUMBER
                                                                            AND SCORE_ZONE = PACKAGE_TYPE
                                                                            JOIN
                                                                        gillingham.bay_location L ON L.LOCATION = A.CUR_LOCATION
                                                                            JOIN
                                                                        gillingham.vectormap V ON V.BAY = L.BAY
                                                                            LEFT JOIN
                                                                        gillingham.slottingdb_itemactions ON openactions_item = SCORE_ITEM
                                                                    WHERE
                                                                        A.SUGGESTED_TIER <> ('PALL')
                                                                        and A.NBR_SHIP_OCC >= 10
                                                                        and (openactions_status is NULL or openactions_status = 'OPEN')
                        --                                                and ITEM_NUMBER = 1128445
                                                                    $itemnumsql
                                                                    ORDER BY E.SCORE_TOTALSCORE ASC , E.SCORE_REPLENSCORE , E.SCORE_WALKSCORE
                                                                    LIMIT $returncount");
$TOP_SCORE->execute();
$TOP_REPLEN_COST_array = $TOP_SCORE->fetchAll(pdo::FETCH_ASSOC);

