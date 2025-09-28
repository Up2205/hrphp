<?php

include '../../index.php';



try {

    $data = json_decode(file_get_contents("php://input"), True);
    $MAX_REF_NO = getDataBySelect("SELECT MAX(REF_NO) AS MAX_REF_NO FROM EXITPERMSN_ORDER", NULL, NULL);
    $MAX = ($MAX_REF_NO['MAX_REF_NO'] ?? 0) + 1;
    $result = [
        'REF_YEAR'    => $data['REF_YEAR'],
        'REF_DATE'    => $data['REF_DATE'],
        'REF_NO'    => $MAX,
        'PFNO'        => $data['PFNO'],
        'CUR_DATE'    => $data['CUR_DATE'],
        'CUR_YEAR'    => $data['CUR_YEAR'],
        'MONTH_NO'    => $data['MONTH_NO'],
        'SHH'         => $data['SHH'],
        'SMI'         => $data['SMI'],
        'EHH'         => $data['EHH'],
        'EMI'         => $data['EMI'],
        'EIN_MINUTS'  => $data['EIN_MINUTS'],
        'MPFNO'       => $data['MPFNO'],
        'LEAVE_CODE'  => $data['LEAVE_CODE'],
        'AGREED'      => $data['AGREED'],
        'CUR_STATUS'  => $data['CUR_STATUS'],
        'AGREED_date' => $data['AGREED_DATE'],
        'REF_TYPE' => $data['LEAVE_CODE'],
        'DIV_CODE' => $data['DIV_CODE']
    ];

    // تحقق إذا كان هناك طلب بنفس الشهر ونفس اليوم
    $pfno = $data['PFNO'] ?? null;
    $refDate = $data['REF_DATE'] ?? null;
    $exists = getCuont(
        "EXITPERMSN_ORDER",
        "REF_DATE ='$refDate' AND PFNO = '$pfno'"
    );
    if ($exists > 0) {
        printFailure("يوجد طلب مسبق لنفس الشهر واليوم.");
        exit;
    }
    insertData('EXITPERMSN_ORDER', $result);
} catch (ErrorException $e) {
    printFailure("فشل طلبك: " . $e->getMessage());
}
