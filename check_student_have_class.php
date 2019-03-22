<?php

include("db.php");

//$userID = 27;
//$eventID = 101;
$userID = $_POST['userID'];
$eventID = $_POST['eventID'];
$active = '';
$regisID = '';
$userName = '';
$className = '';

$selectUserNameSql = mysqli_query($con, "SELECT User FROM user WHERE UserID = '" . $userID . "'");
while ($arr = mysqli_fetch_array($selectUserNameSql, MYSQLI_ASSOC)) {
	$userName = $arr['User'];
}

$checkPaidSql = mysqli_query($con, "SELECT Active, eventTitle FROM student_show_course_paid
									WHERE UserID = '" . $userID . "' AND eventID = '" . $eventID . "'");

while ($arr = mysqli_fetch_array($checkPaidSql, MYSQLI_ASSOC)) {
	$active = $arr['Active'];
	$className = $arr['eventTitle'];
}

if ($active == '') {
	$msg = 'cant regis';
} else if ($active == 0) {
	$msg = 'check already';
} else if ($active == 1) {
	$selectRegisID = mysqli_query($con, "SELECT RegisID FROM registration WHERE user_UserID = '" . $userID . "' LIMIT 1");
	$DataList = [];
	$newArr = [];
	while ($arr = mysqli_fetch_array($selectRegisID, MYSQLI_ASSOC)) {
		$regisID = $arr['RegisID'];
	}
	if (!empty($regisID)) {
		$updateStatus = "UPDATE studentsched SET Active = 0 WHERE registration_RegisID = '" . $regisID . "' AND eventID = '" . $eventID . "' AND Active = 1";

		if ($con->query($updateStatus)) {
			$msg = 'success';
		} else {
			$msg = 'fail';
		}
	} else {
		$msg = 'fail';
	}
} else {
	$msg = 'fail';
}

//$output['msg'] = $msg;
echo json_encode(array('msg' => $msg, 'userName' => $userName, 'className' => $className));