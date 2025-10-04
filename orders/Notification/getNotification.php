<?php
include "../../index.php";


$pfNo = filterRequest("pfNo");
$divCode = filterRequest("divCode");
$secCode = filterRequest("secCode");

getAllDataBySelect("SELECT 
    TITLE,
    BODY,
    ADMIN_PFNO,
    CRT_DATE,
    CASE
        WHEN PFNO IS NOT NULL 
             AND DIV_CODE IS NULL 
             AND SEC_CODE IS NULL
        THEN 'شخصي'
        WHEN DIV_CODE IS NOT NULL 
             AND SEC_CODE IS NULL 
             AND PFNO IS NULL
        THEN 'فرع'
        WHEN DIV_CODE IS NOT NULL 
             AND SEC_CODE IS NOT NULL 
             AND PFNO IS NULL
        THEN 'قسم'
        ELSE 'UNKNOWN'
    END AS TARGET_TYPE
FROM VW_POST_TARGET
WHERE 
      (DIV_CODE = '$divCode' AND SEC_CODE IS NULL AND PFNO IS NULL)  
   OR (DIV_CODE = '$divCode' AND SEC_CODE = '$secCode' AND PFNO IS NULL)   
   OR (PFNO = '$pfNo' AND DIV_CODE IS NULL AND SEC_CODE IS NULL) 
");




// getData('MVEMPLOYEES', "PFNO='$pfNo' AND EMPLOYEE_STATUS=1 AND PASSWORD='$pass'");