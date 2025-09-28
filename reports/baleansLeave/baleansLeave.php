<?php
include "../../index.php";

$pfno = filterRequest("userId");
$leaveCode = filterRequest("leaveCode");
$year = filterRequest("year");

$result = callOracleTableFunctionOCI(
    "HM_PKG_API",
    "GET_LEAVE_BALANCE",
    [$pfno, $leaveCode, $year]
);


if (!empty($result)) {
    printSuccess("", $result);
} else {
    printFailure("لا يوجد بيانات");
}
