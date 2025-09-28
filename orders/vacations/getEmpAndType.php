<?php

include "../../index.php";
$userid = filterRequest('userId');


$data = array();

// جلب بيانات الموظفين
// $data['emp'] = getAllDataBySelect("SELECT PFNO ,EMP_NAME FROM EMPLOYEE WHERE PFNO NOT IN ( SELECT MANAGER_CODE FROM EMPLOYEE WHERE PFNO !=MANAGER_CODE) ORDER BY PFNO ", null,  false);

// جلب أنواع الإجازة
$data['LEAVE_TYPE'] = getAllData('LEAVE_TYPE', null, null, false);

// إذا تم جلب الموظفين والإجازات بنجاح
if ($data['LEAVE_TYPE'] != false) {
    $curDate = strtoupper(date('d-M-y'));

    foreach ($data['LEAVE_TYPE'] as $index => $leave) {
        $leaveCode = $leave['LEAVE_CODE'];

        // استدعاء Oracle Function لجلب Balance
        try {
            $balance = callOracleFunction('HM_PKG', 'Get_Emp_Leave_Balance_AsDate', [$userid, $leaveCode, $curDate]);
        } catch (Exception $e) {
            $balance = 0; // لو فشل نرجع 0
        }

        // نضيفه للعنصر
        $data['LEAVE_TYPE'][$index]['Balance'] = $balance;
    }

    $data['status'] = "success";
    echo json_encode($data);
} else {
    printFailure();
}
