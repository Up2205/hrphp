<?php
include "../../index.php";

$pfNo = filterRequest("pfNo");
$pass = filterRequest("pass");
// $password = sha1(filterRequest("password"));

$data = array("PASSWORD" => $pass);
updateData('USERS_ACCOUNTS', $data, "PFNO='$pfNo'");
