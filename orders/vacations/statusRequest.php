<?php
include "../../index.php";
$userId = filterRequest("userId");
getAllData('VW_SCREEN_PATHS_HMLEAVE', "PFNO='$userId'");
