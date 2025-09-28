<?php
include "../../index.php";

$userId = filterRequest("userId");
$password = sha1(filterRequest("password"));

$data = array("PASSWORD" => $password);
updateData('USERS_ACCOUNTS', $data, "PFNO='$userId'");
