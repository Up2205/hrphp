<?php
include "../../index.php";

$email = filterRequest("email");
$verfiycode = rand(10000, 99999);
$count = getCuont("EMPLOYEE", "GEMAIL ='$email'");

if ($count > 0) {
    try {

        // send verification code to user email
        sendEmail2($email, $verfiycode);
        $data = array('VERIFICATION_CODE' => $verfiycode);
        updateData('EMPLOYEE', $data, "GEMAIL='$email'");
    } catch (ErrorException $e) {
        printFailure();
    }
} else {
    printFailure("هذا الايميل ليس موجود");
}
