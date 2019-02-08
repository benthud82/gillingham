<?php
//scenario number descriptions
// 0 - Nothing can be done
// 1 - Adjust Max
// 2 - Perfect Slot
// 3 - 1-Level Slot
// 4 - Imperfect MC
// 5 - Imperfect GRID5



$SCENARIO = $displayarray[$key2]['ReslotScenario'];

switch ($SCENARIO) {
    case 0: //Nothing can be done
        echo 'Nothing can be done!';
        break;

    case 999: //Settings Check
        //call slotting settings check
        include 'slottingsettings.php';
        break;

    case 1: //Adjust Max.  Does not impact walk cost
        ?>
        <h3 class="sub-page-header">Score Summary</h3>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Replen Score Impact: <?php echo number_format(($displayarray[$key2]['MOVESCORE_AFTER_MAX_INCREASE'] - $displayarray[$key2]['SCORE_REPLENSCORE']) * 100, 2) . '%'; ?> </div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_REPLENSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['MOVESCORE_AFTER_MAX_INCREASE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Walk Score Impact: <?php echo '0.00%' ?> </div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_WALKSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_WALKSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Total Score Impact: <?php echo number_format($displayarray[$key2]['FinalSavings'] * 100, 2) . '%'; ?></div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_TOTALSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format(($displayarray[$key2]['SCORE_WALKSCORE'] * $displayarray[$key2]['MOVESCORE_AFTER_MAX_INCREASE']) * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>


        <?php
        break;
    case 2: //Perfect Slot
        ?>
        <h3 class="sub-page-header">Score Summary</h3>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Replen Score Impact: <?php echo number_format(($displayarray[$key2]['MOVESCORE_AFTER_PERF_GRID'] - $displayarray[$key2]['SCORE_REPLENSCORE']) * 100, 2) . '%'; ?> </div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_REPLENSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['MOVESCORE_AFTER_PERF_GRID'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Walk Score Impact: <?php echo number_format(($displayarray[$key2]['WALKSCORE_AFTER_PERF_GRID'] - $displayarray[$key2]['SCORE_WALKSCORE']) * 100, 2) . '%'; ?> </div>
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_WALKSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format(($displayarray[$key2]['WALKSCORE_AFTER_PERF_GRID'] * 100), 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Total Score Impact: <?php echo number_format($displayarray[$key2]['FinalSavings'] * 100, 2) . '%'; ?></div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_TOTALSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format(($displayarray[$key2]['WALKSCORE_AFTER_PERF_GRID'] * $displayarray[$key2]['MOVESCORE_AFTER_PERF_GRID']) * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>

        <?php
        break;
    case 3: //1-Level Slot
        //step 1, move the level 1 item to new location
        ?>
        <h3 class="sub-page-header">Score Summary</h3>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Replen Score Impact: <?php echo number_format(($displayarray[$key2]['MOVESCORE_AFTER_LEVEL1_SWAP'] - $displayarray[$key2]['SCORE_REPLENSCORE']) * 100, 2) . '%'; ?> </div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_REPLENSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['MOVESCORE_AFTER_LEVEL1_SWAP'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Walk Score Impact: <?php echo number_format(($displayarray[$key2]['WALKSCORE_AFTER_LEVEL1_SWAP'] - $displayarray[$key2]['SCORE_WALKSCORE']) * 100, 2) . '%'; ?> </div>
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_WALKSCORE'] * 100, 2).'%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['WALKSCORE_AFTER_LEVEL1_SWAP'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Total Score Impact: <?php echo number_format($displayarray[$key2]['FinalSavings'] * 100, 2).'%'; ?></div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_TOTALSCORE'] * 100, 2) .'%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format(($displayarray[$key2]['WALKSCORE_AFTER_LEVEL1_SWAP'] * $displayarray[$key2]['MOVESCORE_AFTER_LEVEL1_SWAP']) * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>
        <?php
        break;
    case 4: //Imp_MC Slot
                //********HAVE NOT CORRECTED BELOW HERE**********
        ?>
        <h3 class="sub-page-header">Yearly Cost Summary</h3>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Replen Score Impact: <?php echo number_format(($displayarray[$key2]['MOVESCORE_AFTER_IMPERFECT_MC'] - $displayarray[$key2]['SCORE_REPLENSCORE']) * 100, 2) . '%'; ?> </div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_REPLENSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['MOVESCORE_AFTER_IMPERFECT_MC'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Walk Score Impact: <?php echo number_format(($displayarray[$key2]['WALKSCORE_AFTER_IMPERFECT_MC'] - $displayarray[$key2]['SCORE_WALKSCORE']) * 100, 2) . '%'; ?> </div>
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_WALKSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format(($displayarray[$key2]['WALKSCORE_AFTER_IMPERFECT_MC'] * 100), 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Total Score Impact: <?php echo number_format($displayarray[$key2]['FinalSavings'] * 100, 2) . '%'; ?></div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_TOTALSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format(($displayarray[$key2]['WALKSCORE_AFTER_IMPERFECT_MC'] * $displayarray[$key2]['MOVESCORE_AFTER_IMPERFECT_MC']) * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>

        <?php
        break;
    case 5: //Imp_GRID5 Slot
        ?>
       <h3 class="sub-page-header">Score Summary</h3>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Replen Score Impact: <?php echo number_format(($displayarray[$key2]['MOVESCORE_AFTER_IMP_GRID'] - $displayarray[$key2]['SCORE_REPLENSCORE']) * 100, 2) . '%'; ?> </div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_REPLENSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['MOVESCORE_AFTER_IMP_GRID'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Walk Score Impact: <?php echo number_format(($displayarray[$key2]['WALKSCORE_AFTER_IMP_GRID'] - $displayarray[$key2]['SCORE_WALKSCORE']) * 100, 2) . '%'; ?> </div>
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_WALKSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format(($displayarray[$key2]['WALKSCORE_AFTER_IMP_GRID'] * 100), 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Total Score Impact: <?php echo number_format($displayarray[$key2]['FinalSavings'] * 100, 2) . '%'; ?></div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format($displayarray[$key2]['SCORE_TOTALSCORE'] * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Score </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo number_format(($displayarray[$key2]['WALKSCORE_AFTER_IMP_GRID'] * $displayarray[$key2]['MOVESCORE_AFTER_IMP_GRID']) * 100, 2) . '%'; ?> </div>
                        <div class="uppercase profile-stat-text"> New Score </div>
                    </div>
                </div> 
            </section> 
        </div>

        <?php
        break;
}
