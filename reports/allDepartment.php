<?php
include "../index.php";

$params = [
    '01-JAN-25',  // dd-MON-yy
    '31-JAN-25',
];

$rows = callOracleTableFunctionOCI("HM_PKG_API", "GET_LEAVE_SUMMARY", $params);

echo json_encode($rows);
