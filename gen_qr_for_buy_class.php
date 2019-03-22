<?php
include("db.php");

//$eventID = 98;
//$userID = 27;
$eventID = $_POST['eventID'];
$userID = $_POST['userID'];
$userName = '';
$eventName = '';
$coin = '';
$coinValue = '';
$baht = '';

$coinSql = mysqli_query($con, "SELECT value FROM syssetting WHERE sysDesc = 'CoinBase'");
while ($arr = mysqli_fetch_array($coinSql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$coinValue = $val;
	}
}

$userSql = mysqli_query($con, "SELECT User FROM user WHERE UserID = '" . $userID . "'");
while ($arr = mysqli_fetch_array($userSql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$userName = $val;
	}
}

$eventDataList = [];
$eventArr = [];
$eventSql = mysqli_query($con, "SELECT eventTitle, coin FROM student_course_class_activity WHERE eventID = '" . $eventID . "'");
$DataList = [];
$newArr = [];
$EventListData = [];

while ($arr = mysqli_fetch_array($eventSql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		// echo $key . ' ' . $val;
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}
foreach($newArr as $key => $val){
	$eventName = $val['eventTitle'];
	$coin = $val['coin'];

}
$baht = $coin * $coinValue;

if(!empty($userName) && !empty($userID) && !empty($eventID) && !empty($eventName) && !empty($coin) && !empty($baht)){
	$msg = 'success';
}else{
	$msg = "Something wrong happened! Please try again!";
}


echo json_encode(array('msg' => $msg, 'userName' => $userName, 'userID' => $userID, 'eventID' => $eventID, 'eventName' => $eventName, 'coin' => $coin, 'baht' => $baht));

