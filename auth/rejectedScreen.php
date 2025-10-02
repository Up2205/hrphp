<?php

include "../index.php";

$pfNo = filterRequest("pfNo");


getAllData("APP_REFERENCE", "PFNO='$pfNo'");
