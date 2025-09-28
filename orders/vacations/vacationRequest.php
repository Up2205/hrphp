<?php
include '../../index.php';
$data = json_decode(file_get_contents("php://input"), True);
$order = $data[0];
$result = callOracleFunction('HM_PKG', 'Get_Emp_Leave_Balance_AsDate', [$order['PFNO'], $order['LEAVE_CODE'], $order['CUR_DATE']]);
$orderT = 0;
$orderF = 0;
foreach ($data as $request) {
    try {
        $vacation_request_begin            = $request['SDATE'] ?? null;
        $vacation_request_end              = $request['EDATE'] ?? null;
        $vacation_request_type             = ($request['LEAVE_CODE'] === "null") ? null : $request['LEAVE_CODE'];
        $vacation_request_status           = $request['CUR_STATUS'] ?? null;
        $vacation_request_text             = $request['MEMO'] ?? null;
        $vacation_request_userid           = $request['PFNO'] ?? null;
        $vacation_request_year             = $request['CUR_YEAR'] ?? null;
        $vacation_request_month            = $request['MONTH_NO'] ?? null;
        $vacation_request_ReturnDate       = $request['RDATE'] ?? null;
        $vacation_request_days             = $request['LDAYS'] ?? 0;
        $vacation_request_hour             = $request['LHH'] ?? 0;
        $vacation_request_minute           = $request['LMI'] ?? 0;
        $vacation_request_SubstituteEmployee = ($request['SPFNO'] === "null") ? null : $request['SPFNO'];
        $vacation_request_TotalVacation    = $request['LIN_MINUTS'] ?? 0;
        $vacation_request_MPFNO            = $request['MPFNO'] ?? null;
        $vacation_request_SENDTO           = $request['SENDTO'] ?? null;
        $vacation_request_CUR_DATE          = $request['CUR_DATE'] ?? null;
        $vacation_request_DIV_CODE         = $request['DIV_CODE'] ?? null;
        if (getCuont('HMONTH_PERIOD', "'$vacation_request_begin' >= SDATE
              AND '$vacation_request_end' <= EDATE AND CUR_YEAR='$vacation_request_year'
             ") == 0) {
            $orderF++;
            printFailure(["orderT" => $orderT, "orderF" => $orderF, "result" => $result, 'note' => "الإجازة خارج الفترة المسموح بها"]);
            return;
        }
        if (checkLeaveOverlap($vacation_request_userid, $vacation_request_begin, $vacation_request_end, $vacation_request_type)) {
            $orderF++;
            printFailure(["orderT" => $orderT, "orderF" => $orderF, "result" => $result, 'note' => "يوجد اجازة متداخله"]);
            return;
        }

        $MAX_ORDER_NO = getDataBySelect("SELECT MAX(ORDER_NO) AS MAX_ORDER_NO FROM HMLEAVE_ORDER", NULL, NULL);
        $MAX = ($MAX_ORDER_NO['MAX_ORDER_NO'] ?? 0) + 1;
        $data = [
            'ORDER_NO'    => $MAX,
            'SDATE'       => $vacation_request_begin,
            'EDATE'       => $vacation_request_end,
            'LEAVE_CODE'  => $vacation_request_type,
            'CUR_STATUS'  => $vacation_request_status,
            'MEMO'        => $vacation_request_text,
            'PFNO'        => $vacation_request_userid,
            'CUR_YEAR'    => $vacation_request_year,
            'MONTH_NO'    => $vacation_request_month,
            'RDATE'       => $vacation_request_ReturnDate,
            'LDAYS'       => $vacation_request_days,
            'LHH'         => $vacation_request_hour,
            'LMI'         => $vacation_request_minute,
            'SPFNO'       => $vacation_request_SubstituteEmployee,
            'LIN_MINUTS'  => $vacation_request_TotalVacation,
            'MPFNO'       => $vacation_request_MPFNO,
            'SENDTO'      => $vacation_request_SENDTO,
            'CUR_DATE'    => $vacation_request_CUR_DATE,
            'DIV_CODE'    => $vacation_request_DIV_CODE,

        ];
        if ($result >= $vacation_request_TotalVacation) {
            insertData('HMLEAVE_ORDER', $data, false);
            $result = $result - $vacation_request_TotalVacation;
            $orderT++;
            printSuccess(["orderT" => $orderT, "orderF" => $orderF, "result" => $result, 'note' => "تم قبول طلبك الرجاء الانتظار حتى الرد"]);
        } else {
            if (callOracleFunction('HM_PKG', 'Get_Emp_Leave_Permitted', [$vacation_request_userid, $vacation_request_type, $vacation_request_begin, $vacation_request_end]) > 0) {
                insertData("HMLEAVE_ORDER", $data, false);
                $result = $result - $vacation_request_TotalVacation;
                $orderT++;
                printSuccess(["orderT" => $orderT, "orderF" => $orderF, "result" => $result, 'note' => "تم قبول طلبك الرجاء الانتظار حتى الرد"]);
            } else {
                $orderF++;
                printFailure(["orderT" => $orderT, "orderF" => $orderF, "result" => $result, 'note' => "رصيدك غير كافي"]);
            }
        }
    } catch (ErrorException $e) {
        printFailure(["orderT" => $orderT, "orderF" => $orderF, "result" => $result, 'note' => "$e"]);
    }
}
