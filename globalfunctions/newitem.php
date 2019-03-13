<?php

//calculate slot qty for primary location
function _slotqty($var_PCCPKU, $var_2weekdmd, $var_EATRNQ, $var_DailyDem) {

    if ($var_DailyDem <= .5) {
        $var_EachSLOTQTY = $var_EATRNQ;
    } else {
        if ($var_PCCPKU == 0 || $var_2weekdmd < $var_PCCPKU) {
            $var_EachSLOTQTY = ceil($var_2weekdmd);
        } elseif ($var_EATRNQ > $var_PCCPKU && $var_2weekdmd > $var_EATRNQ) {
            $var_EachSLOTQTY = ceil($var_2weekdmd * .5);
        } else {
            $var_EachSLOTQTY = ceil($var_2weekdmd);
        }
    }

    return $var_EachSLOTQTY;
}

function _slotqtyround2($var_PCCPKU, $var_2weekdmd, $var_EATRNQ) {

    if ($var_PCCPKU == 0 || $var_2weekdmd < $var_PCCPKU) {
        $var_EachSLOTQTY = ceil($var_2weekdmd);
    } elseif ($var_EATRNQ > $var_PCCPKU && $var_2weekdmd > $var_EATRNQ) {
        $var_EachSLOTQTY = ceil($var_2weekdmd * .5);
    } else {
        $var_EachSLOTQTY = ceil($var_2weekdmd);
    }

    if ($var_EachSLOTQTY <= 1) {
        $var_EachSLOTQTY = 1;
    }


    return $var_EachSLOTQTY;
}

//calculate true fit - First Iteration
function _truefit($var_grid5, $var_gridheight, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin, $var_EachSLOTQTY, $var_stacklimit) {
    switch ($var_PCLIQU) {
        case "  ":


            $var_gridHprodL = min(intval($var_gridheight / $var_PCELENin), $var_stacklimit);
            $var_gridHprodW = min(intval($var_gridheight / $var_PCEWIDin), $var_stacklimit);
            $var_gridHprodH = min(intval($var_gridheight / $var_PCEHEIin), $var_stacklimit);
            $var_gridDprodL = intval($var_griddepth / $var_PCELENin);
            $var_gridDprodW = intval($var_griddepth / $var_PCEWIDin);
            $var_gridDprodH = intval($var_griddepth / $var_PCEHEIin);
            $var_gridWprodL = intval($var_gridwidth / $var_PCELENin);
            $var_gridWprodW = intval($var_gridwidth / $var_PCEWIDin);
            $var_gridWprodH = intval($var_gridwidth / $var_PCEHEIin);

            $var_attempt1 = $var_gridHprodL * $var_gridDprodW * $var_gridWprodH;
            $var_attempt2 = $var_gridHprodL * $var_gridDprodH * $var_gridWprodW;
            $var_attempt3 = $var_gridHprodW * $var_gridDprodL * $var_gridWprodH;
            $var_attempt4 = $var_gridHprodW * $var_gridDprodH * $var_gridWprodL;
            $var_attempt5 = $var_gridHprodH * $var_gridDprodL * $var_gridWprodW;
            $var_attempt6 = $var_gridHprodH * $var_gridDprodW * $var_gridWprodL;

            $var_maxtruefit = max($var_attempt1, $var_attempt2, $var_attempt3, $var_attempt4, $var_attempt5, $var_attempt6);
            break;
        default :

            $var_gridHprodH = intval($var_gridheight / $var_PCEHEIin);
            $var_gridDprodL = intval($var_griddepth / $var_PCELENin);
            $var_gridDprodW = intval($var_griddepth / $var_PCEWIDin);
            $var_gridWprodL = intval($var_gridwidth / $var_PCELENin);
            $var_gridWprodW = intval($var_gridwidth / $var_PCEWIDin);

            $var_attempt5 = $var_gridHprodH * $var_gridDprodL * $var_gridWprodW;
            $var_attempt6 = $var_gridHprodH * $var_gridDprodW * $var_gridWprodL;

            $var_maxtruefit = max($var_attempt5, $var_attempt6);
            break;
    }
    if ($var_maxtruefit >= $var_EachSLOTQTY) {
        return array($var_grid5, $var_maxtruefit);
    }
}

function _newmc($var_DailyDem) {

    if ($var_DailyDem >= 2) {
        $var_preditemMC = 'A';
    } elseif ($var_DailyDem >= 1) {
        $var_preditemMC = 'B';
    } elseif ($var_DailyDem >= .1) {
        $var_preditemMC = 'C';
    } else {
        $var_preditemMC = 'D';
    }
    return $var_preditemMC;
}

function _predtier($var_preditemMC, $var_whse) {

    if ($var_preditemMC == 'D' && ($var_whse == 2 || $var_whse == 3 || $var_whse == 6 || $var_whse == 12 )) {
        $var_predtier = 'L06';
    } else {
        $var_predtier = 'L04';
    }
    return $var_predtier;
}

function _okgrids($var_preditemMC, $var_whse) {


    if ($var_whse == 7 || $var_whse == 9 || $var_whse == 11) {

        switch ($var_preditemMC) {
            case 'A':
                $okgrids = "A','B";
                break;
            case 'B':
                $okgrids = "C','E";
                break;
            case 'C':
                $okgrids = "F', 'G', 'H', 'I', 'J";
                break;
            case 'D':
                $okgrids = "H', 'I', 'J";
                break;
        }
    } elseif ($var_whse == 12) {
        $okgrids = 'A';
    } elseif ($var_whse == 6 && $var_preditemMC = 'D') {
        $okgrids = 'A';
    } else {
        $okgrids = $var_preditemMC;
    }

    return $okgrids;
}

//calculate true fit with a given grid - first iteration
function _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin) {

    switch ($var_PCLIQU) {
        case "  ":

            $var_gridHprodL = intval($var_gridheight / $var_PCELENin);
            $var_gridHprodW = intval($var_gridheight / $var_PCEWIDin);
            $var_gridHprodH = intval($var_gridheight / $var_PCEHEIin);
            $var_gridDprodL = intval($var_griddepth / $var_PCELENin);
            $var_gridDprodW = intval($var_griddepth / $var_PCEWIDin);
            $var_gridDprodH = intval($var_griddepth / $var_PCEHEIin);
            $var_gridWprodL = intval($var_gridwidth / $var_PCELENin);
            $var_gridWprodW = intval($var_gridwidth / $var_PCEWIDin);
            $var_gridWprodH = intval($var_gridwidth / $var_PCEHEIin);

            $var_attempt1 = $var_gridHprodL * $var_gridDprodW * $var_gridWprodH;
            $var_attempt2 = $var_gridHprodL * $var_gridDprodH * $var_gridWprodW;
            $var_attempt3 = $var_gridHprodW * $var_gridDprodL * $var_gridWprodH;
            $var_attempt4 = $var_gridHprodW * $var_gridDprodH * $var_gridWprodL;
            $var_attempt5 = $var_gridHprodH * $var_gridDprodL * $var_gridWprodW;
            $var_attempt6 = $var_gridHprodH * $var_gridDprodW * $var_gridWprodL;

            $var_maxtruefit = max($var_attempt1, $var_attempt2, $var_attempt3, $var_attempt4, $var_attempt5, $var_attempt6);
            break;
        default :

            $var_gridHprodL = $var_gridHprodW = $var_gridDprodH = $var_gridWprodH = 0;
            $var_gridHprodH = intval($var_gridheight / $var_PCEHEIin);
            $var_gridDprodL = intval($var_griddepth / $var_PCELENin);
            $var_gridDprodW = intval($var_griddepth / $var_PCEWIDin);
            $var_gridWprodL = intval($var_gridwidth / $var_PCELENin);
            $var_gridWprodW = intval($var_gridwidth / $var_PCEWIDin);

            $var_attempt1 = $var_attempt2 = $var_attempt3 = $var_attempt4 = 0;
            $var_attempt5 = $var_gridHprodH * $var_gridDprodL * $var_gridWprodW;
            $var_attempt6 = $var_gridHprodH * $var_gridDprodW * $var_gridWprodL;

            $var_maxtruefit = max($var_attempt5, $var_attempt6);
            break;
    }

    return array($var_grid5, $var_maxtruefit, $var_attempt1, $var_attempt2, $var_attempt3, $var_attempt4, $var_attempt5, $var_attempt6, $var_gridHprodL, $var_gridHprodW, $var_gridHprodH, $var_gridDprodL, $var_gridDprodW, $var_gridDprodH, $var_gridWprodL, $var_gridWprodW, $var_gridWprodH);
}

//Determine product orientation
function _productorient($var_maxtruefit, $var_attempt1, $var_attempt2, $var_attempt3, $var_attempt4, $var_attempt5, $var_attempt6, $var_gridHprodL, $var_gridHprodW, $var_gridHprodH, $var_gridDprodL, $var_gridDprodW, $var_gridDprodH, $var_gridWprodL, $var_gridWprodW, $var_gridWprodH) {
    if ($var_attempt5 == $var_maxtruefit) {
        $var_itemheightorient = 'Item <b>height</b> on grid <b>height</b> fits ' . $var_gridHprodH . ' units.';
        $var_itemlengthtorient = 'Item <b>length</b> on grid <b>depth</b> fits ' . $var_gridDprodL . ' units.';
        $var_itemwidthorient = 'Item <b>width</b> on grid <b>width</b> fits ' . $var_gridWprodW . ' units.';
        return array($var_itemheightorient, $var_itemlengthtorient, $var_itemwidthorient);
    } elseif ($var_attempt6 == $var_maxtruefit) {
        $var_itemheightorient = 'Item <b>height</b> on grid <b>height</b> fits ' . $var_gridHprodH . ' units.';
        $var_itemwidthorient = 'Item <b>width</b> on grid <b>depth</b> fits ' . $var_gridDprodW . ' units.';
        $var_itemlengthtorient = 'Item <b>length</b> on grid <b>width</b> fits ' . $var_gridWprodL . ' units.';
        return array($var_itemheightorient, $var_itemlengthtorient, $var_itemwidthorient);
    } elseif ($var_attempt1 == $var_maxtruefit) {
        $var_itemlengthtorient = 'Item <b>length</b> on grid <b>height</b> fits ' . $var_gridHprodL . ' units.';
        $var_itemwidthorient = 'Item <b>width</b> on grid <b>depth</b> fits ' . $var_gridDprodW . ' units.';
        $var_itemheightorient = 'Item <b>height</b> on grid <b>width</b> fits ' . $var_gridWprodH . ' units.';
        return array($var_itemheightorient, $var_itemlengthtorient, $var_itemwidthorient);
    } elseif ($var_attempt2 == $var_maxtruefit) {
        $var_itemlengthtorient = 'Item <b>length</b> on grid <b>height</b> fits ' . $var_gridHprodL . ' units.';
        $var_itemheightorient = 'Item <b>height</b> on grid <b>depth</b> fits ' . $var_gridDprodH . ' units.';
        $var_itemwidthorient = 'Item <b>width</b> on grid <b>width</b> fits ' . $var_gridWprodW . ' units.';
        return array($var_itemheightorient, $var_itemlengthtorient, $var_itemwidthorient);
    } elseif ($var_attempt3 == $var_maxtruefit) {
        $var_itemwidthorient = 'Item <b>width</b> on grid <b>height</b> fits ' . $var_gridHprodW . ' units.';
        $var_itemlengthtorient = 'Item <b>length</b> on grid <b>depth</b> fits ' . $var_gridDprodL . ' units.';
        $var_itemheightorient = 'Item <b>height</b> on grid <b>width</b> fits ' . $var_gridWprodH . ' units.';
        return array($var_itemheightorient, $var_itemlengthtorient, $var_itemwidthorient);
    } elseif ($var_attempt4 == $var_maxtruefit) {
        $var_itemwidthorient = 'Item <b>width</b> on grid <b>height</b> fits ' . $var_gridHprodW . ' units.';
        $var_itemheightorient = 'Item <b>height</b> on grid <b>depth</b> fits ' . $var_gridDprodH . ' units.';
        $var_itemlengthtorient = 'Item <b>length</b> on grid <b>width</b> fits ' . $var_gridWprodL . ' units.';
        return array($var_itemheightorient, $var_itemlengthtorient, $var_itemwidthorient);
    }
}

//calculate true fit with a given grid - first and second iteration
function _truefitgrid2iterations($var_grid5, $var_gridheight, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin) {

    if ($var_griddepth <= 12 && substr($var_grid5, 2, 1) <> 'D') { //acount for subdivided tote dividers
        $var_gridheight -= .5;
        $var_griddepth -= .5;
        $var_gridwidth -= .5;
    }

    switch ($var_PCLIQU) {
        case '':


            $var_gridHprodL = intval($var_gridheight / $var_PCELENin);
            $var_gridHprodLmod = fmod($var_gridheight, $var_PCELENin);

            $var_gridHprodW = intval($var_gridheight / $var_PCEWIDin);
            $var_gridHprodWmod = fmod($var_gridheight, $var_PCEWIDin);

            $var_gridHprodH = intval($var_gridheight / $var_PCEHEIin);
            $var_gridHprodHmod = fmod($var_gridheight, $var_PCEHEIin);

            $var_gridDprodL = intval($var_griddepth / $var_PCELENin);
            $var_gridDprodLmod = fmod($var_griddepth, $var_PCELENin);

            $var_gridDprodW = intval($var_griddepth / $var_PCEWIDin);
            $var_gridDprodWmod = fmod($var_griddepth, $var_PCEWIDin);

            $var_gridDprodH = intval($var_griddepth / $var_PCEHEIin);
            $var_gridDprodHmod = fmod($var_griddepth, $var_PCEHEIin);

            $var_gridWprodL = intval($var_gridwidth / $var_PCELENin);
            $var_gridWprodLmod = fmod($var_gridwidth, $var_PCELENin);

            $var_gridWprodW = intval($var_gridwidth / $var_PCEWIDin);
            $var_gridWprodWmod = fmod($var_gridwidth, $var_PCEWIDin);

            $var_gridWprodH = intval($var_gridwidth / $var_PCEHEIin);
            $var_gridWprodHmod = fmod($var_gridwidth, $var_PCEHEIin);

            //Round 1 True Fits  (Grid is always static as HDW)
            $var_attempt1 = $var_gridHprodL * $var_gridDprodW * $var_gridWprodH; //LWH
            $var_attempt2 = $var_gridHprodL * $var_gridDprodH * $var_gridWprodW; //LHW
            $var_attempt3 = $var_gridHprodW * $var_gridDprodL * $var_gridWprodH; //WLH
            $var_attempt4 = $var_gridHprodW * $var_gridDprodH * $var_gridWprodL; //WHL
            $var_attempt5 = $var_gridHprodH * $var_gridDprodL * $var_gridWprodW; //HLW
            $var_attempt6 = $var_gridHprodH * $var_gridDprodW * $var_gridWprodL; //HWL
            //New LWH to be matched with $var_attempt1
            $new_dim = max($var_gridHprodLmod, $var_gridDprodWmod, $var_gridWprodHmod);
            switch ($new_dim) {
                case $var_gridHprodLmod:
                    $var_truefitarraynewlwh = _truefitgrid($var_grid5, $new_dim, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewlwh = $var_truefitarraynewlwh[1];
                    break;
                case $var_gridDprodWmod:
                    $var_truefitarraynewlwh = _truefitgrid($var_grid5, $var_gridheight, $new_dim, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewlwh = $var_truefitarraynewlwh[1];
                    break;
                case $var_gridWprodHmod:
                    $var_truefitarraynewlwh = _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $new_dim, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewlwh = $var_truefitarraynewlwh[1];
                    break;
            }
            $var_attempt1bothrounds = $var_attempt1 + $var_maxtruefitnewlwh;


            //New LHW to be matched with $var_attempt2
            $new_dim2 = max($var_gridHprodLmod, $var_gridDprodHmod, $var_gridWprodWmod);
            switch ($new_dim2) {
                case $var_gridHprodLmod:
                    $var_truefitarraynewlhw = _truefitgrid($var_grid5, $new_dim2, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewlhw = $var_truefitarraynewlhw[1];
                    break;
                case $var_gridDprodHmod:
                    $var_truefitarraynewlhw = _truefitgrid($var_grid5, $var_gridheight, $new_dim2, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewlhw = $var_truefitarraynewlhw[1];
                    break;
                case $var_gridWprodWmod:
                    $var_truefitarraynewlhw = _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $new_dim2, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewlhw = $var_truefitarraynewlhw[1];
                    break;
            }
            $var_attempt2bothrounds = $var_attempt2 + $var_maxtruefitnewlhw;


            //New WLH to be matched with $var_attempt3
            $new_dim3 = max($var_gridHprodWmod, $var_gridDprodLmod, $var_gridWprodHmod);
            switch ($new_dim3) {
                case $var_gridHprodWmod:
                    $var_truefitarraynewwlh = _truefitgrid($var_grid5, $new_dim3, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewwlh = $var_truefitarraynewwlh[1];
                    break;
                case $var_gridDprodLmod:
                    $var_truefitarraynewwlh = _truefitgrid($var_grid5, $var_gridheight, $new_dim3, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewwlh = $var_truefitarraynewwlh[1];
                    break;
                case $var_gridWprodHmod:
                    $var_truefitarraynewwlh = _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $new_dim3, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewwlh = $var_truefitarraynewwlh[1];
                    break;
            }
            $var_attempt3bothrounds = $var_attempt3 + $var_maxtruefitnewwlh;


            //New WHL to be matched with $var_attempt4
            $new_dim4 = max($var_gridHprodWmod, $var_gridDprodHmod, $var_gridWprodLmod);
            switch ($new_dim4) {
                case $var_gridHprodWmod:
                    $var_truefitarraynewwhl = _truefitgrid($var_grid5, $new_dim4, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewwhl = $var_truefitarraynewwhl[1];
                    break;
                case $var_gridDprodHmod:
                    $var_truefitarraynewwhl = _truefitgrid($var_grid5, $var_gridheight, $new_dim4, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewwhl = $var_truefitarraynewwhl[1];
                    break;
                case $var_gridWprodLmod:
                    $var_truefitarraynewwhl = _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $new_dim4, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewwhl = $var_truefitarraynewwhl[1];
                    break;
            }
            $var_attempt4bothrounds = $var_attempt4 + $var_maxtruefitnewwhl;


            //New HLW to be matched with $var_attempt5
            $new_dim5 = max($var_gridHprodHmod, $var_gridDprodLmod, $var_gridWprodWmod);
            switch ($new_dim5) {
                case $var_gridHprodHmod:
                    $var_truefitarraynewhlw = _truefitgrid($var_grid5, $new_dim5, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewhlw = $var_truefitarraynewhlw[1];
                    break;
                case $var_gridDprodLmod:
                    $var_truefitarraynewhlw = _truefitgrid($var_grid5, $var_gridheight, $new_dim5, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewhlw = $var_truefitarraynewhlw[1];
                    break;
                case $var_gridWprodWmod:
                    $var_truefitarraynewhlw = _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $new_dim5, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewhlw = $var_truefitarraynewhlw[1];
                    break;
            }
            $var_attempt5bothrounds = $var_attempt5 + $var_maxtruefitnewhlw;


            //New HWL to be matched with $var_attempt6
            $new_dim6 = max($var_gridHprodHmod, $var_gridDprodWmod, $var_gridWprodLmod);
            switch ($new_dim6) {
                case $var_gridHprodHmod:
                    $var_truefitarraynewhwl = _truefitgrid($var_grid5, $new_dim6, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewhwl = $var_truefitarraynewhwl[1];
                    break;
                case $var_gridDprodWmod:
                    $var_truefitarraynewhwl = _truefitgrid($var_grid5, $var_gridheight, $new_dim6, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewhwl = $var_truefitarraynewhwl[1];
                    break;
                case $var_gridWprodLmod:
                    $var_truefitarraynewhwl = _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $new_dim6, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewhwl = $var_truefitarraynewhwl[1];
                    break;
            }
            $var_attempt6bothrounds = $var_attempt6 + $var_maxtruefitnewhwl;




            $var_maxtruefit = max($var_attempt1, $var_attempt2, $var_attempt3, $var_attempt4, $var_attempt5, $var_attempt6);
            $var_maxtruefit2rounds = max($var_attempt1bothrounds, $var_attempt2bothrounds, $var_attempt3bothrounds, $var_attempt4bothrounds, $var_attempt5bothrounds, $var_attempt6bothrounds);
            break;
        default :


            $var_gridHprodL = $var_gridHprodW = $var_gridDprodH = $var_gridWprodH = 0;
            $var_gridHprodH = intval($var_gridheight / $var_PCEHEIin);
            $var_gridDprodL = intval($var_griddepth / $var_PCELENin);
            $var_gridDprodW = intval($var_griddepth / $var_PCEWIDin);
            $var_gridWprodL = intval($var_gridwidth / $var_PCELENin);
            $var_gridWprodW = intval($var_gridwidth / $var_PCEWIDin);

            $var_attempt1 = $var_attempt2 = $var_attempt3 = $var_attempt4 = 0;
            $var_attempt5 = $var_gridHprodH * $var_gridDprodL * $var_gridWprodW;
            $var_attempt6 = $var_gridHprodH * $var_gridDprodW * $var_gridWprodL;

            $var_maxtruefit = max($var_attempt5, $var_attempt6);
            $var_maxtruefit2rounds = $var_maxtruefit;
            break;
    }

    return array($var_maxtruefit, $var_maxtruefit2rounds);
}

//calculate true fit with a given grid - first and second iteration
function _truefitgrid2iterations_case($var_grid5, $var_gridheight, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin, $var_caseqty) {
    if ($var_caseqty === 0) {
        $var_caseqty = 1;
    }
    switch ($var_PCLIQU) {
        case "  ":

            $var_gridHprodL = intval($var_gridheight / $var_PCELENin);
            $var_gridHprodLmod = fmod($var_gridheight, $var_PCELENin);

            $var_gridHprodW = intval($var_gridheight / $var_PCEWIDin);
            $var_gridHprodWmod = fmod($var_gridheight, $var_PCEWIDin);

            $var_gridHprodH = intval($var_gridheight / $var_PCEHEIin);
            $var_gridHprodHmod = fmod($var_gridheight, $var_PCEHEIin);

            $var_gridDprodL = intval($var_griddepth / $var_PCELENin);
            $var_gridDprodLmod = fmod($var_griddepth, $var_PCELENin);

            $var_gridDprodW = intval($var_griddepth / $var_PCEWIDin);
            $var_gridDprodWmod = fmod($var_griddepth, $var_PCEWIDin);

            $var_gridDprodH = intval($var_griddepth / $var_PCEHEIin);
            $var_gridDprodHmod = fmod($var_griddepth, $var_PCEHEIin);

            $var_gridWprodL = intval($var_gridwidth / $var_PCELENin);
            $var_gridWprodLmod = fmod($var_gridwidth, $var_PCELENin);

            $var_gridWprodW = intval($var_gridwidth / $var_PCEWIDin);
            $var_gridWprodWmod = fmod($var_gridwidth, $var_PCEWIDin);

            $var_gridWprodH = intval($var_gridwidth / $var_PCEHEIin);
            $var_gridWprodHmod = fmod($var_gridwidth, $var_PCEHEIin);

            //Round 1 True Fits  (Grid is always static as HDW)
            $var_attempt1 = $var_gridHprodL * $var_gridDprodW * $var_gridWprodH; //LWH
            $var_attempt2 = $var_gridHprodL * $var_gridDprodH * $var_gridWprodW; //LHW
            $var_attempt3 = $var_gridHprodW * $var_gridDprodL * $var_gridWprodH; //WLH
            $var_attempt4 = $var_gridHprodW * $var_gridDprodH * $var_gridWprodL; //WHL
            $var_attempt5 = $var_gridHprodH * $var_gridDprodL * $var_gridWprodW; //HLW
            $var_attempt6 = $var_gridHprodH * $var_gridDprodW * $var_gridWprodL; //HWL
            //New LWH to be matched with $var_attempt1
            $new_dim = max($var_gridHprodLmod, $var_gridDprodWmod, $var_gridWprodHmod);
            switch ($new_dim) {
                case $var_gridHprodLmod:
                    $var_truefitarraynewlwh = _truefitgrid($var_grid5, $new_dim, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewlwh = $var_truefitarraynewlwh[1];
                    break;
                case $var_gridDprodWmod:
                    $var_truefitarraynewlwh = _truefitgrid($var_grid5, $var_gridheight, $new_dim, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewlwh = $var_truefitarraynewlwh[1];
                    break;
                case $var_gridWprodHmod:
                    $var_truefitarraynewlwh = _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $new_dim, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewlwh = $var_truefitarraynewlwh[1];
                    break;
            }
            $var_attempt1bothrounds = $var_attempt1 + $var_maxtruefitnewlwh;


            //New LHW to be matched with $var_attempt2
            $new_dim2 = max($var_gridHprodLmod, $var_gridDprodHmod, $var_gridWprodWmod);
            switch ($new_dim2) {
                case $var_gridHprodLmod:
                    $var_truefitarraynewlhw = _truefitgrid($var_grid5, $new_dim2, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewlhw = $var_truefitarraynewlhw[1];
                    break;
                case $var_gridDprodHmod:
                    $var_truefitarraynewlhw = _truefitgrid($var_grid5, $var_gridheight, $new_dim2, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewlhw = $var_truefitarraynewlhw[1];
                    break;
                case $var_gridWprodWmod:
                    $var_truefitarraynewlhw = _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $new_dim2, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewlhw = $var_truefitarraynewlhw[1];
                    break;
            }
            $var_attempt2bothrounds = $var_attempt2 + $var_maxtruefitnewlhw;


            //New WLH to be matched with $var_attempt3
            $new_dim3 = max($var_gridHprodWmod, $var_gridDprodLmod, $var_gridWprodHmod);
            switch ($new_dim3) {
                case $var_gridHprodWmod:
                    $var_truefitarraynewwlh = _truefitgrid($var_grid5, $new_dim3, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewwlh = $var_truefitarraynewwlh[1];
                    break;
                case $var_gridDprodLmod:
                    $var_truefitarraynewwlh = _truefitgrid($var_grid5, $var_gridheight, $new_dim3, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewwlh = $var_truefitarraynewwlh[1];
                    break;
                case $var_gridWprodHmod:
                    $var_truefitarraynewwlh = _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $new_dim3, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewwlh = $var_truefitarraynewwlh[1];
                    break;
            }
            $var_attempt3bothrounds = $var_attempt3 + $var_maxtruefitnewwlh;


            //New WHL to be matched with $var_attempt4
            $new_dim4 = max($var_gridHprodWmod, $var_gridDprodHmod, $var_gridWprodLmod);
            switch ($new_dim4) {
                case $var_gridHprodWmod:
                    $var_truefitarraynewwhl = _truefitgrid($var_grid5, $new_dim4, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewwhl = $var_truefitarraynewwhl[1];
                    break;
                case $var_gridDprodHmod:
                    $var_truefitarraynewwhl = _truefitgrid($var_grid5, $var_gridheight, $new_dim4, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewwhl = $var_truefitarraynewwhl[1];
                    break;
                case $var_gridWprodLmod:
                    $var_truefitarraynewwhl = _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $new_dim4, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewwhl = $var_truefitarraynewwhl[1];
                    break;
            }
            $var_attempt4bothrounds = $var_attempt4 + $var_maxtruefitnewwhl;


            //New HLW to be matched with $var_attempt5
            $new_dim5 = max($var_gridHprodHmod, $var_gridDprodLmod, $var_gridWprodWmod);
            switch ($new_dim5) {
                case $var_gridHprodHmod:
                    $var_truefitarraynewhlw = _truefitgrid($var_grid5, $new_dim5, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewhlw = $var_truefitarraynewhlw[1];
                    break;
                case $var_gridDprodLmod:
                    $var_truefitarraynewhlw = _truefitgrid($var_grid5, $var_gridheight, $new_dim5, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewhlw = $var_truefitarraynewhlw[1];
                    break;
                case $var_gridWprodWmod:
                    $var_truefitarraynewhlw = _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $new_dim5, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewhlw = $var_truefitarraynewhlw[1];
                    break;
            }
            $var_attempt5bothrounds = $var_attempt5 + $var_maxtruefitnewhlw;


            //New HWL to be matched with $var_attempt6
            $new_dim6 = max($var_gridHprodHmod, $var_gridDprodWmod, $var_gridWprodLmod);
            switch ($new_dim6) {
                case $var_gridHprodHmod:
                    $var_truefitarraynewhwl = _truefitgrid($var_grid5, $new_dim6, $var_griddepth, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewhwl = $var_truefitarraynewhwl[1];
                    break;
                case $var_gridDprodWmod:
                    $var_truefitarraynewhwl = _truefitgrid($var_grid5, $var_gridheight, $new_dim6, $var_gridwidth, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewhwl = $var_truefitarraynewhwl[1];
                    break;
                case $var_gridWprodLmod:
                    $var_truefitarraynewhwl = _truefitgrid($var_grid5, $var_gridheight, $var_griddepth, $new_dim6, $var_PCLIQU, $var_PCEHEIin, $var_PCELENin, $var_PCEWIDin);
                    $var_maxtruefitnewhwl = $var_truefitarraynewhwl[1];
                    break;
            }
            $var_attempt6bothrounds = $var_attempt6 + $var_maxtruefitnewhwl;




            $var_maxtruefit = max($var_attempt1, $var_attempt2, $var_attempt3, $var_attempt4, $var_attempt5, $var_attempt6) * $var_caseqty;
            $var_maxtruefit2rounds = max($var_attempt1bothrounds, $var_attempt2bothrounds, $var_attempt3bothrounds, $var_attempt4bothrounds, $var_attempt5bothrounds, $var_attempt6bothrounds) * $var_caseqty;
            break;
        default :

            $var_gridHprodL = $var_gridHprodW = $var_gridDprodH = $var_gridWprodH = 0;
            $var_gridHprodH = intval($var_gridheight / $var_PCEHEIin);
            $var_gridDprodL = intval($var_griddepth / $var_PCELENin);
            $var_gridDprodW = intval($var_griddepth / $var_PCEWIDin);
            $var_gridWprodL = intval($var_gridwidth / $var_PCELENin);
            $var_gridWprodW = intval($var_gridwidth / $var_PCEWIDin);

            $var_attempt1 = $var_attempt2 = $var_attempt3 = $var_attempt4 = 0;
            $var_attempt5 = $var_gridHprodH * $var_gridDprodL * $var_gridWprodW;
            $var_attempt6 = $var_gridHprodH * $var_gridDprodW * $var_gridWprodL;

            $var_maxtruefit = max($var_attempt5, $var_attempt6) * $var_caseqty;
            $var_maxtruefit2rounds = $var_maxtruefit;
            break;
    }

    return array($var_maxtruefit, $var_maxtruefit2rounds);
}
