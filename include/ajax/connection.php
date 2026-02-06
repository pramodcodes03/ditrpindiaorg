<?php

$root_path = $_SERVER['DOCUMENT_ROOT'];

//Database configuration 
//require_once("include/classes/database_results.php");

//enter the database details here
$host = 'localhost';
$username = 'root';
$password = '';
$database_name = 'temple_learning';

$ConnectionObject = new database_results();
$link = $ConnectionObject->connect_selct_db($host,$username,$password,$database_name);


?>