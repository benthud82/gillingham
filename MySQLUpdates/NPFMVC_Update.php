<?php

//haven't started on this.  This is pulled from the US main update php file
include_once '../connection/NYServer.php';
include_once '../globalfunctions/slottingfunctions.php';
include_once '../globalfunctions/newitem.php';
ini_set('max_execution_time', 99999);
ini_set('memory_limit', '-1');

$whssel = 'GB0001';

//assign columns variable for my_npfmvc table
$columns = 'WAREHOUSE, ITEM_NUMBER, PACKAGE_UNIT, PACKAGE_TYPE, CUR_LOCATION, DAYS_FRM_SLE, AVGD_BTW_SLE, AVG_INV_OH, NBR_SHIP_OCC, PICK_QTY_MN, PICK_QTY_SD, SHIP_QTY_MN, SHIP_QTY_SD,CPCEPKU,CPCCPKU,CPCFLOW,CPCTOTE,CPCSHLF,CPCROTA,CPCESTK,CPCLIQU,CPCELEN,CPCEHEI,CPCEWID,CPCCLEN,CPCCHEI,CPCCWID,LMHIGH,LMDEEP,LMWIDE,LMVOL9,LMTIER,LMGRD5,DLY_CUBE_VEL,DLY_PICK_VEL,SUGGESTED_TIER,SUGGESTED_GRID5,SUGGESTED_DEPTH,SUGGESTED_MAX,SUGGESTED_MIN,SUGGESTED_SLOTQTY,SUGGESTED_IMPMOVES,CURRENT_IMPMOVES,SUGGESTED_NEWLOCVOL,SUGGESTED_DAYSTOSTOCK, AVG_DAILY_PICK, AVG_DAILY_UNIT, VCBAY, JAX_ENDCAP';


//Assign items on hold
//include_once 'itemsonhold.php'; 

//still needs some work.  Two tasks on Monday's to update
//replace static variables with actual columns in below SQL statement
$L01GridsSQL = $conn1->prepare("SELECT DISTINCT
    'GB00001' AS WAREHOUSE,
    A.ITEM AS ITEM_NUMBER,
    A.PKGU AS PACKAGE_UNIT,
    A.PKTYPE AS PACKAGE_TYPE,
    D.slotmaster_loc AS LMLOC,
    A.DSLS AS DAYS_FRM_SLE,
    A.ADBS AS AVGD_BTW_SLE,
    A.AVG_INVOH AS AVG_INV_OH,
    A.DAYCOUNT AS NBR_SHIP_OCC,
    A.AVG_PICK AS PICK_QTY_MN,
    A.PICK_STD AS PICK_QTY_SD,
    A.AVG_UNITS AS SHIP_QTY_MN,
    A.UNIT_STD AS SHIP_QTY_SD,
    X.PKGU_EA AS CPCEPKU,
    X.PKGU_CA AS CPCCPKU,
    'Y' AS CPCFLOW,
    'Y' AS CPCTOTE,
    'Y' AS CPCSHLF,
    'Y' AS CPCROTA,
    0 AS CPCESTK,
    ' ' AS CPCLIQU,
    X.EA_DEPTH AS CPCELEN,
    X.EA_HEIGHT AS CPCEHEI,
    X.EA_WIDTH AS CPCEWID,
    X.CA_DEPTH AS CPCCLEN,
    X.CA_HEIGHT AS CPCCHEI,
    X.CA_WIDTH AS CPCCWID,
    0 AS CPCNEST,
    D.slotmaster_chargroup,
    D.slotmaster_pickzone,
    D.slotmaster_usehigh AS LMHIGH,
    D.slotmaster_usedeep AS LMDEEP,
    D.slotmaster_usewide AS LMWIDE,
    D.slotmaster_usecube AS LMVOL9,
    D.slotmaster_tier AS LMTIER,
    D.slotmaster_dimgroup AS LMGRD5,
    D.slotmaster_normreplen + D.slotmaster_maxreplen AS CURMAX,
    D.slotmaster_normreplen AS CURMIN,
    CASE
        WHEN X.EA_DEPTH * X.EA_HEIGHT * X.EA_WIDTH > 0 THEN (A.AVG_DAILY_UNIT * X.EA_DEPTH * X.EA_HEIGHT * X.EA_WIDTH)
        ELSE (A.AVG_DAILY_UNIT) * X.CA_DEPTH * X.CA_HEIGHT * X.CA_WIDTH / X.PKGU_CA
    END AS DLY_CUBE_VEL,
    CASE
        WHEN X.EA_DEPTH * X.EA_HEIGHT * X.EA_WIDTH > 0 THEN (A.AVG_DAILY_PICK) * X.EA_DEPTH * X.EA_HEIGHT * X.EA_WIDTH
        ELSE (A.AVG_DAILY_PICK) * X.CA_DEPTH * X.CA_HEIGHT * X.CA_WIDTH
    END AS DLY_PICK_VEL,
    currgrid_loctype,
    currgrid_grid,
    itemtf_griddep,
    itemtf_max,
    itemtf_min,
    itemtf_slotqty,
    itemtf_impmoves,
    0 AS CURRENT_IMPMOVES,
    itemtf_locvol,
    itemtf_daystostock,
    'XXXX' AS VCBAY,
    0 AS JAX_ENDCAP,
    A.AVG_DAILY_PICK AS DAILYPICK,
    A.AVG_DAILY_UNIT AS DAILYUNIT
FROM
    gillingham.nptsld A
        JOIN
    gillingham.item_master X ON X.ITEM = A.ITEM
        JOIN
    gillingham.slotmaster D ON D.slotmaster_item = A.ITEM
        JOIN
    gillingham.currgrid ON A.ITEM = currgrid_item
        JOIN
    gillingham.item_truefits_ext ON A.ITEM = itemtf_item
        AND itemtf_grid = currgrid_grid
        LEFT JOIN
    gillingham.my_npfmvc F ON F.ITEM_NUMBER = A.ITEM
WHERE
    F.ITEM_NUMBER IS NULL
        AND D.slotmaster_pkgu = 'EA'
        AND A.PKTYPE = 'EA'");
$L01GridsSQL->execute();
$L01GridsArray = $L01GridsSQL->fetchAll(pdo::FETCH_ASSOC);

