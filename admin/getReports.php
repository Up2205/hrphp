<?php
include '../index.php';
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

if ($start_date && $end_date) {
    $query = "SELECT 
                  EMPLOYEE_NAME, 
                  CUR_STATUS, 
                  DEP_NAME, 
                  SUM(LDAYS) AS total_days
              FROM 
                  vw_leave_details
              WHERE 
                  SDATE >= ? 
                  AND EDATE <= ?
              GROUP BY 
                  EMPLOYEE_NAME, DEP_NAME,CUR_STATUS";
    getAllDataBySelect($query, array($start_date, $end_date));
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid date range']);
}
