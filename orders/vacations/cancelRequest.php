<?php
include "../../index.php";
$requestId = filterRequest("requestId");
deleteData('HMLEAVE_ORDER', "ORDER_NO='$requestId'");
