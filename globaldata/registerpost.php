<?php

//add the user to the slottingdb_users table
include_once '../connection/NYServer.php';
$options = [
    'cost' => 11,
];
// Get the password from post
$passwordFromPost = $_POST['pass'];
$hash = password_hash($passwordFromPost, PASSWORD_BCRYPT, $options);

$userid = $_POST["username"];
$userfirst = $_POST["firstname"];
$userlast = $_POST["lastname"];
$userDC = intval($_POST["whsesel"]);


$result1 = $conn1->prepare("INSERT INTO gillingham.slottingdb_users (idslottingDB_users_ID, slottingDB_users_FIRSTNAME, slottingDB_users_LASTNAME, slottingDB_users_PRIMDC, slottingDB_users_PASS) values ('$userid','$userfirst','$userlast', $userDC, '$hash')");
$result1->execute();

header('Location: ../signin.php');
