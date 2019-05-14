
<?php
ini_set('max_execution_time', 99999);
include_once '../connection/NYServer.php';


$var_item = $_POST['itemnum'];  //pulled from itemquery.php
$var_userid = $_POST['userid'];

$itemsettings_loose = $conn1->prepare("SELECT 
                                                                            *
                                                                        FROM
                                                                            gillingham.npfcpcsettings
                                                                                LEFT JOIN
                                                                            gillingham.item_settings ON ITEM = CPCITEM
                                                                        WHERE
                                                                           CPCITEM = $var_item;");
$itemsettings_loose->execute();
$itemsettingsarray_loose = $itemsettings_loose->fetchAll(pdo::FETCH_ASSOC);


//Loose Display
foreach ($itemsettingsarray_loose as $key => $value) {
    ?>
    <div class="row" style="padding-bottom: 10px; padding-top: 10px;">
        <!--Item Characteristics-->
        <div class="col-xl-3 col-lg-4 panel-no-page-break">
            <section class="panel">
                <header class="panel-heading bg  h3 text-center bg-warning">Item Characteristics </header> 
                <!-- Characteristics List group -->
                <div class="list-group">
                    <div class="list-group-item"> 
                        <span class="pull-right"><strong><?php echo number_format($itemsettingsarray_loose[$key]['CPCCPKU']); ?></strong></span> 
                        Case Pkgu
                    </div>
                    <div class="list-group-item"> 
                        <span class="pull-right"><strong><?php echo number_format($itemsettingsarray_loose[$key]['CPCELEN'], 2) . ' (cm)'; ?></strong></span> 
                        Each Length
                    </div>
                    <div class="list-group-item"> 
                        <span class="pull-right"><strong><?php echo number_format($itemsettingsarray_loose[$key]['CPCEWID'], 2) . ' (cm)'; ?></strong></span> 
                        Each Width
                    </div>
                    <div class="list-group-item"> 
                        <span class="pull-right"><strong><?php echo number_format($itemsettingsarray_loose[$key]['CPCEHEI'], 2) . ' (cm)'; ?></strong></span> 
                        Each Height
                    </div>
                    <div class="list-group-item"> 
                        <span class="pull-right"><strong><?php echo number_format($itemsettingsarray_loose[$key]['CPCCLEN'], 2) . ' (cm)'; ?></strong></span> 
                        Case Length
                    </div>
                    <div class="list-group-item"> 
                        <span class="pull-right"><strong><?php echo number_format($itemsettingsarray_loose[$key]['CPCCWID'], 2) . ' (cm)'; ?></strong></span> 
                        Case Width
                    </div>
                    <div class="list-group-item"> 
                        <span class="pull-right"><strong><?php echo number_format($itemsettingsarray_loose[$key]['CPCCHEI'], 2) . ' (cm)'; ?></strong></span> 
                        Case Height
                    </div>
                </div>
            </section>
        </div>

        <!--Item Settings-->
        <div class="col-xl-3 col-lg-4 panel-no-page-break">
            <section class="panel">
                <header class="panel-heading bg  h3 text-center bg-warning">Item Settings </header>
                <div class="panel-body  text-center" style="border-bottom: 3px solid #ccc;">
                    <div class="widget-content-blue-wrapper changed-up">
                        <div class="widget-content-blue-inner padded">
                            <div class="h4" id="modifysettingsclick" style="cursor: pointer;">Click to Modify Settings  <i class="fa fa-pencil"></i> </div>
                        </div>
                    </div>
                </div>
                <div class="list-group">
                    <div class="row">
                        <div class="col-md-6 bordered">
                            <div class="list-group-item "> 
                                <span class="pull-right"><strong><?php echo $itemsettingsarray_loose[$key]['CPCFLOW'] ?></strong></span> 
                                OK in Flow
                            </div>
                            <div class="list-group-item "> 
                                <span class="pull-right"><strong><?php echo $itemsettingsarray_loose[$key]['CPCTOTE'] ?></strong></span> 
                                OK in Tote
                            </div>
                            <div class="list-group-item "> 
                                <span class="pull-right"><strong><?php echo $itemsettingsarray_loose[$key]['CPCSHLF'] ?></strong></span> 
                                OK in Shelf
                            </div>
                            <div class="list-group-item "> 
                                <span class="pull-right"><strong><?php echo $itemsettingsarray_loose[$key]['CPCROTA'] ?></strong></span> 
                                Rotate
                            </div>
                            <div class="list-group-item "> 
                                <span class="pull-right"><strong><?php echo $itemsettingsarray_loose[$key]['CPCESTK'] ?></strong></span> 
                                Stack Limit
                            </div>
                            <div class="list-group-item "> 
                                <span class="pull-right"><strong><?php echo $itemsettingsarray_loose[$key]['CPCLIQU'] ?></strong></span> 
                                Liquid
                            </div>
                            <div class="list-group-item"> 
                                <span class="pull-right"><strong><?php echo 'N/A' ?></strong></span> 
                                Shelf Orientation
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="list-group-item"> 
                                <span class="pull-right"><strong><?php echo  'N/A' ?></strong></span> 
                                Corp PFR
                            </div>
                            <div class="list-group-item"> 
                                <span class="pull-right"><strong><?php echo  'N/A'  ?></strong></span> 
                                Local PFR
                            </div>
                            <div class="list-group-item"> 
                                <span class="pull-right"><strong><?php echo $itemsettingsarray_loose[$key]['CPCNEST'] ?></strong></span> 
                                Nest Limit
                            </div>
                            <div class="list-group-item"> 
                                <span class="pull-right" id="casetf"><strong><?php
                                        if ($itemsettingsarray_loose[$key]['CASETF'] == NULL) {
                                            echo 'N';
                                        } else {
                                            echo $itemsettingsarray_loose[$key]['CASETF'];
                                        }
                                        ?></strong></span> 
                                Use Case TF
                            </div>
                            <div class="list-group-item"> 
                                <span class="pull-right" id="holdtier"><strong><?php echo $itemsettingsarray_loose[$key]['HOLDTIER'] ?></strong></span> 
                                Hold Tier
                            </div>
                            <div class="list-group-item"> 
                                <span class="pull-right" id="holdgrid"><strong><?php echo $itemsettingsarray_loose[$key]['HOLDGRID'] ?></strong></span> 
                                Hold Grid
                            </div>
                            <div class="list-group-item"> 
                                <span class="pull-right" id="holdlocation"><strong><?php echo $itemsettingsarray_loose[$key]['HOLDLOCATION'] ?></strong></span> 
                                Hold Location
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>







        <?php
    }




//pull in item comments from mysql
    $itemcommentssql = $conn1->prepare("SELECT 
                                        *
                                    FROM
                                        gillingham.slotting_itemcomments
                                    JOIN gillingham.slottingdb_users on idslottingDB_users_ID = itemcomments_tsm
                                    WHERE
                                        itemcomments_item = $var_item
                                    ORDER BY itemcomments_date DESC;");
    $itemcommentssql->execute();
    $itemcommentsarray = $itemcommentssql->fetchAll(pdo::FETCH_ASSOC);
    $opencount = count($itemcommentsarray);

//item comments
    ?>

    <div class = "col-xl-6 col-lg-4  panel-no-page-break">
        <section class = "panel portlet-item" style = "opacity: 1;">
            <header class = "panel-heading bg-info" style="font-size: 24px;" > Item Comments  <span class="pull-right" style="cursor: pointer; font-size: 16px;" id="addcomment">Add Comment <i class="fa fa-plus-circle"></i></span> </header>
            <section class = "panel-body">

                <?php
                if ($opencount == 0) {
                    echo 'No comments...';
                } else {

                    foreach ($itemcommentsarray as $key => $value) {
                        ?>
                        <article class="media"> 

                                                                                                                                                    <!--<span class="btn btn-white btn-xs pull-left"><?php // echo $itemcommentsarray[$key]['itemcomments_tsm'] . " | " . $itemcommentsarray[$key]['slottingDB_users_FIRSTNAME'] . " " . $itemcommentsarray[$key]['slottingDB_users_LASTNAME']                ?>  </span>--> 
                            <div class="pull-left media-mini text-center text-muted"> 
                                <strong class="h4"><?php echo $itemcommentsarray[$key]['itemcomments_tsm'] . " | " . $itemcommentsarray[$key]['slottingDB_users_FIRSTNAME'] . " " . $itemcommentsarray[$key]['slottingDB_users_LASTNAME'] ?></strong><br> 
                                <small class="label bg-light">Comment TSM</small> 
                            </div> 

                            <div class="media-body"> 
                                <div class="pull-right media-mini text-center text-muted"> 
                                    <strong class="h4"><?php echo $itemcommentsarray[$key]['itemcomments_date']; ?></strong><br> 
                                    <small class="label bg-light">Comment Date</small> 
                                </div> 
                                <a href="#" class="h4"><?php echo $itemcommentsarray[$key]['itemcomments_header']; ?></a> 
                                <small class="block"><?php echo $itemcommentsarray[$key]['itemcomments_comment']; ?></small> 
                            </div> 
                        </article> 
                        <?php if ($key + 1 <> $opencount) { ?>
                            <div class="line pull-in"></div> 
                        <?php } ?>

                        <?php
                    }
                }
                ?>

            </section> 
        </section>
    </div>
</div>


