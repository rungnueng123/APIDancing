<?php
include("db.php");

$userID = $_POST['userID'];
$eventID = $_POST['eventID'];
$userName = '';
$className = '';

$sql = mysqli_query($con,"SELECT User FROM user WHERE UserID = '" . $userID . "'");
while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	$userName = $arr['User'];
}

$selectClassSql = mysqli_query($con, "SELECT eventTitle FROM student_show_course_paid WHERE eventID = '" . $eventID . "'");
while ($arr = mysqli_fetch_array($selectClassSql, MYSQLI_ASSOC)) {
	$className = $arr['eventTitle'];
}

if(!empty($userName)){
	$msg = 'success';
}else{
	$msg = 'fail';
}

echo json_encode(array('msg' => $msg, 'userName' => $userName, 'className' => $className));