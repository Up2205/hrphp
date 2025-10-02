<?php
include "../../index.php";


$divId = filterRequest("divId");


$where = "EMPLOYEE_STATUS = 1";
if (!empty($divId) && $divId != 1) {

    $where .= " AND DIV_CODE = " . $divId;
}

// جلب جميع البيانات للموظفين حسب الشرط


try {
    $data = getAllData('VEMPLOYEES', $where, null, false);
    $result = [];

    foreach ($data as $row) {
        // تأكد من وجود المفاتيح وتحوّلها لنوع مناسب
        $divCode = isset($row['DIV_CODE']) ? (string) $row['DIV_CODE'] : '';
        $divName = isset($row['DIV_NAME']) ? (string) $row['DIV_NAME'] : '';
        $secCode = isset($row['SEC_CODE']) ? (string) $row['SEC_CODE'] : '';
        $secName = isset($row['SEC_NAME']) ? (string) $row['SEC_NAME'] : '';
        $pfno    = isset($row['PFNO']) ? (string) $row['PFNO'] : '';
        $empName = isset($row['EMP_NAME']) ? (string) $row['EMP_NAME'] : '';

        if ($divCode === '' || $secCode === '' || $pfno === '') {
            // تخطّي الصف لو ناقص مفاتيح أساسية
            continue;
        }

        // إنشاء الفرع إذا لم يوجد
        if (!isset($result[$divCode])) {
            $result[$divCode] = [
                "divId"    => $divCode,
                "name"     => $divName,
                "sections" => []
            ];
        }

        // إنشاء القسم داخل الفرع إذا لم يوجد
        if (!isset($result[$divCode]["sections"][$secCode])) {
            $result[$divCode]["sections"][$secCode] = [
                "secId"     => $secCode,
                "name"      => $secName,
                "employees" => []
            ];
        }

        // إضافة الموظف
        $result[$divCode]["sections"][$secCode]["employees"][] = [
            "PFNO"     => $pfno,
            "EMP_NAME" => $empName
        ];
    }

    // تحويل الأقسام من associative إلى indexed array لكل div
    foreach ($result as $key => $div) {
        $sectionsAssoc = $div["sections"];
        // أعِد بناء الأقسام كمصفوفة مرقّمة
        $result[$key]["sections"] = array_values($sectionsAssoc);
    }

    // تجهيز الاستجابة
    $response = [
        "status" => "success",
        "data"   => array_values($result)
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Exception $e) {
    // في حالة عدم وجود بيانات أو فشل الجلب
    printFailure();
}
