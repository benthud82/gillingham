<?php

ini_set('max_execution_time', 99999);
ini_set('memory_limit', '-1');
include_once '../connection/NYServer.php';
date_default_timezone_set('Europe/London');
$datetime = date('Y-m-d');
$previous7days = date('Y-m-d', strtotime('-7 days'));


$sqldelete = "TRUNCATE TABLE gillingham.slottingscore";
$querydelete = $conn1->prepare($sqldelete);
$querydelete->execute();

$columns = 'SCORE_WHSE, SCORE_ITEM, SCORE_PKGU, SCORE_ZONE, SCORE_TOTALSCORE, SCORE_REPLENSCORE, SCORE_WALKSCORE, SCORE_TOTALSCORE_OPT, SCORE_REPLENSCORE_OPT, SCORE_WALKSCORE_OPT';

$scoresql = $conn1->prepare("INSERT INTO gillingham.slottingscore
    SELECT 
    A.WAREHOUSE,
    A.ITEM_NUMBER,
    A.PACKAGE_UNIT,
    A.PACKAGE_TYPE,
    CASE
        WHEN 1 - (((abs(A.CURRENT_IMPMOVES) / 15) / .052632)) < 0 THEN 0
        ELSE 1 - (((abs(A.CURRENT_IMPMOVES) / 15) / .052632))
    end * CASE
        WHEN 1 - (((abs(B.OPT_ADDTLFTPERDAY) / 1000 / 1.4) / .052632)) < 0 THEN 0
        ELSE 1 - (((abs(B.OPT_ADDTLFTPERDAY) / 1000 / 1.4) / .052632))
    end as SCORE_TOTALSCORE,
    CASE
        WHEN 1 - (((abs(A.CURRENT_IMPMOVES) / 15) / .052632)) < 0 THEN 0
        ELSE 1 - (((abs(A.CURRENT_IMPMOVES) / 15) / .052632))
    end as SCORE_REPLENSCORE,
    CASE
        WHEN 1 - (((abs(B.OPT_ADDTLFTPERDAY) / 1000 / 1.4) / .052632)) < 0 THEN 0
        ELSE 1 - (((abs(B.OPT_ADDTLFTPERDAY) / 1000 / 1.4) / .052632))
    end as SCORE_WALKSCORE,
    CASE
        WHEN 1 - (((abs(A.SUGGESTED_IMPMOVES) / 15) / .052632)) < 0 THEN 0
        ELSE 1 - (((abs(A.SUGGESTED_IMPMOVES) / 15) / .052632))
    end * CASE
        WHEN 1 - (((abs(0) / 1.4) / .052632)) < 0 THEN 0
        ELSE 1 - (((abs(0) / 1.4) / .052632))
    end as SCORE_TOTALSCORE_OPT,
    CASE
        WHEN 1 - (((abs(A.SUGGESTED_IMPMOVES) / 15) / .052632)) < 0 THEN 0
        ELSE 1 - (((abs(A.SUGGESTED_IMPMOVES) / 15) / .052632))
    end as SCORE_REPLENSCORE_OPT,
    CASE
        WHEN 1 - (((abs(0) / 1.4) / .052632)) < 0 THEN 0
        ELSE 1 - (((abs(0) / 1.4) / .052632))
    end as SCORE_WALKSCORE_OPT
FROM
    gillingham.my_npfmvc A
        join
    gillingham.optimalbay B ON A.WAREHOUSE = B.OPT_WHSE
        and A.ITEM_NUMBER = B.OPT_ITEM
        and A.PACKAGE_UNIT = B.OPT_PKGU
        and A.PACKAGE_TYPE = B.OPT_CSLS
        WHERE
   PACKAGE_TYPE in ('EA')");
$scoresql->execute();


//score_loose100
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
$loosescore_bottom100 = number_format($loosescore_100dataarray[0]['loosescore_bottom100'] * 100, 1);

//score_loose1000
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
$loosescore_bottom1000 = number_format($loosescore_1000dataarray[0]['loosescore_bottom1000'] * 100, 1);

//score_looseall
$loosescore_alldata = $conn1->prepare("SELECT 
                                avg(items.SCORE_TOTALSCORE) as loosescore_bottomall
                            FROM
                                (SELECT 
                                    B.SCORE_TOTALSCORE
                                from
                                    gillingham.slottingscore B
                                WHERE
                                   B.SCORE_ZONE in ('EA')
                                ORDER BY B.SCORE_TOTALSCORE asc) items");
$loosescore_alldata->execute();
$loosescore_alldataarray = $loosescore_alldata->fetchAll(pdo::FETCH_ASSOC);
$loosescore_bottomall = number_format($loosescore_alldataarray[0]['loosescore_bottomall'] * 100, 1);



//loose walk reduction
$walkred_loose = $conn1->prepare("SELECT 
                                        SUM(OPT_ADDTLFTPERDAY)  as WALKTIMEREDLOOSE
                                    FROM
                                        gillingham.optimalbay
                                    WHERE
                                        OPT_CSLS in ('EA')");
$walkred_loose->execute();
$walkred_loosearray = $walkred_loose->fetchAll(pdo::FETCH_ASSOC);
$walkred_loose_miles = ($walkred_loosearray[0]['WALKTIMEREDLOOSE']);

//loose replen reduction
$replenred_loose = $conn1->prepare("SELECT 
                                SUM(CURRENT_IMPMOVES) - 
                                    SUM(SUGGESTED_IMPMOVES) as REPLENREDLOOSE
                            FROM
                                gillingham.my_npfmvc
                            WHERE
                                PACKAGE_TYPE in ('EA')");
$replenred_loose->execute();
$replenred_loosearray = $replenred_loose->fetchAll(pdo::FETCH_ASSOC);

$replenred_loose_moves = number_format($replenred_loosearray[0]['REPLENREDLOOSE'], 1);






$casescore_bottom100 = $casescore_bottom1000 = $casescore_bottomall = $walkred_casearray_hours = $replenred_casearray_moves = 0;

//insert into table slottingscore_hist

$result1 = $conn1->prepare("INSERT INTO gillingham.slottingscore_hist(slottingscore_hist_WHSE, slottingscore_hist_DATE, slottingscore_hist_LSE100, slottingscore_hist_LSE1000, slottingscore_hist_LSEALL, slottingscore_hist_CSE100, slottingscore_hist_CSE1000, slottingscore_hist_CSEALL, slottingscore_hist_LSEWALK, slottingscore_hist_LSEMOVES, slottingscore_hist_CSEHOURS, slottingscore_hist_CSEMOVES)
                                VALUES (1, '$datetime', '$loosescore_bottom100', '$loosescore_bottom1000', '$loosescore_bottomall', '$casescore_bottom100', '$casescore_bottom1000', '$casescore_bottomall', '$walkred_loose_miles', '$replenred_loose_moves', '$walkred_casearray_hours', '$replenred_casearray_moves')
                                ON DUPLICATE KEY UPDATE slottingscore_hist_LSE100=VALUES(slottingscore_hist_LSE100), slottingscore_hist_LSE1000=VALUES(slottingscore_hist_LSE1000), slottingscore_hist_LSEALL=VALUES(slottingscore_hist_LSEALL), slottingscore_hist_CSE100=VALUES(slottingscore_hist_CSE100), slottingscore_hist_CSE1000=VALUES(slottingscore_hist_CSE1000), slottingscore_hist_CSEALL=VALUES(slottingscore_hist_CSEALL), slottingscore_hist_LSEWALK=VALUES(slottingscore_hist_LSEWALK), slottingscore_hist_LSEMOVES=VALUES(slottingscore_hist_LSEMOVES), slottingscore_hist_CSEHOURS=VALUES(slottingscore_hist_CSEHOURS), slottingscore_hist_CSEMOVES=VALUES(slottingscore_hist_CSEMOVES)");
$result1->execute();


$result6 = $conn1->prepare("INSERT INTO gillingham.picksbybay
                            SELECT 
                                1,PICKDATE, L.BAY AS BAY, COUNT(*) AS PICKCOUNT
                            FROM
                                gillingham.gill_raw
                                    JOIN
                                gillingham.slotmaster ON slotmaster_item = ITEM and PKTYPE = slotmaster_pkgu
                                    JOIN
                                gillingham.bay_location L ON L.LOCATION = slotmaster_loc
                            WHERE
                                PICKDATE >= '$previous7days'
                            GROUP BY 1, PICKDATE , BAY
                            ON DUPLICATE KEY UPDATE picksbybay_PICKS=VALUES(picksbybay_PICKS)");
$result6->execute();







//old end of for each loop
//replen history by bay
$result2 = $conn1->prepare("INSERT INTO gillingham.replen_hist (replen_whse, replen_date, replen_bay, replen_replens) 
                            SELECT 
                                WAREHOUSE,
                                CURDATE(),
                                BAY,
                                SUM(CURRENT_IMPMOVES - SUGGESTED_IMPMOVES) * 253 AS YEARLYMOVES
                            FROM
                                gillingham.my_npfmvc
                                    JOIN
                                gillingham.optimalbay ON OPT_ITEM = ITEM_NUMBER
                                    JOIN
                                gillingham.bay_location ON LOCATION = CUR_LOCATION
                            GROUP BY WAREHOUSE , CURDATE() , BAY
                            ON DUPLICATE KEY UPDATE replen_replens=VALUES(replen_replens)");
$result2->execute();

$result3 = $conn1->prepare("INSERT INTO gillingham.walk_hist (walk_whse, walk_date, walk_bay, walk_walkfeet) 
                                                            SELECT 
                                                                OPT_WHSE,
                                                                CURDATE(),
                                                                L.BAY,
                                                                SUM(OPT_ADDTLFTPERDAY) * 253 AS YEARLYFEET
                                                            FROM
                                                                gillingham.optimalbay
                                                                    JOIN
                                                                gillingham.my_npfmvc ON OPT_ITEM = ITEM_NUMBER
                                                                    JOIN
                                                                gillingham.bay_location L ON L.LOCATION = CUR_LOCATION
                                                            GROUP BY OPT_WHSE , CURDATE() , L.BAY
                                                            ON DUPLICATE KEY UPDATE walk_walkfeet=VALUES(walk_walkfeet)");
$result3->execute();


//historical feet summary graph update
$result4 = $conn1->prepare(" INSERT INTO gillingham.feetperpick_summary (fpp_whse, fpp_date, fpp_totalfeet, fpp_fpp) 
                            SELECT 
                                'GB0001' as picksbybay_WHSE,
                                picksbybay_DATE,
                                sum(picksbybay_PICKS * WALKFEET) / 1000 as fpp_totalfeet,
                                sum(picksbybay_PICKS * WALKFEET) / sum(picksbybay_PICKS) / 1000 as fpp_fpp
                            FROM
                                gillingham.picksbybay
                                    left join
                                gillingham.vectormap ON picksbybay_BAY = BAY
                            WHERE
                                picksbybay_DATE >= '$previous7days'
                               -- picksbybay_DATE >= '2017-07-01'
                            GROUP BY picksbybay_DATE , picksbybay_WHSE
                          ON DUPLICATE KEY UPDATE fpp_totalfeet=VALUES(fpp_totalfeet), fpp_fpp=VALUES(fpp_fpp)");
$result4->execute();


//map errors for locations in slot master not mapped
$sqldelete2 = "TRUNCATE TABLE gillingham.vectormaperrors";
$querydelete2 = $conn1->prepare($sqldelete2);
$querydelete2->execute();

$result5 = $conn1->prepare("INSERT IGNORE INTO gillingham.vectormaperrors (maperror_bay, maperror_tier)
                                                        SELECT DISTINCT
                                                                L.BAY AS SLOTBAY,
                                                                slotmaster_tier
                                                            FROM
                                                                gillingham.slotmaster
                                                                    JOIN
                                                                gillingham.bay_location L ON L.LOCATION = slotmaster_loc
                                                                    LEFT JOIN
                                                                gillingham.vectormap V ON V.BAY = L.BAY
                                                            WHERE
                                                                slotmaster_allowpick = 'Y'
                                                                    AND WALKFEET IS NULL");
$result5->execute();


//update the bottom100
foreach ($whsearray as $whse) {
    $sql = "UPDATE gillingham.slottingscore dest,
                                (SELECT 
                                    *
                                FROM
                                    gillingham.slottingscore
                                WHERE
                                    SCORE_ZONE IN ('EA')
                                ORDER BY SCORE_TOTALSCORE , SCORE_REPLENSCORE , SCORE_WALKSCORE
                                LIMIT 100) src 
                            SET 
                                dest.SCORE_BOTTOM100 = 1
                            WHERE
                               dest.SCORE_ITEM = src.SCORE_ITEM
                                    AND dest.SCORE_ZONE = src.SCORE_ZONE;";
    $query = $conn1->prepare($sql);
    $query->execute();
}

//update the bottom1000
foreach ($whsearray as $whse) {
    $sql = "UPDATE gillingham.slottingscore dest,
                                    (SELECT 
                                        *
                                    FROM
                                        gillingham.slottingscore
                                    WHERE
                                        SCORE_ZONE IN ('EA')
                                    ORDER BY SCORE_TOTALSCORE , SCORE_REPLENSCORE , SCORE_WALKSCORE
                                    LIMIT 1000) src 
                                SET 
                                    dest.SCORE_BOTTOM1000 = 1
                                WHERE
                                   dest.SCORE_ITEM = src.SCORE_ITEM
                                        AND dest.SCORE_ZONE = src.SCORE_ZONE;";
    $query = $conn1->prepare($sql);
    $query->execute();
}

//update slotting historical scores by item.

$result6 = $conn1->prepare("INSERT IGNORE into gillingham.slottingscore_hist_item
                                                        SELECT 
                                                            WAREHOUSE,
                                                            ITEM_NUMBER,
                                                            PACKAGE_UNIT,
                                                            CUR_LOCATION,
                                                            LMTIER,
                                                            SUGGESTED_TIER,
                                                            LMGRD5,
                                                            SUGGESTED_GRID5,
                                                            SUGGESTED_DEPTH,
                                                            SUGGESTED_SLOTQTY,
                                                            SUGGESTED_MAX,
                                                            CURRENT_IMPMOVES,
                                                            SUGGESTED_IMPMOVES,
                                                            AVG_DAILY_PICK,
                                                            AVG_DAILY_UNIT,
                                                            SCORE_TOTALSCORE,
                                                            SCORE_TOTALSCORE_OPT,
                                                            SCORE_REPLENSCORE,
                                                            SCORE_REPLENSCORE_OPT,
                                                            SCORE_WALKSCORE,
                                                            SCORE_WALKSCORE_OPT,
                                                            SCORE_BOTTOM100,
                                                            SCORE_BOTTOM1000,
                                                            CURDATE()
                                                        FROM
                                                            gillingham.slottingscore
                                                                JOIN
                                                            gillingham.my_npfmvc ON 
                                                                SCORE_ITEM = ITEM_NUMBER
                                                                AND SCORE_ZONE = PACKAGE_TYPE");
$result6->execute();
