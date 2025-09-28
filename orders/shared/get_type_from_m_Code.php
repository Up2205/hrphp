<?php

include "../../../index.php";
$MJ_Code = filterRequest("MJ_Code");

getAllData("MCODES", "MJ_CODE='$MJ_Code'");
