<?php

//get whse for user
if (isset($_SESSION['MYUSER'])) {
    $var_userid = $_SESSION['MYUSER'];


    $maperror = $conn1->prepare("SELECT 
                                                                bayloc, vectormap, maxmin, dimissues
                                                            FROM
                                                                gillingham.badges;");
    $maperror->execute();
    $maperrorarray = $maperror->fetchAll(pdo::FETCH_ASSOC);
}
if (isset($maperrorarray)) {
    $bayloc = $maperrorarray[0]['bayloc'];
    $vectormap = $maperrorarray[0]['vectormap'];
    $maxmin = $maperrorarray[0]['maxmin'];
    $dimissues = $maperrorarray[0]['dimissues'];
} else {
    $bayloc = 0;
    $vectormap = 0;
    $maxmin = 0;
    $dimissues = 0;
}