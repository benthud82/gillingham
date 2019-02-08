<?php

//get whse for user
if (isset($_SESSION['MYUSER'])) {
    $var_userid = $_SESSION['MYUSER'];


    $maperror = $conn1->prepare("SELECT 
                                                            COUNT(*) AS maperrorcount
                                                        FROM
                                                            gillingham.vectormaperrors");
    $maperror->execute();
    $maperrorarray = $maperror->fetchAll(pdo::FETCH_ASSOC);
}
if (isset($maperrorarray)) {
    $maperrorcount = $maperrorarray[0]['maperrorcount'];
} else {
    $maperrorcount = 0;
}