<?php

include "../../index.php";

$pass = filterRequest("pass");
$user = filterRequest("user");


if (getCuont("MVEMPLOYEES", "PASSWORD IS NULL AND PFNO='$user'") > 0) {

    getData('MVEMPLOYEES', "PFNO='$user' AND EMPLOYEE_STATUS=1 AND EMP_TEL='$pass' AND PFNO IN ( SELECT MANAGER_CODE FROM MVEMPLOYEES  WHERE MANAGER_CODE ='$user')");
} else {
    getData('MVEMPLOYEES', "PFNO='$user' AND EMPLOYEE_STATUS=1 AND PASSWORD='$pass' AND PFNO IN ( SELECT MANAGER_CODE FROM MVEMPLOYEES  WHERE MANAGER_CODE ='$user')");
}




// {
//   "status": "success",
//   "data": {
//     "PFNO": "100766",
//     "EMP_NAME": "ميثاق عبده على مقبل",
//     "MANAGER_CODE": "100161",
//     "COMPANY_NO": "1",
//     "COMPANY_NAME": "الشركة اليمنية لصناعة الطلاء ومشنقاته",
//     "DIV_CODE": "1",
//     "DIV_NAME": "تعز",
//     "DEP_CODE": "4",
//     "DEP_NAME": "ادارة الموارد البشريه",
//     "SEC_CODE": "1",
//     "SEC_NAME": "الموارد البشريه",
//     "CC_CODE": "3",
//     "CC_NAME": "مصاريف صناعية غير مباشرة(إدارية)",
//     "BIRTH_DATE": "24-JUL-90",
//     "PLACE_OF_BIRTH": "تعز",
//     "GENDER": "1",
//     "GENDER_NAME": "ذكر",
//     "BLOOD": "5",
//     "NATY_ID": "1",
//     "NATY_NAME": "يمني",
//     "ADDRESS": null,
//     "COUNTRY": "Yemen",
//     "PROVINCE": "تعز",
//     "CITY": null,
//     "REGION": "التعزيه",
//     "STREET": null,
//     "P_O_BOX": null,
//     "CARD_TYPE": "1",
//     "CARD_NAME": "شخصية",
//     "CARD_POI": "تعز",
//     "CARD_DATE": "30-JUL-11",
//     "GEMAIL": null,
//     "PASSWORD": null,
//     "VERIFICATION_CODE": null,
//     "EMPL_TYPE": "1",
//     "EMPL_TYPE_NAME": "توظيف",
//     "SALARY_YR": "181400",
//     "SALARY_DR": null,
//     "BSALARY_PRC": "100",
//     "BSALARY_YR": "121000",
//     "BSALARY_DR": "0",
//     "GRADE_CODE": "7",
//     "POINT_CODE": "3",
//     "GRADE": "7.3",
//     "DOJ": "01-JUL-14",
//     "JOB_CODE": "117",
//     "JOB_TITLE": "اداري موارد بشرية",
//     "INCREMENT_DATE": null,
//     "MARITAL_STATUS": "2",
//     "MSTATUS_NAME": "متزوج",
//     "EMPLOYEE_STATUS": "1",
//     "EMPLOYEE_STATUS_DATE": "01-JUL-14",
//     "EMP_STATUS_NAME": "منتظم",
//     "DUTY_HOURS": "8",
//     "FREE_CHECKIN": "0",
//     "FREE_CHECKOUT": "0",
//     "REMARKS": null,
//     "EMP_TEL": "780266303",
//     "ACTIVE_EMP": "1",
//     "SUSPENDED": "0",
//     "RESIGNED": "0",
//     "FIRED": "0",
//     "RETIRED": "0",
//     "DECEASED": "0",
//     "CONTRACTOR": "0"
//   }
// }