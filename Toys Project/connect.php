<?php
header("Access-Control-Allow-Origin: *");
// קובץ שאחראי כל כל התתחברות למסד נתונים
// $dbhost = "localhost";
// $dbuser = "root";
// $dbpass = "";
// $dbname = "panda4";
$dbhost = "localhost";
$dbuser = "id16866059_galzooz1";
$dbpass = '4eE}#a{$h^#Id>{A';
$dbname = "id16866059_shop";

// ככה אני מזהה שאני בשרת מקומי
if($_SERVER["SERVER_ADDR"] == "::1"){
  $dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "panda4";
}

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (mysqli_connect_error()) {
  // die -> עוצר את השרת ומציג את ההודעה
  die("cant connect");
}
// שידע לתמוך בעברית ובשפות
$conn->query("SET NAMES 'utf8'");