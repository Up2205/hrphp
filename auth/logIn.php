<?php
include "../index.php";


$user = filterRequest("user");
$pass = filterRequest("pass");




getData('MVEMPLOYEES', "PFNO='$user' AND EMPLOYEE_STATUS=1 AND PASSWORD='$pass'");
