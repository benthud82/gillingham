<?php

//sql statement for daily pick.  This is used for all off system slotting daily pick calculations and sorts

$sql_dailypick = '    CASE
                                            WHEN A.ADBS >= 365 THEN 0
                                            WHEN A.DSLS >= 180 THEN 0
                                            WHEN
                                                A.AVG_PICK > A.AVG_UNITS
                                            THEN
                                                (A.AVG_UNITS / (CASE
                                                    WHEN X.CPCCPKU > 0 THEN X.CPCCPKU
                                                    ELSE 1
                                                END)) / A.ADBS
                                            WHEN
                                                A.ADBS = 0
                                                    AND A.DSLS = 0
                                            THEN
                                                A.AVG_PICK
                                            WHEN A.ADBS = 0 THEN (A.AVG_PICK / A.DSLS)
                                            ELSE (A.AVG_PICK / A.ADBS)
                                        END';

$sql_dailyunit = 'CASE
        WHEN A.ADBS >= 365 THEN 0
        WHEN A.DSLS >= 180 THEN 0
        WHEN A.AVG_PICK > A.AVG_UNITS THEN A.AVG_UNITS / A.ADBS
        WHEN
            A.ADBS = 0
                AND A.DSLS = 0
        THEN
            A.AVG_UNITS
        WHEN A.ADBS = 0 THEN (A.AVG_UNITS / A.DSLS)
        ELSE (A.AVG_UNITS / A.ADBS)
    END';