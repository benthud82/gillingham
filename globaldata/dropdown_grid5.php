<?php
include_once '../connection/NYServer.php';
$var_userid = $_POST['userid'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];
$tiersel = $_POST['tiersel'];

$grid5 = $conn1->prepare("SELECT 
                           concat(SUGGESTED_GRID5,
                                ' - ',
                                SUGGESTED_DEPTH) as SUGGESTED_GRID5data,
                                    SUGGESTED_GRID5, SUGGESTED_NEWLOCVOL as VOL
                        FROM
                            gillingham.my_npfmvc
                        WHERE
                            SUGGESTED_TIER = '$tiersel'
                        GROUP BY   concat(SUGGESTED_GRID5,
                                 ' - ',
                                SUGGESTED_DEPTH),
                                    SUGGESTED_GRID5, SUGGESTED_GRID5, SUGGESTED_NEWLOCVOL
                        ORDER BY SUGGESTED_NEWLOCVOL");
$grid5->execute();
$grid5array = $grid5->fetchAll(pdo::FETCH_ASSOC);
?>


 <select class="selectstyle" id="grid5sel" name="grid5sel" style="width: 125px;padding: 5px; margin-right: 10px;">
    <option value="%">ALL</option>
    <?php foreach ($grid5array as $key => $value) {
      ?>  <option value="<?= $grid5array[$key]['SUGGESTED_GRID5data']; ?>"><?php echo $grid5array[$key]['SUGGESTED_GRID5data'];?></option>
   <?php } ?>

 </select>
