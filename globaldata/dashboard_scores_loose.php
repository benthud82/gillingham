<?php
$var_whse = 'GB0001';

$loosescore_100data = $conn1->prepare("SELECT 
                                avg(items.SCORE_TOTALSCORE) as loosescore_bottom100
                            FROM
                                (SELECT 
                                    B.SCORE_TOTALSCORE
                                from
                                    gillingham.slottingscore B
                                WHERE
                                     B.SCORE_ZONE in ('EA')
                                ORDER BY B.SCORE_TOTALSCORE asc
                                LIMIT 100) items");
$loosescore_100data->execute();
$loosescore_100dataarray = $loosescore_100data->fetchAll(pdo::FETCH_ASSOC);

$loosescore_bottom100 = number_format($loosescore_100dataarray[0]['loosescore_bottom100'] * 100, 1).'%';

$loosescore_1000data = $conn1->prepare("SELECT 
                                avg(items.SCORE_TOTALSCORE) as loosescore_bottom1000
                            FROM
                                (SELECT 
                                    B.SCORE_TOTALSCORE
                                from
                                    gillingham.slottingscore B
                                WHERE
                                    B.SCORE_ZONE in ('EA')
                                ORDER BY B.SCORE_TOTALSCORE asc
                                LIMIT 1000) items");
$loosescore_1000data->execute();
$loosescore_1000dataarray = $loosescore_1000data->fetchAll(pdo::FETCH_ASSOC);

$loosescore_bottom1000 = number_format($loosescore_1000dataarray[0]['loosescore_bottom1000'] * 100, 1).'%';
