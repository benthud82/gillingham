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
    
    
    $maperror2 = $conn1->prepare("SELECT 
                                                            COUNT(*) AS maperrorcount2
                                                        FROM
                                                            gillingham.bayloc_errors");
    $maperror2->execute();
    $maperrorarray2 = $maperror2->fetchAll(pdo::FETCH_ASSOC);
}
if (isset($maperrorarray)) {
    $maperrorcount = $maperrorarray[0]['maperrorcount'] + $maperrorarray2[0]['maperrorcount2'];
} else {
    $maperrorcount = 0;
}