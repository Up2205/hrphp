<?php
$host = 'localhost';
$port = '1521';
$sid = 'IT';
$username = 'YCPD';
$password = 'A';
$dsn = "oci:dbname=//$host:$port/$sid;charset=UTF8";
try {
  $con = new PDO($dsn, $username, $password);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  include "functions.php";
  // echo "conncent";
} catch (PDOException $e) {
  echo "Connection failed:1 " . $e->getMessage();
}
