<?php
include "../index.php";

$userId = filterRequest("userId");
$year = filterRequest("year");
$month = filterRequest("month");

getData("VW_EMP_SALARY", "MONTH_NO='$month' AND CUR_YEAR='$year' AND PFNO='$userId'");
