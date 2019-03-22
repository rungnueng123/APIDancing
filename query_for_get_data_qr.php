<?php

include("db.php");

$idAndPackIdSecret = $_POST['secretKey'];
$msg = "Can't Connection";
$userID = '';
$coinPackID = '';
$user = '';
$coinPackName = '';

$sql = mysqli_query($con, "SELECT value FROM syssetting WHERE sysDesc = 'ตั้งค่า Secret Key'");
//$sql = mysqli_query($con, "SELECT eventStart FROM student_class_home WHERE DATE(eventStart) >= '" . $date . "'");

$DataList = [];
$newArr = [];
$ArrID = [];
$key = '';
$msg = "Connection Fail";

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		// echo $key . ' ' . $val;
		$key = $val;
	}
}

$idAndPackId = openssl_decrypt($idAndPackIdSecret, "AES-128-ECB", $key);
//$idAndPackId = '1 1';
$ArrID = explode(" ", $idAndPackId);
$userID = $ArrID[0];
$coinPackID = $ArrID[1];

$userSql = mysqli_query($con, "SELECT User FROM user WHERE UserID = '" . $userID . "'");
while ($arr = mysqli_fetch_array($userSql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$user = $val;
	}
}

$packNameSql = mysqli_query($con, "SELECT namepack FROM coinpack WHERE id = '" . $coinPackID . "'");
while ($arr = mysqli_fetch_array($packNameSql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$coinPackName = $val;
	}
}

if (!empty($user) && !empty($coinPackName)){
	$msg = 'success';
}

echo json_encode(array('msg' => $msg, 'user' => $user, 'coinPackName' => $coinPackName, 'userID' => $userID, 'coinPackID' => $coinPackID));