<?php


include "../../index.php";

$user = filterRequest("user");
$pass = filterRequest("pass");

if (getCuont("MVEMPLOYEES", "PASSWORD IS NULL AND PFNO='$user'") > 0) {

    getData('MVEMPLOYEES', "PFNO='$user' AND EMPLOYEE_STATUS=1 AND EMP_TEL='$pass' AND PFNO NOT IN ( SELECT MANAGER_CODE FROM MVEMPLOYEES  WHERE MANAGER_CODE ='$user')");
} else {
    getData('MVEMPLOYEES', "PFNO='$user' AND EMPLOYEE_STATUS=1 AND PASSWORD='$pass' AND PFNO NOT IN ( SELECT MANAGER_CODE FROM MVEMPLOYEES  WHERE MANAGER_CODE ='$user')");
}
