<?php
$TOP_SCORE = $conn1->prepare("SELECT DISTINCT
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
                                                                    C.slotmaster_normreplen + slotmaster_maxreplen AS VCCTRF,
                                                                    E.SCORE_TOTALSCORE,
                                                                    E.SCORE_REPLENSCORE,
                                                                    E.SCORE_WALKSCORE,
                                                                    E.SCORE_TOTALSCORE_OPT,
                                                                    E.SCORE_REPLENSCORE_OPT,
                                                                    E.SCORE_WALKSCORE_OPT,
                                                                    'Y' AS CPCPFRC,
                                                                    'Y' AS CPCPFRA,
                                                                    L.WALKBAY
                                                                FROM
                                                                    gillingham.my_npfmvc A
                                                                        JOIN
                                                                    gillingham.optimalbay B ON A.ITEM_NUMBER = B.OPT_ITEM
                                                                        JOIN
                                                                    gillingham.slotmaster C ON C.slotmaster_item = A.ITEM_NUMBER
                                                                        JOIN
                                                                    gillingham.slottingscore E ON E.SCORE_ITEM = A.ITEM_NUMBER
                                                                        JOIN
                                                                    gillingham.bay_location L ON L.LOCATION = A.CUR_LOCATION
                                                                WHERE
                                                                    A.SUGGESTED_TIER NOT IN ('PALL')
                                                                ORDER BY E.SCORE_TOTALSCORE ASC , E.SCORE_REPLENSCORE , E.SCORE_WALKSCORE
                                                                    LIMIT $returncount");
$TOP_SCORE->execute();
$TOP_REPLEN_COST_array = $TOP_SCORE->fetchAll(pdo::FETCH_ASSOC);

