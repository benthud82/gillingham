<?php

include_once("../../globalfunctions/newitem.php");
include_once '../connection/NYServer.php';
$post_grid5 = trim(strtok($_POST['grid5sel'], '-'));
$post_item = $_POST['itemnum'];



#Query the Database into a result set - 
$result = $conn1->prepare("SELECT 
                                                        (SELECT DISTINCT
                                                                slotmaster_usehigh
                                                            FROM
                                                                gillingham.slotmaster
                                                            WHERE
                                                                slotmaster_dimgroup = '$post_grid5') AS slotmaster_usehigh,
                                                        (SELECT DISTINCT
                                                                slotmaster_usedeep
                                                            FROM
                                                                gillingham.slotmaster
                                                            WHERE
                                                                slotmaster_dimgroup = '$post_grid5') AS slotmaster_usedeep,
                                                        (SELECT DISTINCT
                                                                slotmaster_usewide
                                                            FROM
                                                                gillingham.slotmaster
                                                            WHERE
                                                                slotmaster_dimgroup = '$post_grid5') AS slotmaster_usewide,
                                                        EA_HEIGHT,
                                                        EA_DEPTH,
                                                        EA_WIDTH
                                                    FROM
                                                        gillingham.item_master
                                                    WHERE
                                                        ITEM = $post_item");
$result->execute();
$resultsetarray = $result->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultsetarray as $key => $value) {
    $var_gridheight = $resultsetarray[$key]['slotmaster_usehigh'];
    $var_griddepth = $resultsetarray[$key]['slotmaster_usedeep'];
    $var_gridwidth = $resultsetarray[$key]['slotmaster_usewide'];
    $var_PCEHEIin = $resultsetarray[$key]['EA_HEIGHT'];
    $var_PCELENin = $resultsetarray[$key]['EA_DEPTH'];
    $var_PCEWIDin = $resultsetarray[$key]['EA_WIDTH'];
}



//call truefitgrid function to calculate true fit.
$var_truefitarray = _truefitgrid($post_grid5, $var_gridheight, $var_griddepth, $var_gridwidth, ' ', $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);

$var_EachRecSlot = $var_truefitarray[0];
$var_maxtruefit = $var_truefitarray[1];
$var_attempt1 = $var_truefitarray[2];
$var_attempt2 = $var_truefitarray[3];
$var_attempt3 = $var_truefitarray[4];
$var_attempt4 = $var_truefitarray[5];
$var_attempt5 = $var_truefitarray[6];
$var_attempt6 = $var_truefitarray[7];
$var_gridHprodL = $var_truefitarray[8];
$var_gridHprodW = $var_truefitarray[9];
$var_gridHprodH = $var_truefitarray[10];
$var_gridDprodL = $var_truefitarray[11];
$var_gridDprodW = $var_truefitarray[12];
$var_gridDprodH = $var_truefitarray[13];
$var_gridWprodL = $var_truefitarray[14];
$var_gridWprodW = $var_truefitarray[15];
$var_gridWprodH = $var_truefitarray[16];

//call productorient function to determine how to place product in grid to achieve max true fit
$var_orientarray = _productorient($var_maxtruefit, $var_attempt1, $var_attempt2, $var_attempt3, $var_attempt4, $var_attempt5, $var_attempt6, $var_gridHprodL, $var_gridHprodW, $var_gridHprodH, $var_gridDprodL, $var_gridDprodW, $var_gridDprodH, $var_gridWprodL, $var_gridWprodW, $var_gridWprodH);
$var_itemheightorient = $var_orientarray[0];
$var_itemlengthtorient = $var_orientarray[1];
$var_itemwidthorient = $var_orientarray[2];
//      Logic to calculate true fit
//      Variables from "../PHPLogic/tflogic.php"
echo 'The true fit for item <b>' . $post_item . '</b> in a <b>' . $post_grid5 . '</b> is <b>' . $var_maxtruefit . '</b> units.';
echo '<br><br><div class="line-separator"></div>';
echo "<ul><li> $var_itemheightorient</li>";
echo "<li> $var_itemlengthtorient</li>";
echo "<li> $var_itemwidthorient</li></ul>";

$var_truefitarrayround2 = _truefitgrid2iterations($post_grid5, $var_gridheight, $var_griddepth, $var_gridwidth, ' ', $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);

$var_round2tf = $var_truefitarrayround2[1] - $var_truefitarrayround2[0];
echo '<div class="line-separator"></div> <br>';
echo 'Two round true fit is <b>' . $var_truefitarrayround2[1] . '</b> units.';
