<?php

function _primdc($DC) {
    switch ($DC) {
        case 2:
            $stringdc = 'Indy';
            break;
        case 3:
            $stringdc = 'Sparks';
            break;
        case 6:
            $stringdc = 'Denver';
            break;
        case 7:
            $stringdc = 'Dallas';
            break;
        case 9:
            $stringdc = 'Jax';
            break;
        case 11:
            $stringdc = 'NOTL';
            break;
        case 12:
            $stringdc = 'Vanc';
            break;
        case 16:
            $stringdc = 'Calgary';
            break;

        default:
            $stringdc = 'N/A';
            break;
    }
    return $stringdc;
}

function _searchForKey($id, $array, $searchkey) {
    foreach ($array as $key => $val) {
        if ($val[$searchkey] === $id) {
            return $key;
        }
    }
    return null;
}

function _rolling12startfiscal() {
    $current_month = date('m');
    $prev_year = date("Y", strtotime("-1 year"));
    $rolling_12_start_fiscal = date("Ym", mktime(0, 0, 0, $current_month, 1, $prev_year));

    return $rolling_12_start_fiscal;
}

function _currentquarterfiscal() {
    $current_month = date('m');
    if ($current_month <= 3) {
        $current_quarter_start = 1;
    } elseif ($current_month <= 6) {
        $current_quarter_start = 4;
    } elseif ($current_month <= 9) {
        $current_quarter_start = 7;
    } else {
        $current_quarter_start = 10;
    }

    $current_quarter_start_fiscal = date("Ym", mktime(0, 0, 0, $current_quarter_start, 1, date('Y')));
//$end_date = date("Y-m-d H:i:s", mktime(0, 0, 0, $current_quarter_start + 3, 1, date('Y')));   This is the end of the quarter if needed later

    return $current_quarter_start_fiscal;
}

function _rollquarterfiscal() {
    $date = strtotime(date('Y-m-d H:i:s') . ' -90 days');
    $roll_qtr_start_fiscal = date("Ym", $date);

    return $roll_qtr_start_fiscal;
}

function _currentmonthfiscal() {

    $current_month_start_fiscal = date("Ym", mktime(0, 0, 0, date('m'), 1, date('Y')));

    return $current_month_start_fiscal;
}

function _rollmonthfiscal() {
    $date = strtotime(date('Y-m-d H:i:s') . ' -30 days');
    $roll_month_start_fiscal = date("Ym", $date);

    return $roll_month_start_fiscal;
}

function _currentmonth1yyddd() {
    $startyear = date('y');
    $startday = date('z', mktime(0, 0, 0, date('m'), 1, date('Y'))) + 1;
    if ($startday < 10) {
        $startday = '00' . $startday;
    } else if ($startday < 100) {
        $startday = '0' . $startday;
    }
    $datej = intval('1' . $startyear . $startday);
    return $datej;
}

function _rollmonth1yyddd() {
    $date = strtotime(date('Y-m-d H:i:s') . ' -30 days');

    $startyear = date('y', $date);
    $startday = date('z', $date) + 1;
    if ($startday < 10) {
        $startday = '00' . $startday;
    } else if ($startday < 100) {
        $startday = '0' . $startday;
    }
    $datej = intval('1' . $startyear . $startday);
    return $datej;
}

function _currentquarter1yyddd() {

    $current_month = date('m');
    if ($current_month <= 3) {
        $current_quarter_start = 1;
    } elseif ($current_month <= 6) {
        $current_quarter_start = 4;
    } elseif ($current_month <= 9) {
        $current_quarter_start = 7;
    } else {
        $current_quarter_start = 10;
    }


    $current_quarter_start_fiscal = date("Y-m-d", mktime(0, 0, 0, $current_quarter_start, 1, date('Y')));



    $startyear = date('y', strtotime($current_quarter_start_fiscal));
    $startday = date('z', strtotime($current_quarter_start_fiscal)) + 1;
    if ($startday < 10) {
        $startday = '00' . $startday;
    } else if ($startday < 100) {
        $startday = '0' . $startday;
    }
    $datej = intval('1' . $startyear . $startday);
    return $datej;
}

function _rollquarter1yyddd() {
    $date = strtotime(date('Y-m-d H:i:s') . ' -90 days');


    $startyear = date('y', $date);
    $startday = date('z', $date) + 1;
    if ($startday < 10) {
        $startday = '00' . $startday;
    } else if ($startday < 100) {
        $startday = '0' . $startday;
    }
    $datej = intval('1' . $startyear . $startday);
    return $datej;
}

function _rolling12start1yyddd() {
    $current_month = date('m');
    $prev_year = date("Y", strtotime("-1 year"));
    $rolling_12_start_fiscal = date("Y-m-d", mktime(0, 0, 0, $current_month, 1, $prev_year));


    $startyear = date('y', strtotime($rolling_12_start_fiscal));
    $startday = date('z', strtotime($rolling_12_start_fiscal)) + 1;
    if ($startday < 10) {
        $startday = '00' . $startday;
    } else if ($startday < 100) {
        $startday = '0' . $startday;
    }
    $datej = intval('1' . $startyear . $startday);
    return $datej;
}

function _yydddtogregdate($date) {
    $a1 = substr($date, 2, 3);
    $a2 = substr($date, 0, 2);
    $converteddate = date("m/d/Y", mktime(0, 0, 0, 1, $a1, $a2));

    return $converteddate;
}

function _1yydddtogregdate($date) {
    $a1 = substr($date, 3, 3);
    $a2 = substr($date, 1, 2);
    $converteddate = date("m/d/Y", mktime(0, 0, 0, 1, $a1, $a2));

    return $converteddate;
}

function _currentmonthyyddd() {
    $startyear = date('y');
    $startday = date('z', mktime(0, 0, 0, date('m'), 1, date('Y'))) + 1;
    if ($startday < 10) {
        $startday = '00' . $startday;
    } else if ($startday < 100) {
        $startday = '0' . $startday;
    }
    $datej = intval($startyear . $startday);
    return $datej;
}

function _rollmonthyyddd() {
    $date = strtotime(date('Y-m-d H:i:s') . ' -30 days');

    $startyear = date('y', $date);
    $startday = date('z', $date) + 1;
    if ($startday < 10) {
        $startday = '00' . $startday;
    } else if ($startday < 100) {
        $startday = '0' . $startday;
    }
    $datej = intval($startyear . $startday);
    return $datej;
}

function _rollmonthyyyymmdd() {
    $date = strtotime(date('Y-m-d H:i:s') . ' -30 days');
    $date2 = date("Ymd", $date);

    return $date2;
}

function _rollqtryyyymmdd() {
    $date = strtotime(date('Y-m-d H:i:s') . ' -90 days');
    $date2 = date("Ymd", $date);

    return $date2;
}

function _roll12yyyymmdd() {
    $date = strtotime(date('Y-m-d H:i:s') . ' -365 days');
    $date2 = date("Ymd", $date);

    return $date2;
}

function _roll10dayyyyymmdd() {
    $date = strtotime(date('Y-m-d H:i:s') . ' -10 days');
    $date2 = date("Ymd", $date);

    return $date2;
}

function _currentquarteryyddd() {

    $current_month = date('m');
    if ($current_month <= 3) {
        $current_quarter_start = 1;
    } elseif ($current_month <= 6) {
        $current_quarter_start = 4;
    } elseif ($current_month <= 9) {
        $current_quarter_start = 7;
    } else {
        $current_quarter_start = 10;
    }


    $current_quarter_start_fiscal = date("Y-m-d", mktime(0, 0, 0, $current_quarter_start, 1, date('Y')));



    $startyear = date('y', strtotime($current_quarter_start_fiscal));
    $startday = date('z', strtotime($current_quarter_start_fiscal)) + 1;
    if ($startday < 10) {
        $startday = '00' . $startday;
    } else if ($startday < 100) {
        $startday = '0' . $startday;
    }
    $datej = intval($startyear . $startday);
    return $datej;
}

function _rollquarteryyddd() {

    $date = strtotime(date('Y-m-d H:i:s') . ' -90 days');


    $startyear = date('y', $date);
    $startday = date('z', $date) + 1;
    if ($startday < 10) {
        $startday = '00' . $startday;
    } else if ($startday < 100) {
        $startday = '0' . $startday;
    }
    $datej = intval($startyear . $startday);
    return $datej;
}

function _rolling12startyyddd() {
    $current_month = date('m');
    $prev_year = date("Y", strtotime("-1 year"));
    $rolling_12_start_fiscal = date("Y-m-d", mktime(0, 0, 0, $current_month, 1, $prev_year));


    $startyear = date('y', strtotime($rolling_12_start_fiscal));
    $startday = date('z', strtotime($rolling_12_start_fiscal)) + 1;
    if ($startday < 10) {
        $startday = '00' . $startday;
    } else if ($startday < 100) {
        $startday = '0' . $startday;
    }
    $datej = intval($startyear . $startday);
    return $datej;
}

function _gregdateto1yyddd($convertdate) {
    $startyear = date('y', strtotime($convertdate));
    $startday = date('z', strtotime($convertdate)) + 1;
    if ($startday < 10) {
        $startday = '00' . $startday;
    } else if ($startday < 100) {
        $startday = '0' . $startday;
    }
    $datej = intval('1' . $startyear . $startday);
    return $datej;
}

function _gregdatetoyyddd($convertdate) {
    $startyear = date('y', strtotime($convertdate));
    $startday = date('z', strtotime($convertdate)) + 1;
    if ($startday < 10) {
        $startday = '00' . $startday;
    } else if ($startday < 100) {
        $startday = '0' . $startday;
    }
    $datej = intval($startyear . $startday);
    return $datej;
}

function _customerscorecardpanelclass($score) {
    if ($score < 40) {
        $customerscorecardclass = 'panel-70';
        return $customerscorecardclass;
    } else if ($score < 65) {
        $customerscorecardclass = 'panel-80';
        return $customerscorecardclass;
    } else {
        $customerscorecardclass = 'panel-90';
        return $customerscorecardclass;
    }
}

function _newcustomerscorecardpanelclass($score) {
    if ($score < 40) {
        $customerscorecardclass = 'bg-danger';
        return $customerscorecardclass;
    } else if ($score < 65) {
        $customerscorecardclass = 'bg-warning';
        return $customerscorecardclass;
    } else {
        $customerscorecardclass = 'bg-success';
        return $customerscorecardclass;
    }
}

function _customerscorecardstatclass($score) {
    if ($score < 40) {
        $customerscorecardclass = 'red-thunderbird';
        return $customerscorecardclass;
    } else if ($score < 65) {
        $customerscorecardclass = 'yellow-gold';
        return $customerscorecardclass;
    } else {
        $customerscorecardclass = 'green-seagreen';
        return $customerscorecardclass;
    }
}
function _newcustomerscorecardstatclass($score) {
    if ($score < 40) {
        $customerscorecardclass = 'bg-danger';
        return $customerscorecardclass;
    } else if ($score < 65) {
        $customerscorecardclass = 'bg-warning';
        return $customerscorecardclass;
    } else {
        $customerscorecardclass = 'bg-success';
        return $customerscorecardclass;
    }
}

function linear_regression($x, $y) {

    // calculate number points
    $n = count($x);

    // ensure both arrays of points are the same size
    if ($n != count($y)) {

        trigger_error("linear_regression(): Number of elements in coordinate arrays do not match.", E_USER_ERROR);
    }

    // calculate sums
    $x_sum = array_sum($x);
    $y_sum = array_sum($y);

    $xx_sum = 0;
    $xy_sum = 0;

    for ($i = 0; $i < $n; $i++) {

        $xy_sum+=($x[$i] * $y[$i]);
        $xx_sum+=($x[$i] * $x[$i]);
    }
    if ((($n * $xx_sum) - ($x_sum * $x_sum)) == 0) {
        echo 'here';
    } else {

        // calculate slope
        $m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));
    }
    // calculate intercept
    $b = ($y_sum - ($m * $x_sum)) / $n;

    // return result
    return array("m" => $m, "b" => $b);
}
