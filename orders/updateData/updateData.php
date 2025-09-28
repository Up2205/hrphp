<?php
include "../../index.php";

// $MJ_Code = filterRequest("MJ_Code");

$data = array();
try {
    $data["mcode"] = array_merge(
        getAllData("MCODES", "MJ_CODE='2'", null, null, null),
        getAllData("MCODES", "MJ_CODE='3'", null, null, null),
        getAllData("MCODES", "MJ_CODE='50'", null, null, null)
    );
    $data['division'] = getAllData("division", "COMPANY_NO='1' ORDER BY DIV_NAME", null, null, null);
    $data['department'] = getAllData("department", "COMPANY_NO='1' ORDER BY DEP_NAME", null, null, null);
    $data['section'] = getAllData("section", "COMPANY_NO='1'  ORDER BY SEC_NAME", null, null, null);
    $data['jobs'] = getAllData("job_title", "1=1 ORDER BY JOB_TITLE", null, null, null);
    $data['managers'] = getAllDataBySelect("SELECT mep.emp_name, mep.pfno
                                        FROM mvemployees mep
                                        WHERE mep.pfno IN  (
                                        SELECT x.manager_code
                                        FROM mvemployees x
                                        WHERE x.manager_code IS NOT NULL
                                        )
                                        ORDER BY mep.EMP_NAME", NULL, NULL);
    echo json_encode(array("status" => "success", "data" => $data));
} catch (Exception $e) {
    printFailure();
}
