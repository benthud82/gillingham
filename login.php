<?php

$sessionuser = $_POST["username"];
$sessionpw = $_POST["password"];
include_once 'connection/NYServer.php';

include 'globaldata/inusertable.php';  //has the user registered?
if (count($usersetarray) == 0) {  //the user is not logged in redirect to registration page
    header('Location: registration.php');
}


$pass = $conn1->prepare("SELECT slottingDB_users_PASS as hashedpass from gillingham.slottingdb_users WHERE idslottingDB_users_ID = '$sessionuser' ");  //$orderby pulled from: include 'slopecat_switch_orderby.php';
$pass->execute();
$passarray = $pass->fetchAll(pdo::FETCH_ASSOC);
$hashedPasswordFromDB = $passarray[0]['hashedpass'];

if (password_verify($sessionpw, $hashedPasswordFromDB)) {

    if (count($usersetarray) == 0) {  //the user is not logged in redirect to registration page
        header('Location: registration.php');
    } elseif (isset($conn1)) {

        // If correct, we set the session to YES
        session_start();
        $_SESSION["Login"] = "YES";
        $_SESSION['LAST_ACTIVITY'] = time();
        $_SESSION['MYUSER'] = $_POST["username"];
        $_SESSION['MYPASS'] = $_POST["password"];

        //write to MySQL Database that user logged in:
        include_once 'connection/NYServer.php';
        date_default_timezone_set('Europe/London');
        $datetime = date('Y-m-d H:i:s');
        $usertsm = $_SESSION['MYUSER'];
        $result1 = $conn1->prepare("INSERT INTO gillingham.slottingDBlogin (idcustomerauditlogin, customeraudit_TSM, customeraudit_datetime) values (0,'$usertsm','$datetime')");
        $result1->execute();

        header('Location: dashboard.php');
    }
} else {

    // If not correct, we set the session to NO
    session_start();
    $_SESSION["Login"] = "NO";
    echo "<h1>Oops!  Something went wrong! </h1>";
}
