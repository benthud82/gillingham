<?php
include_once '../connection/NYServer.php';
$var_userid = $_POST['userid'];
$var_whse = 'GB0001';
$tiersel = $_POST['tiersel'];

$grid5 = $conn1->prepare("SELECT 
                                                        CONCAT(SUGGESTED_GRID5,
                                                                ' Depth: ',
                                                                SUGGESTED_DEPTH) AS SUGGESTED_GRID5data,
                                                        SUGGESTED_GRID5,
                                                        SUGGESTED_NEWLOCVOL AS VOLUME,
                                                        COUNT(*)
                                                    FROM
                                                        gillingham.my_npfmvc
                                                    WHERE
                                                        SUGGESTED_TIER = '$tiersel'
                                                    GROUP BY CONCAT(SUGGESTED_GRID5,
                                                            ' Depth: ',
                                                            SUGGESTED_DEPTH) , SUGGESTED_GRID5 , SUGGESTED_NEWLOCVOL
                                                    ORDER BY SUGGESTED_NEWLOCVOL");
$grid5->execute();
$grid5array = $grid5->fetchAll(pdo::FETCH_ASSOC);
?>


<select class="form-control" id="grid5sel" name="grid5sel"  onchange="buttonstatuscheck();">
    <option value="0"></option>
    <?php foreach ($grid5array as $key => $value) {
      ?>  <option value="<?= $grid5array[$key]['SUGGESTED_GRID5']; ?>"><?php echo $grid5array[$key]['SUGGESTED_GRID5data'];?></option>
   <?php } ?>

 </select>
