<?php
include("db.php");

$eventID = $_POST['eventID'];
$countChecked = '';
$countRegis = '';

$countCheckSql = mysqli_query($con,"SELECT numCheckedStudent as countChecked, countRegis FROM admin_class_checked_activity WHERE eventID = '" . $eventID . "'");

while ($arr = mysqli_fetch_array($countCheckSql, MYSQLI_ASSOC)) {
	$countChecked = $arr['countChecked'].'/'.$arr['countRegis'];
}

$selectStuCheckedSql = mysqli_query($con,"SELECT User FROM student_check_status WHERE eventID = '" . $eventID . "' AND Active = 0");
$DataList = [];
$newArr = [];
$StudentListData = [];
$date = date('Y-m-d H:i:s');

while ($arr = mysqli_fetch_array($selectStuCheckedSql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$DataList[$key] = $val;
	}
	array_push($StudentListData, $DataList);
}
if(empty($countChecked)){
	$msg = "Something wrong happened! Please try again!";
}else if(!empty($countChecked) && empty($StudentListData)){
	$msg = "empty";
}else if(!empty($StudentListData)){
	$msg = "success";
}else{
	$msg = "Something wrong happened! Please try again!";
}
//print_pre($StudentListData);

echo json_encode(array('msg' => $msg, 'student' => $StudentListData, 'countChecked' => $countChecked));
