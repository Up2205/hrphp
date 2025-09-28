<?php
include "../index.php";


$dataRequest = json_decode(file_get_contents("php://input"), true);

$oRDERNO = $dataRequest['ORDER_NO'];
$cURSTATUS = $dataRequest['CUR_STATUS'];
$pFNO = $dataRequest['PFNO'];





$data = [
    'CUR_STATUS' => $cURSTATUS,
];
updateData("HMLEAVE_ORDER", $data, "ORDER_NO ='$oRDERNO'");
if ($cURSTATUS == 1) {
    $cURYEAR = $dataRequest['CUR_YEAR'];
    $mONTHNO = $dataRequest['MONTH_NO'];
    $oRDERNO = $dataRequest['ORDER_NO'];
    $cLOUDID = $dataRequest['CLOUD_ID'];
    $pFNO = $dataRequest['PFNO'];
    $eMPLOYEENAME = $dataRequest['EMPLOYEE_NAME'];
    $cURDATE = $dataRequest['CUR_DATE'];
    $lEAVECODE = $dataRequest['LEAVE_CODE'];
    $lEAVETYPENAME = $dataRequest['LEAVE_TYPENAME'];
    $sDATE = $dataRequest['SDATE'];
    $lDAYS = $dataRequest['LDAYS'];
    $lHH = $dataRequest['LHH'];
    $lMI = $dataRequest['LMI'];
    $eDATE = $dataRequest['EDATE'];
    $lINMINUTS = $dataRequest['LIN_MINUTS'];
    $rDATE = $dataRequest['RDATE'];
    $sENDTO = $dataRequest['SENDTO'];
    $mPFNO = $dataRequest['MPFNO'];
    $sPFNO = $dataRequest['SPFNO'];
    $aGREED = $dataRequest['AGREED'];
    $aGREEDDATE = $dataRequest['AGREED_DATE'];
    $wORKER = $dataRequest['WORKER'];
    $mEMO = $dataRequest['MEMO'];
    $cURSTATUS = $dataRequest['CUR_STATUS'];
    $JobTITLE = $dataRequest['JOB_TITLE'];
    $DepName = $dataRequest['DEP_NAME'];
    $DepCode = $dataRequest['DEP_CODE'];

    $MAX_LEAVE_NO = getDataBySelect("SELECT MAX(LEAVE_NO) AS MAX_LEAVE_NO
  FROM EMP_LEAVES
", NULL, NULL);
    $MAX = ($MAX_LEAVE_NO['MAX_LEAVE_NO'] ?? 0) + 1;
    $DATE = [
        "CUR_YEAR" => $cURYEAR,
        "LEAVE_NO" => $MAX,
        "PFNO" => $pFNO,
        "CUR_DATE" => $cURDATE,
        "LEAVE_CODE" => $lEAVECODE,
        "LDAYS" => $lDAYS,
        "LHH" => $lHH,
        "LMI" => $lMI,
        "LIN_MINUTS" => $lINMINUTS,
        "MEMO" => $mEMO,
        "SDATE" => $sDATE,
        "EDATE" => $eDATE,
        "RDATE" => $rDATE,
        "SENDTO" => $sENDTO,
        "MPFNO" => $mPFNO,
        "AGREED" => $aGREED,
        "AGREED_DATE" => $aGREEDDATE,
        "WORKER" => $wORKER,
        "MMEMO" => " ",
        "CUR_STATUS" => $cURSTATUS,
        "USER_NAME" => $eMPLOYEENAME,
        // "LEAVE_MOD"=> ,  
        // "HAS_DOCMTS"=> , 
        "MONTH_NO" => $mONTHNO,
        "SPFNO" => $sPFNO,
        // "GLEAVE"=> ,     
        // "LATEIN_NO"=>$
    ];
    insertData("EMP_LEAVES", $DATE);
    sendNotification("طلب اجازة", "تم قبول طلبك", "user_$pFNO", "..");
} else if ($value == 2) {
    sendNotification("طلب اجازة", "تم رفض طلبك", "user_$pFNO", "..");
}

