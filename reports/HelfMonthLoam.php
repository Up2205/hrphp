<?php
include "../index.php";

$userId = filterRequest("userId");
$startDate = filterRequest("startDate");
$endDate = filterRequest("endDate");

getAllDataBySelect("SELECT 
E.DIV_CODE ,
E.DIV_NAME ,
E.DEP_CODE ,
E.DEP_NAME ,
E.SEC_CODE ,
E.SEC_NAME ,
E.JOB_TITLE ,
H.PFNO ,
E.EMP_NAME ,
H.SALARY_YR ,
E.SALARY_DR ,
H.LOAN_CODE ,
H.LOAN_DATE ,
H.LOAN_3AMT ,
H.BUFFET ,
H.LOAN_AMOUNT ,
H.PERCNT ,
H.MEMO
FROM EMP_LOAN H,VEMP_PROFILE E
WHERE E.COMPANY_NO = 1
AND H.PFNO = E.PFNO
AND H.LOAN_DATE BETWEEN '$startDate' AND '$endDate' AND H.PFNO='$userId'
ORDER BY E.DIV_CODE ,
E.DEP_CODE 
 ");
