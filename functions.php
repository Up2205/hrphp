<?php

// ==========================================================
//  Copyright Reserved Wael Wael Abo Hamza (Course Ecommerce)
// ==========================================================

define("MB", 1048576);

function filterRequest($requestname)
{
    return  htmlspecialchars(strip_tags($_POST[$requestname]));
}

function getAllDataBySelect($query, $values = null, $josn = true)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("$query");
    $stmt->execute($values);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = count($data);
    if ($josn == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure", "data" => "none", "message" => "لا يوجد بيانات"));
        }
    } else {
        if ($count > 0) {

            return $data;
        } else {

            return false;
        }
    }
}
function getDataBySelect($query, $values = null, $josn = true)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("$query");
    $stmt->execute($values);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data != false) {
        $count  = count($data);
        if ($josn == true) {
            if ($count > 0) {
                echo json_encode(array("status" => "success", "data" => $data));
            } else {
                echo json_encode(array("status" => "failure", "data" => "none", "message" => "لا يوجد بيانات"));
            }
        } else {
            if ($count > 0) {

                return $data;
            } else {

                return false;
            }
        }
    } else {
        if ($josn == true) {
            echo json_encode(array("status" => "failure", "data" => "none", "message" => "لا يوجد بيانات"));
        } else {
            return false;
        }
    }
}
function callOracleFunctionOCI($packageName, $functionName, $params)
{
    $host = 'localhost';
    $sid = 'YCPD';
    $username = 'HUMAN_YCPD';
    $password = 'a';

    // إنشاء الاتصال
    $conn = oci_connect($username, $password, "$host/$sid");
    if (!$conn) {
        $e = oci_error();
        echo "Connection failed: " . $e['message'];
        exit;
    }

    $sql = "BEGIN :result := {$packageName}.{$functionName}(:param0, :param1, TO_DATE(:param2, 'YYYY-MM-DD')); END;";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":param0", $params[0]);
    oci_bind_by_name($stmt, ":param1", $params[1]);
    oci_bind_by_name($stmt, ":param2", $params[2]);

    $result = null;
    oci_bind_by_name($stmt, ':result', $result, 100);

    $r = oci_execute($stmt);
    if (!$r) {
        $e = oci_error($stmt);
        throw new Exception("Oracle Error: " . $e['message']);
    }
    oci_close($conn);
    return $result;
}
// function callOracleProcedureWithOut($packageName, $procedureName, $inParams = [])
// {
//     $host = 'localhost';
//     $sid = 'IT';
//     $username = 'YCPD';
//     $password = 'A';

//     $conn = oci_connect($username, $password, "$host/$sid");
//     if (!$conn) {
//         $e = oci_error();
//         throw new Exception("Connection failed: " . $e['message']);
//     }

//     // بناء placeholders للباراميترات
//     $placeholders = [];
//     foreach ($inParams as $i => $_) {
//         $placeholders[] = ":param{$i}";
//     }

//     // إضافة اثنين OUT للـ start date و end date
//     $placeholders[] = ":out_sdate";
//     $placeholders[] = ":out_edate";

//     $sql = "BEGIN {$packageName}.{$procedureName}(" . implode(", ", $placeholders) . "); END;";
//     $stmt = oci_parse($conn, $sql);

//     // ربط المدخلات
//     foreach ($inParams as $i => $val) {
//         oci_bind_by_name($stmt, ":param{$i}", $inParams[$i]);
//     }

//     // ربط المخرجات OUT
//     $sdate = null;
//     $edate = null;
//     oci_bind_by_name($stmt, ":out_sdate", $sdate, 100);
//     oci_bind_by_name($stmt, ":out_edate", $edate, 100);

//     if (!oci_execute($stmt)) {
//         $e = oci_error($stmt);
//         throw new Exception("Oracle Error: " . $e['message']);
//     }

//     oci_close($conn);

//     return [$sdate, $edate];
// }

function callOracleTableFunctionOCI($packageName, $functionName, $params = [])
{
    $host = 'localhost';
    $sid = 'IT';
    $username = 'YCPD';
    $password = 'A';

    // إنشاء الاتصال
    $conn = oci_connect($username, $password, "$host/$sid", 'AL32UTF8');
    if (!$conn) {
        $e = oci_error();
        throw new Exception("Connection failed: " . $e['message']);
    }

    // تجهيز placeholders للباراميترات
    $placeholders = [];
    foreach (array_keys($params) as $i => $k) {
        $placeholders[] = ":param{$i}";
    }
    $placeholdersStr = implode(", ", $placeholders);

    // SQL لاستخدام table function
    $sql = "SELECT * FROM TABLE({$packageName}.{$functionName}($placeholdersStr))";

    $stmt = oci_parse($conn, $sql);

    // ربط الباراميترات
    foreach ($params as $i => $value) {
        oci_bind_by_name($stmt, ":param{$i}", $params[$i]);
    }

    $r = oci_execute($stmt);
    if (!$r) {
        return [];
    }

    // جلب النتائج في مصفوفة
    $rows = [];
    while (($row = oci_fetch_assoc($stmt)) !== false) {
        $rows[] = $row;
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $rows;
}

function callOracleFunction($packageName, $functionName, $params = [])
{
    $host = 'localhost';
    $sid = 'IT';
    $username = 'YCPD';
    $password = 'A';

    $conn = oci_connect($username, $password, "$host/$sid");
    if (!$conn) {
        $e = oci_error();
        throw new Exception("Connection failed: " . $e['message']);
    }

    $paramPlaceholders = [];
    foreach ($params as $i => $value) {
        // إذا كانت القيمة تاريخ بصيغة YYYY-MM-DD نستخدم TO_DATE
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            $paramPlaceholders[] = "TO_DATE(:param{$i}, 'YYYY-MM-DD')";
        } else {
            $paramPlaceholders[] = ":param{$i}";
        }
    }

    $sql = "BEGIN :result := {$packageName}.{$functionName}(" . implode(', ', $paramPlaceholders) . "); END;";
    $stmt = oci_parse($conn, $sql);

    foreach ($params as $i => $value) {
        oci_bind_by_name($stmt, ":param{$i}", $params[$i]);
    }

    $result = null;
    oci_bind_by_name($stmt, ':result', $result, 100);

    if (!oci_execute($stmt)) {
        $e = oci_error($stmt);
        throw new Exception("Oracle Error: " . $e['message']);
    }

    oci_close($conn);
    return $result;
}




function getAllData($table, $where = null, $values = null, $josn = true)
{
    global $con;
    $data = array();
    if ($where == null) {
        $stmt = $con->prepare("SELECT  * FROM $table");
    } else {
        $stmt = $con->prepare("SELECT  * FROM $table WHERE $where ");
    }
    $stmt->execute($values);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // $count  = $stmt->rowCount();
    $count  = count($data);
    if ($josn == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure", "data" => "none", "message" => "لا يوجد بيانات"));
        }
    } else {
        if ($count > 0) {
            return  $data;
        } else {
        }
    }
}
function getData($table, $where = null, $values = null, $josn = true)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("SELECT  * FROM $table WHERE $where ");
    $stmt->execute($values);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    // $count  = count($data);
    if ($data != false) {
        if ($josn == true) {
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            return $data;
        }
    } else {
        if ($josn == true) {
            echo json_encode(array("status" => "failure", "message" => "لا يوجد بيانات"));
        } else {
            return 0;
        }
    }
}
// function getCuont($table, $where = null, $values = null)
// {
//     global $con;
//     $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
//     $stmt->execute($values);
//     $count  = $stmt->rowCount();
//     if ($count > 0) {
//         return $count;
//     } else {
//         return 0;
//     }
// }
function checkLeaveOverlap($pfno, $startDate, $endDate, $leaveCode)
{
    global $con;
    $sql = "
        SELECT 1 FROM HMLEAVE_ORDER
        WHERE PFNO = ?
        AND (
            (SDATE BETWEEN ? AND ?) OR
            (EDATE BETWEEN ? AND ?) OR
            (? BETWEEN SDATE AND EDATE) OR
            (? BETWEEN SDATE AND EDATE)
        ) AND CUR_STATUS !=2 AND LEAVE_CODE=?
    ";

    $stmt = $con->prepare($sql);
    $stmt->execute([
        $pfno,        // لـ PFNO
        $startDate,   // شرط 1: SDATE بين الفترة الجديدة
        $endDate,
        $startDate,   // شرط 2: EDATE بين الفترة الجديدة
        $endDate,
        $startDate,   // شرط 3: تاريخ البداية الجديد بين SDATE و EDATE
        $endDate,
        $leaveCode     // شرط 4: تاريخ النهاية الجديد بين SDATE و EDATE
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

function getCuont($table, $where = null, $values = null)
{
    global $con;
    $stmt = $con->prepare("SELECT  * FROM $table WHERE $where ");
    $stmt->execute($values);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data != false) {
        return count($data);
    } else {
        return 0;
    }
}


function insertData($table, $data, $json = true)
{
    global $con;

    // تصفية القيم "null" وتحويلها إلى NULL حقيقي
    foreach ($data as $key => $value) {
        if ($value === "null" || $value === null) {
            $data[$key] = null;
        }
    }

    $ins = [];
    foreach ($data as $field => $v)
        $ins[] = ':' . $field;

    $ins = implode(',', $ins);
    $fields = implode(',', array_keys($data));
    $sql = "INSERT INTO $table ($fields) VALUES ($ins)";

    $stmt = $con->prepare($sql);

    foreach ($data as $f => $v) {
        $stmt->bindValue(':' . $f, $v, is_null($v) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    }

    $stmt->execute();
    $count = $stmt->rowCount();

    if ($json == true) {
        echo json_encode(["status" => $count > 0 ? "success" : "failure"]);
    }
}


// function insertData($table, $data, $json = true)
// {
//     global $con;

//     foreach ($data as $field => $v) {
//         if (strpos(strtoupper($field), 'DATE') !== false) {
//             // إذا كان الحقل يحتوي على تاريخ، استخدم دالة TO_DATE لتنسيق التاريخ
//             $ins[] = "TO_DATE(:$field, 'YYYY-MM-DD')";
//         } else {
//             // باقي الحقول العادية
//             $ins[] = ':' . $field;
//         }
//     }
//     // دمج الحقول والقيم في استعلام SQL
//     $ins = implode(',', $ins);
//     $fields = implode(',', array_keys($data));
//     $sql = "INSERT INTO $table ($fields) VALUES ($ins)";

//     $stmt = $con->prepare($sql);
//     foreach ($data as $f => $v) {
//         // إذا كان الحقل يحتوي على تاريخ، استخدم نفس القيمة، لكن تنسيق التاريخ يجب أن يتوافق مع قاعدة البيانات
//         $stmt->bindValue(':' . $f, $v);
//     }
//     $stmt->execute();
//     $count = $stmt->rowCount();
//     if ($json == true) {
//         if ($count > 0) {
//             echo json_encode(array("status" => "success"));
//         } else {
//             echo json_encode(array("status" => "failure"));
//         }
//     } else {
//         return $count;
//     }
// }


function updateData($table, $data, $where, $json = true)
{
    global $con;
    $cols = array();
    $vals = array();

    foreach ($data as $key => $val) {
        $vals[] = "$val";
        $cols[] = "$key =  ? ";
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";
    $stmt = $con->prepare($sql);
    $stmt->execute($vals);
    $count  = count($data);
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure", "message" => "لم يتم التحديث"));
        }
    }
    // return $count;
}

function deleteData($table, $where, $json = true)
{
    global $con;
    $stmt = $con->prepare("DELETE FROM $table WHERE $where");
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}

function imageUpload($dir, $imageRequest)
{
    global $msgError;
    $imagename  = rand(10, 10000) . $_FILES[$imageRequest]['name'];
    $imagetmp   = $_FILES[$imageRequest]['tmp_name'];
    $imagesize  = $_FILES[$imageRequest]['size'];
    $allowExt   = array("jpg", "png", "gif", "mp3", "pdf");
    $strToArray = explode(".", $imagename);
    $ext        = end($strToArray);
    $ext        = strtolower($ext);

    if (!empty($imagename) && !in_array($ext, $allowExt)) {
        $msgError = "Exit";
    }
    if ($imagesize > 2 * MB) {
        $msgError = "size select a file less than 2 MB";
    }
    if (empty($msgError)) {
        move_uploaded_file($imagetmp,  "$dir/" . $imagename);
        return $imagename;
    } else {
        return "fail";
    }
}


function deleteFile($dir, $imagename)
{
    if (file_exists($dir . "/" . $imagename)) {
        unlink($dir . "/" . $imagename);
    }
}

function checkAuthenticate()
{
    if (isset($_SERVER['PHP_AUTH_USER'])  && isset($_SERVER['PHP_AUTH_PW'])) {
        if ($_SERVER['PHP_AUTH_USER'] != "vai" ||  $_SERVER['PHP_AUTH_PW'] != "vai1234") {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Page Not Found';
            exit;
        }
    } else {
        exit;
    }
}

function printFailure($message = "none")
{
    echo json_encode(array("status" => "failure", "message" => $message));
}
function printSuccess($message = "none", $data = [])
{
    echo json_encode(array("status" => "success", "message" => $message, "data" => $data));
}

function resulte($count)
{
    if ($count > 0)
        printSuccess();
    else
        printFailure();
}

function sendEmail($to, $title, $body)
{
    $headers = "From: YCPD support@gmail.com\n";
    mail($to, $title, $body, $headers);
}

require 'vendor/autoload.php';

use \Firebase\JWT\JWT;

function getAccessToken($accessToken)
{
    $now = time();
    $serviceAccount = json_decode(file_get_contents($accessToken), true);

    $payload = [
        'iss' => $serviceAccount['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud' => 'https://oauth2.googleapis.com/token',
        'iat' => $now,
        'exp' => $now + 3600,
    ];

    $jwt = JWT::encode($payload, $serviceAccount['private_key'], 'RS256');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt,
    ]));
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    return $data['access_token'];
}


function sendNotification($title, $body, $topic, $accessToken = './serviceAccountKey.json')
{
    $url = "https://fcm.googleapis.com/v1/projects/hrycpd/messages:send";
    $message = [
        'message' => [
            'topic' => $topic,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => [
                'key1' => 'value1',
                'key2' => 'value2'
            ]
        ]
    ];
    $Token = getAccessToken($accessToken . '/serviceAccountKey.json');
    $headers = [
        "Authorization: Bearer {$Token}",
        'Content-Type: application/json; charset=UTF-8'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
// function senNotifAndInser($title, $body, $topic, $userId, $accessToken = '../serviceAccountKey.json')
// {
//     $datainsert = [
//         'notification_title' => $title,
//         'notification_body' => $body,
//         'notification_userid' => $userId,
//     ];
//     insertData('notification', $datainsert, false);
//     sendNotification($title, $body, $topic, $accessToken,);
// }

use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php'; // أو المسار المناسب

function sendEmail2($to,  $code)
{

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'badsbat80@gmail.com';
    $mail->Password = 'pfyqyndmpmotyreo'; // ليس كلمة المرور، بل App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your-email@gmail.com', 'HR System');
    $mail->addAddress($to);

    $mail->Subject = 'verify code';
    $mail->Body    = "كود التحقق هو  : $code";
    $mail->send();
}
