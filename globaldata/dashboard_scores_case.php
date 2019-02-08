<?php
$var_whse = 'GB0001';

//$casescore_100data = $conn1->prepare("SELECT 
//                                        avg(items.SCORE_TOTALSCORE) as casescore_bottom100
//                                    FROM
//                                        (SELECT 
//                                            B.SCORE_TOTALSCORE, B.SCORE_WHSE, B.SCORE_ITEM, B.SCORE_PKGU, B.SCORE_ZONE
//                                        from
//                                            slotting.slottingscore B
//                                            join
//                                        slotting.my_npfmvc C ON C.WAREHOUSE = B.SCORE_WHSE
//                                            and C.ITEM_NUMBER = B.SCORE_ITEM
//                                            and C.PACKAGE_UNIT = B.SCORE_PKGU
//                                            and C.PACKAGE_TYPE = B.SCORE_ZONE
//                                        WHERE
//                                            B.SCORE_WHSE = $var_whse
//                                                and B.SCORE_ZONE in ('CSE' , 'PFR')
//                                        ORDER BY B.SCORE_TOTALSCORE asc
//                                        LIMIT 100) items");
//$casescore_100data->execute();
//$casescore_100dataarray = $casescore_100data->fetchAll(pdo::FETCH_ASSOC);

//$casescore_bottom100 = number_format($casescore_100dataarray[0]['casescore_bottom100'] * 100, 1).'%';
$casescore_bottom100 = number_format(0 * 100, 1).'%';

//$casescore_1000data = $conn1->prepare("SELECT 
//                                        avg(items.SCORE_TOTALSCORE) as casescore_bottom1000
//                                    FROM
//                                        (SELECT 
//                                            B.SCORE_TOTALSCORE, B.SCORE_WHSE, B.SCORE_ITEM, B.SCORE_PKGU, B.SCORE_ZONE
//                                        from
//                                            slotting.slottingscore B
//                                            join
//                                        slotting.my_npfmvc C ON C.WAREHOUSE = B.SCORE_WHSE
//                                            and C.ITEM_NUMBER = B.SCORE_ITEM
//                                            and C.PACKAGE_UNIT = B.SCORE_PKGU
//                                            and C.PACKAGE_TYPE = B.SCORE_ZONE
//                                        WHERE
//                                            B.SCORE_WHSE = $var_whse
//                                                and B.SCORE_ZONE in ('CSE' , 'PFR')
//                                        ORDER BY B.SCORE_TOTALSCORE asc
//                                        LIMIT 1000) items");
//$casescore_1000data->execute();
//$casescore_1000dataarray = $casescore_1000data->fetchAll(pdo::FETCH_ASSOC);

//$casescore_bottom1000 = number_format($casescore_1000dataarray[0]['casescore_bottom1000'] * 100, 1).'%';
$casescore_bottom1000 = number_format(0 * 100, 1).'%';
