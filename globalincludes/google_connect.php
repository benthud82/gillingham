<?php
//Connect to MySQL
$dbtype = "mysql";
$dbhost = "104.154.153.225"; // Host name
$dbuser = "bentley"; // Mysql username
$dbpass = "dave41"; // Mysql password
$dbname = "testdb"; // Database name
$conn1 = new PDO("{$dbtype}:host={$dbhost};dbname={$dbname};charset=utf8", $dbuser, $dbpass, array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));

//
//$user    = 'BHUD01';
//$pass    = 'dave41';
//$host    = '127.0.0.1';
//$db      = 'localhost';
//$charset = 'utf8';
//
//$conn1 = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
//$conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
