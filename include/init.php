<?php

$pdoObject = new PDO('mysql:host=localhost; dbname=bijouterie',"root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));

session_start();

$notification = "";
$counter = 0;
$counterErreur =0;

define("URL", "http://localhost/bijouterie/");

// echo "<pre>"
// print_r($_SERVER);
// echo "</pre>";

require('fonction.php');
tableau($_SESSION);
//les fonctions
