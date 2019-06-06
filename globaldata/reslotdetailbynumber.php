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
        echo 'Downsize Locations - '. '<a href="locationdownsize.php">Open Report</a>';
        break;

    case 999: //Settings Check
        //call slotting settings check
        include 'slottingsettings.php';
        break;

    case 1: //Adjust Max
        ?>
        <h3 class="sub-page-header">Final Recommendation</h3>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Increase Max to True Fit</div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo $displayarray[$key2]['CURMAX'] ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Max </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo $displayarray[$key2]['VCCTRF'] ?> </div>
                        <div class="uppercase profile-stat-text"> Curr TF </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Yearly Move Decrease</div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo intval($displayarray[$key2]['CURRENT_IMPMOVES'] * 253) ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Moves</div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo intval($displayarray[$key2]['MOVES_AFTER_MAX_INCREASE'] * 253) ?> </div>
                        <div class="uppercase profile-stat-text"> New Moves </div>
                    </div>
                </div> 
            </section> 
        </div>


        <?php
        break;
    case 2: //Perfect Slot
        ?>
        <h3 class="sub-page-header">Final Recommendation</h3>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Move Item <?php echo '<b><u>' . $displayarray[$key2]['ITEM_NUMBER'] . '</u></b>' ?> </div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo $displayarray[$key2]['CUR_LOCATION'] ?> </div>
                        <div class="uppercase profile-stat-text"> From Loc </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo $displayarray[$key2]['PERF_SLOT_LOC'] ?> </div>
                        <div class="uppercase profile-stat-text"> To Loc </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Yearly Move Decrease</div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo intval($displayarray[$key2]['CURRENT_IMPMOVES'] * 253) ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Moves</div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo intval($displayarray[$key2]['MOVES_AFTER_PERF_GRID'] * 253) ?> </div>
                        <div class="uppercase profile-stat-text"> New Moves </div>
                    </div>
                </div> 
            </section> 
        </div>
        <?php
        break;
    case 3: //1-Level Slot
        //step 1, move the level 1 item to new location

        ?>

        <h3 class="sub-page-header">Final Recommendation</h3>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Move Item <?php echo '<b><u>' . $displayarray[$key2]['LEVEL_1_ITEM'] . '</u></b>' ?> </div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo $displayarray[$key2]['LEVEL_1_OLD_LOC'] ?> </div>
                        <div class="uppercase profile-stat-text"> From Loc </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo $displayarray[$key2]['LEVEL_1_NEW_LOC'] ?> </div>
                        <div class="uppercase profile-stat-text"> To Loc </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Move Item <?php echo '<b><u>' . $displayarray[$key2]['ITEM_NUMBER'] . '</u></b>' ?> </div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo $displayarray[$key2]['CUR_LOCATION'] ?> </div>
                        <div class="uppercase profile-stat-text"> From Loc </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo $displayarray[$key2]['LEVEL_1_OLD_LOC'] ?> </div>
                        <div class="uppercase profile-stat-text"> To Loc </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Yearly Move Decrease</div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo intval($displayarray[$key2]['CURRENT_IMPMOVES'] * 253) ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Moves</div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo intval($displayarray[$key2]['MOVES_AFTER_LEVEL1_SWAP'] * 253) ?> </div>
                        <div class="uppercase profile-stat-text"> New Moves </div>
                    </div>
                </div> 
            </section> 
        </div>
        <?php
        break;
    case 4: //Imp_MC Slot
        ?>
        <h3 class="sub-page-header">Final Recommendation</h3>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Move Item <?php echo '<b><u>' . $displayarray[$key2]['ITEM_NUMBER']  . '</u></b>' ?> </div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo $displayarray[$key2]['CUR_LOCATION']  ?> </div>
                        <div class="uppercase profile-stat-text"> From Loc </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo $displayarray[$key2]['IMPERFECT_MC_SLOT_LOC'] ?> </div>
                        <div class="uppercase profile-stat-text"> To Loc </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Yearly Move Decrease</div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo intval($displayarray[$key2]['CURRENT_IMPMOVES'] * 253) ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Moves</div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo intval($displayarray[$key2]['AFTER_IMPERFECT_MC_SLOT_MOVES'] * 253) ?> </div>
                        <div class="uppercase profile-stat-text"> New Moves </div>
                    </div>
                </div> 
            </section> 
        </div>
        <?php
        break;
    case 5: //Imp_GRID5 Slot
        ?>
        <h3 class="sub-page-header">Final Recommendation</h3>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Move Item <?php echo '<b><u>' . $displayarray[$key2]['ITEM_NUMBER'] . '</u></b>' ?> </div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo $displayarray[$key2]['CUR_LOCATION'] ?> </div>
                        <div class="uppercase profile-stat-text"> From Loc </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo $displayarray[$key2]['IMPERFECT_GRID5_SLOT_LOC'] ?> </div>
                        <div class="uppercase profile-stat-text"> To Loc </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="col-xs-12"> 
            <section class="panel"> 
                <header class="panel-heading blue-chambray"> 
                    <div class="text-center h4 uppercase">Yearly Move Decrease</div> 
                </header> 
                <div class="panel-body pull-in text-center"> 
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo intval($displayarray[$key2]['CURRENT_IMPMOVES'] * 253) ?> </div>
                        <div class="uppercase profile-stat-text"> Curr Moves</div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?php echo intval($displayarray[$key2]['MOVES_AFTER_IMP_GRID'] * 253) ?> </div>
                        <div class="uppercase profile-stat-text"> New Moves </div>
                    </div>
                </div> 
            </section> 
        </div>
        <?php
        break;
    default:
        break;
}
