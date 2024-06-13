<?php
# FileName="WADYN_MYSQLI_CONN.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_repnew = "localhost";
$database_repnew = "reportifynew";
$username_repnew = "root";
$password_repnew = "";

$servername = $hostname_repnew;
$username = $username_repnew;
$password = $password_repnew;
$database = $database_repnew;

@session_start();

$repnew = mysqli_init();
if (defined("MYSQLI_OPT_INT_AND_FLOAT_NATIVE")) $repnew->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, TRUE);
$repnew->real_connect($hostname_repnew, $username_repnew, $password_repnew, $database_repnew) or die("Connect Error: " . mysqli_connect_error());
