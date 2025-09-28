<?php
include "../../index.php";
try {
    $data = json_decode(file_get_contents("php://input"), True);

    $title = $data['title'] ?? '';
    $body = $data['body'] ?? '';
    $admin_pfno = $data['admin_pfno'] ?? null;
    $targets = $data['targets'] ?? [];

    $postData = [
        "title" => $title,
        "body" => $body,
        "admin_pfno" => $admin_pfno,
    ];
    insertData("POST", $postData, null, null);
    $MAX_POST_NO = getDataBySelect("SELECT MAX(POST_NO) AS MAX_POST_NO FROM POST", NULL, NULL);
    $MAX = ($MAX_POST_NO['MAX_POST_NO'] ?? 0);
    foreach ($targets as $t) {
        $kind = $t['kind'];   // division | section | employee
        $key  = $t['key'];    // co_1_div_10_sec_3 .. الخ

        $companyNo = $t['companyNo'] ?? null;
        $divCode   = $t['divCode']   ?? null;
        $secCode   = $t['secCode']   ?? null;
        $pfno      = $t['pfno']      ?? null;

        $data = [
            "POST_NO" => $MAX,
            "COMPANY_NO" => $companyNo,
            "DIV_CODE" => $divCode,
            "SEC_CODE" => $secCode,
            "PFNO" => $pfno
        ];

        insertData("POST_TARGET", $data, null, null);

        sendNotification("$title", "$body", "$key", "../..");
    }
    printSuccess();
} catch (Exception $e) {
    // printFailure($e->getMessage());
    printFailure("هناك مشكله في التنفيذ اعدالمحاولة");
}
