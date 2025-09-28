<?php
include '../../index.php';



try {

    $data = json_decode(file_get_contents("php://input"), True);
    $refYear      = $data['REF_YEAR'] ?? null;
    $refDate      = $data['REF_DATE'] ?? null;
    $pfno         = $data['PFNO'] ?? null;
    $overtimeType = $data['OVERTIME_TYPE'] ?? null;
    $monthNo      = $data['MONTH_NO'] ?? null;
    $shh          = $data['SHH'] ?? null;
    $ehh          = $data['EHH'] ?? null;
    $whh          = $data['WHH'] ?? null;
    $mpfno        = $data['MPFNO'] ?? null;
    $memo         = $data['MEMO'] ?? null;
    // $agreed       = $data['AGREED'] ?? null;
    // $agreedDate   = $data['AGREED_DATE'] ?? null;
    // $sideMandate  = $data['SIDE_MANDATE'] ?? null;
    $curStatus    = $data['CUR_STATUS'] ?? null;
    $divCode      = $data['DIV_CODE'] ?? null;
    $MAX_REF_NO = getDataBySelect("SELECT MAX(REF_NO) AS MAX_REF_NO FROM OVERTIME_ORDER", NULL, NULL);
    $MAX = ($MAX_REF_NO['MAX_REF_NO'] ?? 0) + 1;
    $data = [
        'REF_YEAR'      => $refYear,
        'REF_NO'        => $MAX,
        'REF_DATE'      => $refDate,
        'PFNO'          => $pfno,
        'OVERTIME_TYPE' => $overtimeType,
        'MONTH_NO'      => $monthNo,
        'SHH'           => $shh,
        'EHH'           => $ehh,
        'WHH'           => $whh,
        'MPFNO'         => $mpfno,
        'MEMO'          => $memo,
        // 'AGREED'        => $agreed,
        // 'AGREED_DATE'   => $agreedDate,
        // 'SIDE_MANDATE'  => $sideMandate,
        'CUR_STATUS'    => $curStatus,
        'DIV_CODE'      => $divCode
    ];
    // تحقق إذا كان هناك طلب بنفس الشهر ونفس اليوم
    $exists = getCuont(
        "OVERTIME_ORDER",
        "REF_DATE ='$refDate' AND PFNO = '$pfno'"
    );
    if ($exists > 0) {
        printFailure("يوجد طلب مسبق لنفس الشهر واليوم.");
        exit;
    }

    insertData('OVERTIME_ORDER', $data);
} catch (ErrorException $e) {
    printFailure("فشل طلبك: " . $e->getMessage());
}
