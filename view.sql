/*https://github.com/waelabouhamza/ecommercecourse*/


CREATE OR REPLACE VIEW  leaveRequestsView AS
SELECT vacation_request.* , users.* FROM vacation_request 
INNER JOIN  users on  users.users_id = vacation_request.vacation_request_userid 
GROUP BY vacation_request.vacation_request_id,vacation_request.vacation_request_userid


CREATE OR REPLACE VIEW  leaveRequestsView AS
SELECT HMLEAVE_ORDER.* , EMPLOYEE.* FROM HMLEAVE_ORDER 
INNER JOIN  EMPLOYEE on  EMPLOYEE.PFNO = HMLEAVE_ORDER.PFNO 

CREATE OR REPLACE VIEW vw_leave_details_expanded AS
SELECT
  CUR_YEAR,
  ORDER_NO       AS LEAVE_NO,
  PFNO,
  CUR_DATE,
  LEAVE_CODE,
  SDATE,
  LDAYS,
  LHH,
  LMI,
  MEMO AS MMEMO,
  EDATE,
  SENDTO,
  MPFNO,
  AGREED,
  AGREED_DATE,
  WORKER,
  CUR_STATUS,
  EMPLOYEE_NAME  AS USER_NAME,
  NULL           AS LEAVE_MOD,
  NULL           AS HAS_DOCMTS,
  LIN_MINUTS,
  MONTH_NO,
  RDATE,
  SPFNO,
  NULL           AS GLEAVE,
  NULL           AS LATEIN_NO
FROM vw_leave_details WHERE CUR_STATUS=1;




CREATE OR REPLACE VIEW VW_LEAVE_DETAILS
 AS
    SELECT H.*,
          E.EMP_NAME AS EMPLOYEE_NAME,
          E.JOB_TITLE,
          E.DEP_CODE,
          E.DEP_NAME,
          L.SHURT_NAME AS LEAVE_TypeName  
     FROM HMLEAVE_ORDER H
          LEFT JOIN EMPLOYEE E ON H.PFNO = E.PFNO
          LEFT JOIN LEAVE_TYPE L ON H.LEAVE_CODE = L.LEAVE_CODE



 
 



 

 