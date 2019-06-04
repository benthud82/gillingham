
<?php
ini_set('max_execution_time', 99999);
include_once '../connection/NYServer.php';
$sqlfilter = 0;

$var_item = intval($_POST['itemnum']);  //pulled from itemquery.php
$var_userid = $_POST['userid'];



    $itemdetail_loose = $conn1->prepare("SELECT DISTINCT
    A.WAREHOUSE,
    A.ITEM_NUMBER,
    A.PACKAGE_UNIT,
    A.PACKAGE_TYPE,
    A.CUR_LOCATION,
    A.DAYS_FRM_SLE,
    A.AVGD_BTW_SLE,
    A.NBR_SHIP_OCC,
    A.AVG_INV_OH,
    A.PICK_QTY_MN,
    A.SHIP_QTY_MN,
    A.DLY_CUBE_VEL,
    A.DLY_PICK_VEL,
    A.LMGRD5,
    A.LMDEEP,
    A.LMTIER,
    A.SUGGESTED_TIER,
    A.SUGGESTED_GRID5,
    A.SUGGESTED_DEPTH,
    A.SUGGESTED_MAX,
    A.SUGGESTED_MIN,
    A.SUGGESTED_SLOTQTY,
    A.SUGGESTED_IMPMOVES,
    A.CURRENT_IMPMOVES,
    A.SUGGESTED_NEWLOCVOL,
    A.SUGGESTED_DAYSTOSTOCK,
    A.AVG_DAILY_PICK,
    A.AVG_DAILY_UNIT,
    A.SUGGESTED_NEWLOCVOL,
    B.OPT_PPCCALC,
    B.OPT_OPTBAY,
    B.OPT_CURRBAY,
    B.OPT_CURRDAILYFT,
    B.OPT_SHLDDAILYFT,
    B.OPT_ADDTLFTPERPICK,
    B.OPT_ADDTLFTPERDAY,
    B.OPT_WALKCOST,
    C.slotmaster_normreplen + C.slotmaster_maxreplen as CURMAX,
    C.slotmaster_normreplen as CURMIN,
    'N/A' as VCCTRF,
    E.SCORE_TOTALSCORE,
    E.SCORE_REPLENSCORE,
    E.SCORE_WALKSCORE,
    'N/A' as ITEM_DESC
FROM
    gillingham.my_npfmvc A
        JOIN
    gillingham.optimalbay B ON A.ITEM_NUMBER = B.OPT_ITEM
        JOIN
    gillingham.slotmaster C ON C.slotmaster_item = A.ITEM_NUMBER
        JOIN
    gillingham.slottingscore E ON E.SCORE_ITEM = A.ITEM_NUMBER
WHERE
   A.ITEM_NUMBER = $var_item");
    $itemdetail_loose->execute();
    $itemdetailarray_loose = $itemdetail_loose->fetchAll(pdo::FETCH_ASSOC);


//Loose Display
    foreach ($itemdetailarray_loose as $key => $value) {
        $CorL = 'Loose';
        ?>
        <div class="row"> 
            <div class="" style="padding-bottom: 5px;">
                <section class="panel">
                    <header class="panel-heading bg bg-inverse h3"> Item Slotting Detail | <?php echo $CorL . ' Pkgu of ' . $itemdetailarray_loose[$key]['PACKAGE_UNIT'] ?> | <?php echo $itemdetailarray_loose[$key]['ITEM_DESC']; ?></header>
                    <div class="media"> 
                        <div class="row">
                            <div class="col-xl-2 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
                                <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo $itemdetailarray_loose[$key]['ITEM_NUMBER'] ?></div> 
                                <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Item Code</div>
                            </div>
                            <div class="col-xl-2 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
                                <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo $itemdetailarray_loose[$key]['CUR_LOCATION'] ?></div> 
                                <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Location</div>
                            </div>
                            <div class="col-xl-2 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
                                <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo $itemdetailarray_loose[$key]['LMGRD5'] ?></div> 
                                <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Current Grid5</div>
                            </div>
                            <div class="col-xl-2 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
                                <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo number_format(($itemdetailarray_loose[$key]['SCORE_REPLENSCORE'] * 100), 2) . '%' ?></div> 
                                <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Replen Score</div>

                            </div>
                            <div class="col-xl-2 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
                                <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo number_format(($itemdetailarray_loose[$key]['SCORE_WALKSCORE'] * 100), 2) . '%' ?></div> 
                                <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Walk Score</div>

                            </div>
                            <div class="col-xl-2 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
                                <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo number_format(($itemdetailarray_loose[$key]['SCORE_TOTALSCORE'] * 100), 2) . '%' ?></div> 
                                <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Total Score</div>
                                <div class="col-sm-12 h5" style="padding-bottom: 4px;"><i class="fa fa-2x clicktotoggle-chevron fa-chevron-circle-down" style="padding: 0px 0px 0px 20px; float: right; cursor: pointer;"></i></div>
                            </div>
                        </div> 
                    </div> 
                    <!--Start of hidden data-->
                    <div class="hiddencostdetail panel-body" style="display: none;">
                        <div class="row" style="padding-bottom: 10px; padding-top: 10px;">
                            <!--Customer Scorecard Panels for month/quarter/rolling 12-->
                            <div class="col-lg-3 col-sm-6 panel-no-page-break">
                                <!-- Current Month Panel -->            
                                <section class="panel">
                                    <header class="panel-heading bg  h3 text-center bg-softblue">Replen Stats </header>
                                    <div class="panel-body  text-center" style="border-bottom: 3px solid #ccc;">
                                        <div class="widget-content-blue-wrapper changed-up">
                                            <div class="widget-content-blue-inner padded">
                                                <div class="h4"><i class="fa fa-sitemap"></i> Replen Score</div>
                                                <div class="line m-l m-r"></div> 
                                                <div class="value-block">
                                                    <!--Replen Score-->
                                                    <div class="h2" id="score_replen"><?php echo number_format(($itemdetailarray_loose[$key]['SCORE_REPLENSCORE'] * 100), 2) . '%' ?></div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- List group -->
                                    <div class="list-group">
                                        <div class="list-group-item"> 
                                            <span class="pull-right"><strong><i id="moveauditclicklse" class="fa fa-question-circle moveauditclick" style="cursor: pointer; text-decoration: none;"></i><?php echo '  ' . intval($itemdetailarray_loose[$key]['CURRENT_IMPMOVES'] * 253) ?></strong></span> 
                                            Current Yearly Moves
                                        </div>
                                        <div class="list-group-item"> 
                                            <span class="pull-right"><strong><?php echo intval($itemdetailarray_loose[$key]['SUGGESTED_IMPMOVES'] * 253) ?></strong></span> 
                                            Optimal Yearly Moves
                                        </div>
                                        <div class="list-group-item"> 
                                            <span class="pull-right"><strong><i id="click_sugggrid5" class="fa fa-question-circle grid5click" style="cursor: pointer; text-decoration: none; margin-right: 4px;" data-grid="<?php echo $itemdetailarray_loose[$key]['SUGGESTED_GRID5'] ?>" ></i><?php echo $itemdetailarray_loose[$key]['SUGGESTED_GRID5'] ?></strong></span> 
                                            Suggested Grid5
                                        </div>
                                        <div class="list-group-item"> 
                                            <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['SUGGESTED_DEPTH'] ?></strong></span> 
                                            Suggested Depth
                                        </div>
                                        <div class="list-group-item"> 
                                            <span class="pull-right"><strong><i id="click_currgrid5" class="fa fa-question-circle grid5click" style="cursor: pointer; text-decoration: none;margin-right: 4px;" data-grid="<?php echo $itemdetailarray_loose[$key]['LMGRD5'] ?>" ></i><?php echo $itemdetailarray_loose[$key]['LMGRD5'] ?></strong></span> 
                                            Current Grid5
                                        </div>
                                        <div class="list-group-item"> 
                                            <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['LMDEEP'] ?></strong></span> 
                                            Current Depth
                                        </div>

                                    </div>
                                </section>
                            </div>
                            <div class="col-lg-3 col-sm-6 panel-no-page-break">
                                <!-- Current Quarter Panel -->            
                                <section class="panel"> 
                                    <header class="panel-heading bg h3 text-center bg-softblue">Travel Stats </header>
                                    <div class="panel-body  text-center" style="border-bottom: 3px solid #ccc;">
                                        <div class="widget-content-blue-wrapper changed-up">
                                            <div class="widget-content-blue-inner padded">
                                                <div class="h4"><i class="fa fa-blind"></i> Travel Score</div>
                                                <div class="line m-l m-r"></div> 
                                                <div class="value-block">
                                                    <!--Walk Score-->
                                                    <div class="h2" id="score_quarter"><?php echo number_format(($itemdetailarray_loose[$key]['SCORE_WALKSCORE'] * 100), 2) . '%' ?></div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- List group -->
                                    <div class="list-group">
                                        <div class="list-group-item"> 
                                            <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['OPT_CURRBAY'] ?></strong></span> 
                                            Current Bay
                                        </div>
                                        <div class="list-group-item"> 
                                            <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['OPT_OPTBAY'] ?></strong></span> 
                                            Optimal Bay
                                        </div>
                                        <div class="list-group-item"> 
                                            <span class="pull-right"><strong><?php echo number_format($itemdetailarray_loose[$key]['OPT_ADDTLFTPERDAY'],1) ?></strong></span> 
                                            Additional Meters / Day
                                        </div>
                                        <div class="list-group-item"> 
                                            <span class="pull-right"><strong><i id="pickauditclicklse" class="fa fa-question-circle pickauditclick" style="cursor: pointer; text-decoration: none;"></i><?php echo '  ' . number_format($itemdetailarray_loose[$key]['AVG_DAILY_PICK'], 2) ?></strong></span> 
                                            Avg. Daily Pick
                                        </div>
                                        <div class="list-group-item"> 
                                            <span class="pull-right"><strong><?php echo number_format($itemdetailarray_loose[$key]['AVG_DAILY_UNIT'], 2) ?></strong></span> 
                                            Avg. Daily Units
                                        </div>
                                        <div class="list-group-item"> 
                                            <span class="pull-right"><strong><?php echo number_format($itemdetailarray_loose[$key]['OPT_PPCCALC'], 2) ?></strong></span> 
                                            Picks Per Cubic CM
                                        </div>

                                    </div>
                                </section>
                            </div>
                            <div class="col-lg-6 col-sm-12 panel-no-page-break">
                                <!-- Current R12 Panel -->            
                                <section class="panel"> 
                                    <header class="panel-heading bg h3 text-center bg-softgreen">Slotting Details </header>
                                    <div class="panel-body  text-center" style="border-bottom: 3px solid #ccc;">
                                        <div class="widget-content-blue-wrapper changed-up">
                                            <div class="widget-content-blue-inner padded">
                                                <div class="h4"><i class="fa fa-info-circle"></i> Total Score</div>
                                                <div class="line m-l m-r"></div> 
                                                <div class="value-block">
                                                    <!--Total Score-->
                                                    <div class="h2" id="score_quarter"><?php echo number_format(($itemdetailarray_loose[$key]['SCORE_TOTALSCORE'] * 100), 2) . '%' ?></div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- List group -->
                                    <div class="list-group">
                                        <div class="row">
                                            <div class="col-md-6 bordered">
                                                <div class="list-group-item "> 
                                                    <span class="pull-right"><strong><?php echo number_format($itemdetailarray_loose[$key]['AVGD_BTW_SLE'],2) ?></strong></span> 
                                                    Avg. Days Between Sale
                                                </div>
                                                <div class="list-group-item "> 
                                                    <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['DAYS_FRM_SLE'] ?></strong></span> 
                                                    Days Since Last Sale 
                                                </div>
                                                <div class="list-group-item "> 
                                                    <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['NBR_SHIP_OCC'] ?></strong></span> 
                                                    Ship Occurrences
                                                </div>
                                                <div class="list-group-item "> 
                                                    <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['AVG_INV_OH'] ?></strong></span> 
                                                    Avg Inventory OH
                                                </div>
                                                <div class="list-group-item "> 
                                                    <span class="pull-right"><strong><?php echo number_format($itemdetailarray_loose[$key]['PICK_QTY_MN'],2) ?></strong></span> 
                                                    Pick Quantity Mean
                                                </div>
                                                <div class="list-group-item "> 
                                                    <span class="pull-right"><strong><?php echo number_format($itemdetailarray_loose[$key]['SHIP_QTY_MN'],2) ?></strong></span> 
                                                    Ship Qty Mean
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="list-group-item"> 
                                                    <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['LMTIER'] ?></strong></span> 
                                                    Current Tier
                                                </div>
                                                <div class="list-group-item"> 
                                                    <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['SUGGESTED_TIER'] ?></strong></span> 
                                                    Suggested Tier
                                                </div>
                                                <div class="list-group-item"> 
                                                    <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['CURMAX'] ?></strong></span> 
                                                    Current Max
                                                </div>
                                                <div class="list-group-item"> 
                                                    <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['SUGGESTED_MAX'] ?></strong></span> 
                                                    Suggested Max
                                                </div>
                                                <div class="list-group-item"> 
                                                    <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['CURMIN'] ?></strong></span> 
                                                    Current Min
                                                </div>
                                                <div class="list-group-item"> 
                                                    <span class="pull-right"><strong><?php echo $itemdetailarray_loose[$key]['SUGGESTED_MIN'] ?></strong></span> 
                                                    Suggested Min
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>



                    </div>
                    <!--End of hidden data-->

                </section>
            </div>
        </div>



        <?php
    }


//$itemdetail_case = $conn1->prepare("SELECT DISTINCT
//                                A.WAREHOUSE,
//                                A.ITEM_NUMBER,
//                                A.PACKAGE_UNIT,
//                                A.PACKAGE_TYPE,
//                                A.DSL_TYPE,
//                                A.CUR_LOCATION,
//                                A.DAYS_FRM_SLE,
//                                A.AVGD_BTW_SLE,
//                                A.NBR_SHIP_OCC,
//                                A.AVG_INV_OH,
//                                A.PICK_QTY_MN,
//                                A.SHIP_QTY_MN,
//                                A.DLY_CUBE_VEL,
//                                A.DLY_PICK_VEL,
//                                A.LMGRD5,
//                                A.LMDEEP,
//                                A.LMTIER,
//                                A.SUGGESTED_TIER,
//                                A.SUGGESTED_GRID5,
//                                A.SUGGESTED_DEPTH,
//                                A.SUGGESTED_MAX,
//                                A.SUGGESTED_MIN,
//                                A.SUGGESTED_SLOTQTY,
//                                A.SUGGESTED_IMPMOVES,
//                                A.CURRENT_IMPMOVES,
//                                A.SUGGESTED_NEWLOCVOL,
//                                A.SUGGESTED_DAYSTOSTOCK,
//                                A.AVG_DAILY_PICK,
//                                A.AVG_DAILY_UNIT,
//                                B.OPT_NEWGRIDVOL,
//                                B.OPT_PPCCALC,
//                                B.OPT_OPTBAY,
//                                B.OPT_CURRBAY,
//                                B.OPT_CURRDAILYFT,
//                                B.OPT_SHLDDAILYFT,
//                                B.OPT_ADDTLFTPERPICK,
//                                B.OPT_ADDTLFTPERDAY,
//                                B.OPT_WALKCOST,
//                                C.CURMAX,
//                                C.CURMIN,
//                                D.VCCTRF,
//                                E.SCORE_TOTALSCORE,
//                                E.SCORE_REPLENSCORE,
//                                E.SCORE_WALKSCORE,
//                                FLOOR,
//                                    B.OPT_BUILDING
//                            FROM
//                                gillingham.my_npfmvc A
//                                    left join
//                                gillingham.optimalbay B ON A.WAREHOUSE = B.OPT_WHSE
//                                    and A.ITEM_NUMBER = B.OPT_ITEM
//                                    and A.PACKAGE_UNIT = B.OPT_PKGU
//                                    and A.PACKAGE_TYPE = B.OPT_CSLS
//                                    join
//                                gillingham.mysql_npflsm C ON C.LMWHSE = A.WAREHOUSE
//                                    and C.LMITEM = A.ITEM_NUMBER
//                                    and C.LMTIER = A.LMTIER
//                                    left join
//                                gillingham.system_npfmvc D ON D.VCWHSE = A.WAREHOUSE
//                                    and D.VCITEM = A.ITEM_NUMBER
//                                    and D.VCPKGU = A.PACKAGE_UNIT
//                                    and D.VCFTIR = A.LMTIER
//                                    join
//                                gillingham.slottingscore E ON E.SCORE_WHSE = A.WAREHOUSE
//                                    AND E.SCORE_ITEM = A.ITEM_NUMBER
//                                    AND E.SCORE_PKGU = A.PACKAGE_UNIT
//                                    AND E.SCORE_ZONE = A.PACKAGE_TYPE
//                                    left join
//                                gillingham.case_floor_locs L ON L.LOCATION = A.CUR_LOCATION
//                                    and L.WHSE = A.WAREHOUSE
//
//                                WHERE
//                                    ITEM_NUMBER = '$var_item'
//                                    and PACKAGE_TYPE in ('CSE','PFR')");
//$itemdetail_case->execute();
//$itemdetailarray_case = $itemdetail_case->fetchAll(pdo::FETCH_ASSOC);
$itemdetailarray_case = array();
//Loose Display
foreach ($itemdetailarray_case as $key => $value) {
    $CorL = 'Case';
    ?>
    <div class="row"> 
        <div class="" style="padding-bottom: 5px;">
            <section class="panel">
                <header class="panel-heading bg bg-inverse"> Item Slotting Detail | <?php echo $CorL . ' Pkgu of ' . $itemdetailarray_case[$key]['PACKAGE_UNIT'] ?> </header>
                <div class="media"> 
                    <div class="row">
                        <div class="col-xl-2 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
                            <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo $itemdetailarray_case[$key]['ITEM_NUMBER'] ?></div> 
                            <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Item Code</div>
                        </div>
                        <div class="col-xl-2 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
                            <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo $itemdetailarray_case[$key]['CUR_LOCATION'] ?></div> 
                            <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Location</div>
                        </div>
                        <div class="col-xl-2 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
                            <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php
                                if ($itemdetailarray_case[$key]['LMGRD5'] == "     " || $itemdetailarray_case[$key]['LMGRD5'] == NULL) {
                                    echo 'PFR';
                                } else {
                                    echo $itemdetailarray_case[$key]['LMGRD5'];
                                }
                                ?></div> 
                            <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Current Grid5</div>
                        </div>
                        <div class="col-xl-2 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
                            <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo number_format(($itemdetailarray_case[$key]['SCORE_REPLENSCORE'] * 100), 2) . '%' ?></div> 
                            <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Replen Score</div>

                        </div>
                        <div class="col-xl-2 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
                            <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo number_format(($itemdetailarray_case[$key]['SCORE_WALKSCORE'] * 100), 2) . '%' ?></div> 
                            <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Walk Score</div>

                        </div>
                        <div class="col-xl-2 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
                            <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo number_format(($itemdetailarray_case[$key]['SCORE_TOTALSCORE'] * 100), 2) . '%' ?></div> 
                            <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Total Score</div>
                            <div class="col-sm-12 h5" style="padding-bottom: 4px;"><i class="fa fa-2x clicktotoggle-chevron fa-chevron-circle-down" style="padding: 0px 0px 0px 20px; float: right; cursor: pointer;"></i></div>
                        </div>
                    </div> 
                </div> 
                <!--Start of hidden data-->
                <div class="hiddencostdetail panel-body" style="display: none;">
                    <div class="row" style="padding-bottom: 10px; padding-top: 10px;">
                        <!--Customer Scorecard Panels for month/quarter/rolling 12-->
                        <div class="col-lg-3 col-sm-6 panel-no-page-break">
                            <!-- Current Month Panel -->            
                            <section class="panel">
                                <header class="panel-heading bg  h3 text-center bg-softblue">Replen Stats </header>
                                <div class="panel-body  text-center" style="border-bottom: 3px solid #ccc;">
                                    <div class="widget-content-blue-wrapper changed-up">
                                        <div class="widget-content-blue-inner padded">
                                            <div class="h4"><i class="fa fa-sitemap"></i> Replen Score</div>
                                            <div class="line m-l m-r"></div> 
                                            <div class="value-block">
                                                <!--Replen Score-->
                                                <div class="h2" id="score_replen"><?php echo number_format(($itemdetailarray_case[$key]['SCORE_REPLENSCORE'] * 100), 2) . '%' ?></div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- List group -->
                                <div class="list-group">
                                    <div class="list-group-item"> 
                                        <span class="pull-right"><strong><?php echo number_format(($itemdetailarray_case[$key]['CURRENT_IMPMOVES'] * 253)) ?></strong></span> 
                                        Current Yearly Moves
                                    </div>
                                    <div class="list-group-item"> 
                                        <span class="pull-right"><strong><?php echo number_format(($itemdetailarray_case[$key]['SUGGESTED_IMPMOVES'] * 253)) ?></strong></span> 
                                        Optimal Yearly Moves
                                    </div>
                                    <div class="list-group-item"> 
                                        <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['SUGGESTED_GRID5'] ?></strong></span> 
                                        Suggested Grid5
                                    </div>
                                    <div class="list-group-item"> 
                                        <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['LMGRD5'] ?></strong></span> 
                                        Current Grid5
                                    </div>
                                    <div class="list-group-item"> 
                                        <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['SUGGESTED_SLOTQTY'] ?></strong></span> 
                                        Optimal Slot Quantity
                                    </div>
                                    <div class="list-group-item"> 
                                        <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['CURMAX'] ?></strong></span> 
                                        Current Max
                                    </div>

                                </div>
                            </section>
                        </div>
                        <div class="col-lg-3 col-sm-6 panel-no-page-break">
                            <!-- Current Quarter Panel -->            
                            <section class="panel"> 
                                <header class="panel-heading bg h3 text-center bg-softblue">Travel Stats </header>
                                <div class="panel-body  text-center" style="border-bottom: 3px solid #ccc;">
                                    <div class="widget-content-blue-wrapper changed-up">
                                        <div class="widget-content-blue-inner padded">
                                            <div class="h4"><i class="fa fa-blind"></i> Travel Score</div>
                                            <div class="line m-l m-r"></div> 
                                            <div class="value-block">
                                                <!--Walk Score-->
                                                <div class="h2" id="score_quarter"><?php echo number_format(($itemdetailarray_case[$key]['SCORE_WALKSCORE'] * 100), 2) . '%' ?></div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- List group -->
                                <div class="list-group">
                                    <div class="list-group-item"> 
                                        <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['OPT_CURRBAY'] ?></strong></span> 
                                        Current Zone
                                    </div>
                                    <div class="list-group-item"> 
                                        <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['OPT_OPTBAY'] ?></strong></span> 
                                        Optimal Zone
                                    </div>
                                    <div class="list-group-item"> 
                                        <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['OPT_ADDTLFTPERDAY'] ?></strong></span> 
                                        Addtl Minutes Per Day
                                    </div>
                                    <div class="list-group-item"> 
                                        <span class="pull-right"><strong><?php echo number_format($itemdetailarray_case[$key]['AVG_DAILY_PICK'], 2) ?></strong></span> 
                                        Avg. Daily Pick
                                    </div>
                                    <div class="list-group-item"> 
                                        <span class="pull-right"><strong><?php echo number_format($itemdetailarray_case[$key]['AVG_DAILY_UNIT'], 2) ?></strong></span> 
                                        Avg. Daily Units
                                    </div>
                                    <div class="list-group-item"> 
                                        <span class="pull-right"><strong><?php echo number_format($itemdetailarray_case[$key]['OPT_PPCCALC'], 2) ?></strong></span> 
                                        Picks Per Cubic CM
                                    </div>

                                </div>
                            </section>
                        </div>
                        <div class="col-lg-6 col-sm-12 panel-no-page-break">
                            <!-- Current R12 Panel -->            
                            <section class="panel"> 
                                <header class="panel-heading bg h3 text-center bg-softgreen">Slotting Details </header>
                                <div class="panel-body  text-center" style="border-bottom: 3px solid #ccc;">
                                    <div class="widget-content-blue-wrapper changed-up">
                                        <div class="widget-content-blue-inner padded">
                                            <div class="h4"><i class="fa fa-info-circle"></i> Total Score</div>
                                            <div class="line m-l m-r"></div> 
                                            <div class="value-block">
                                                <!--Total Score-->
                                                <div class="h2" id="score_quarter"><?php echo number_format(($itemdetailarray_case[$key]['SCORE_TOTALSCORE'] * 100), 2) . '%' ?></div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- List group -->
                                <div class="list-group">
                                    <div class="row">
                                        <div class="col-md-6 bordered">
                                            <div class="list-group-item "> 
                                                <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['AVGD_BTW_SLE'] ?></strong></span> 
                                                Avg. Days Between Sale
                                            </div>
                                            <div class="list-group-item "> 
                                                <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['DAYS_FRM_SLE'] ?></strong></span> 
                                                Days Since Last Sale 
                                            </div>
                                            <div class="list-group-item "> 
                                                <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['NBR_SHIP_OCC'] ?></strong></span> 
                                                Ship Occurrences
                                            </div>
                                            <div class="list-group-item "> 
                                                <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['AVG_INV_OH'] ?></strong></span> 
                                                Avg Inventory OH
                                            </div>
                                            <div class="list-group-item "> 
                                                <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['PICK_QTY_MN'] ?></strong></span> 
                                                Pick Quantity Mean
                                            </div>
                                            <div class="list-group-item "> 
                                                <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['SHIP_QTY_MN'] ?></strong></span> 
                                                Ship Qty Mean
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="list-group-item"> 
                                                <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['LMTIER'] ?></strong></span> 
                                                Current Tier
                                            </div>
                                            <div class="list-group-item"> 
                                                <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['SUGGESTED_TIER'] ?></strong></span> 
                                                Suggested Tier
                                            </div>
                                            <div class="list-group-item"> 
                                                <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['CURMAX'] ?></strong></span> 
                                                Current Max
                                            </div>
                                            <div class="list-group-item"> 
                                                <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['SUGGESTED_MAX'] ?></strong></span> 
                                                Suggested Max
                                            </div>
                                            <div class="list-group-item"> 
                                                <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['CURMIN'] ?></strong></span> 
                                                Current Min
                                            </div>
                                            <div class="list-group-item"> 
                                                <span class="pull-right"><strong><?php echo $itemdetailarray_case[$key]['SUGGESTED_MIN'] ?></strong></span> 
                                                Suggested Min
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>



                </div>
                <!--End of hidden data-->

            </section>
        </div>
    </div>
    <?php
}
