<?php
$dsn = "mysql:host=localhost;dbname=hr";
$user = "root";
$pass = "";
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"
);
$countrowinpage = 9;
try {
    $con = new PDO($dsn, $user, $pass, $option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //header("Access-Control-Allow-Origin: *");
    //header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Access-Control-Allow-Origin");
    //header("Access-Control-Allow-Methods: POST, OPTIONS , GET");
    include "functions.php";
    if (!isset($notAuth)) {
        // checkAuthenticate();
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}




// <?php
// $host = 'localhost';
// $port = '1521';
// $sid = 'YCPD';
// $username = 'HUMAN_YCPD';
// $password = 'a';
// $dsn = "oci:dbname=$host:$port/$sid;charset=UTF8";
// try {
//   $con = new PDO($dsn, $username, $password);
//   $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//   include "functions.php";
//   echo "conncent";
// } catch (PDOException $e) {
//   echo "Connection failed:1 " . $e->getMessage();
// }