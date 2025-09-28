<?php
include '../../index.php';
// التاريخ المستخدم
$date = '2022-09-01';

try {
    $result = callOracleFunctionOCI('HM_PKG', 'Get_Emp_Leave_Balance_AsDate', [100555, 1, $date]);
    echo "النتيجة: " . 2 * $result;
} catch (Exception $e) {
    echo $e->getMessage();
}
