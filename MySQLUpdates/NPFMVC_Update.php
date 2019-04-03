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

