<?php

include("db.php");

//$userID = 1;
//$coinPackID = 60;
$userID = $_POST['userID'];
$coinPackID = $_POST['coinPackID'];
$idAndPackId = $userID . ' ' . $coinPackID;

$sql = mysqli_query($con, "SELECT value FROM syssetting WHERE sysDesc = 'ตั้งค่า Secret Key'");
//$sql = mysqli_query($con, "SELECT eventStart FROM student_class_home WHERE DATE(eventStart) >= '" . $date . "'");

$DataList = [];
$newArr = [];
$KeyListData = [];
$msg = "Can't Connection";

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		// echo $key . ' ' . $val;
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}
foreach ($newArr as $key => $val) {
	$KeyListData[$key]['key'] = $encrypted_string = openssl_encrypt($idAndPackId, "AES-128-ECB", $val['value']);
}

if (!empty($KeyListData)) {
	$msg = 'success';
}

echo json_encode(array('msg' => $msg, 'data' => $KeyListData));
