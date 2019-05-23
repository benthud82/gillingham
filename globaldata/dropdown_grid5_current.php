<?php
include_once '../connection/NYServer.php';
$var_userid = $_POST['userid'];
$whssql = $conn1->prepare("SELECT slottingDB_users_PRIMDC from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$var_userid'");
$whssql->execute();
$whssqlarray = $whssql->fetchAll(pdo::FETCH_ASSOC);

$var_whse = $whssqlarray[0]['slottingDB_users_PRIMDC'];
$tiersel = $_POST['tiersel'];

$grid5_current = $conn1->prepare("SELECT 
                            concat(LMGRD5,
                                    ' Dep: ',
                                    LMDEEP) as SUGGESTED_GRID5data,
                                    LMGRD5
                        FROM
                            gillingham.my_npfmvc
                        WHERE
                            SUGGESTED_TIER = '$tiersel'
                        GROUP BY concat(SUGGESTED_GRID5,
                                ' Depth: ',
                                SUGGESTED_DEPTH)
                        ORDER BY SUGGESTED_NEWLOCVOL");
$grid5_current->execute();
$grid5_currentarray = $grid5_current->fetchAll(pdo::FETCH_ASSOC);
?>


 <select class="selectstyle" id="grid5sel_current" name="grid5sel_current" style="width: 120px;padding: 5px; margin-right: 10px;">
    <option value="&">ALL</option>
    <?php foreach ($grid5_currentarray as $key => $value) {
      ?>  <option value="<?= $grid5_currentarray[$key]['LMGRD5']; ?>"><?php echo $grid5_currentarray[$key]['SUGGESTED_GRID5data'];?></option>
   <?php } ?>

 </select>
