
<?php
ini_set('max_execution_time', 99999);

include_once '../connection/NYServer.php';
$var_userid = $_POST['userid'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];
$var_item = $_POST['itemnum'];

$result1 = $conn1->prepare("SELECT 
                                *
                            FROM
                                gillingham.slotting_itemcomments
                            JOIN gillingham.slottingdb_users on idslottingDB_users_ID = itemcomments_tsm
                            WHERE
                                 itemcomments_item = $var_item
                            ORDER BY itemcomments_date DESC;");
$result1->execute();
$itemcommentsarray = $result1->fetchAll(pdo::FETCH_ASSOC);
$opencount = count($itemcommentsarray);
?>
<div id="commentmodal_container">
    <div class = "row">
        <div class = "col-sm-12 portlet ui-sortable">
            <section class = "panel portlet-item" style = "opacity: 1;">
                <header class = "panel-heading bg-info" style = "font-size: 20px"> Item Comments   </header>
                <section class = "panel-body">

                    <?php
                    if ($opencount == 0) {
                        echo 'No comments...';
                    } else {

                        foreach ($itemcommentsarray as $key => $value) {
                            ?>
                            <article class="media"> 

                                            <!--<span class="btn btn-white btn-xs pull-left"><?php // echo $itemcommentsarray[$key]['itemcomments_tsm'] . " | " . $itemcommentsarray[$key]['slottingDB_users_FIRSTNAME'] . " " . $itemcommentsarray[$key]['slottingDB_users_LASTNAME']   ?>  </span>--> 
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
</div>

