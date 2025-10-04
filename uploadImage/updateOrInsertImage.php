<?php

include "../index.php";

$pfNo = filterRequest("pfNo");

$imageName = filterRequest("imageName");
if (isset($_FILES['file'])) {
    if ($imageName != '')
        deleteFile('../upload', $imageName);
    $imageName = imageUpload("../upload", "file", $pfNo);
    // echo $imageName;
}
$Data = [

    'IMAGE_URL' => $imageName,
];
if ($imageName != 'fail') {
    updateData('USERS_ACCOUNTS', $Data, "pfNO='$pfNo'", null, null);
    echo json_encode(array("status" => "success", "nameImage" => $imageName));
} else {
    printFailure('فشل تغيير صورة الخلفية');
}
