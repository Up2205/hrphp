<?php
include "../../index.php";

$email = filterRequest("email");
$verfiycode = filterRequest("verfiycode");
$count = getCuont("EMPLOYEE ", "GEMAIL ='$email' AND VERIFICATION_CODE='$verfiycode'");
resulte($count);
