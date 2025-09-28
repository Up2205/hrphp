<?php
include "../index.php";

$pfno = filterRequest("userId");
$startDate = filterRequest("startDate"); //تكون بهذي الصيغة (29-JAN-2025)
$endDate = filterRequest("endDate"); //تكون بهذي الصيغة (29-JAN-2025)
$rdRtype = filterRequest("rdRtype");


$params = [
    $pfno,
    $startDate,
    $endDate,
    $rdRtype,   // p_rd_rtype
];

$result = callOracleTableFunctionOCI("HM_PKG_API", "GET_ATTENDANCE", $params);

if (!empty($result)) {
    printSuccess("", $result);
} else {
    printFailure("لا يوجد بيانات");
}
