<?php
include "index.php";


$pfNo = filterRequest("user");
$pass = filterRequest("pass");

getAllData("MVEMPLOYEES", "PFNO='$pfNo'");




// getData('MVEMPLOYEES', "PFNO='$pfNo' AND EMPLOYEE_STATUS=1 AND PASSWORD='$pass'");