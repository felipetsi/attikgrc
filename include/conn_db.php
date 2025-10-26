<?php
//ini_set(display_errors, true); 
//error_reporting(E_ALL);
$server = "localhost";
$userDB = "arm_user";
$passwd ="senha_facil123";
$LANG_NAMEDB="labsec";
$port="5432";
$conn = pg_connect("dbname=$LANG_NAMEDB port=$port host=$server user=$userDB password=$passwd");
?>
