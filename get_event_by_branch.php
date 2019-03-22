<?php
include("db.php");

$branch = $_POST['branchName'];
$courseID = $_POST['courseID'];
$year = $_POST['year'];
if (strlen($_POST['month']) == 1) {
	$month = '0' . $_POST['month'];
} else {
	$month = $_POST['month'];
}
$day = $_POST['date'];
$date = $year . '-' . $month . '-' . $day;
//$date = '2019-02-4';

if(!empty($courseID)) {
	$sql = mysqli_query($con, "SELECT eventStart FROM class_activity_event WHERE branch = '" . $branch . "' AND CourseID = '" . $courseID . "' AND active = 1");
}else{
	$sql = mysqli_query($con, "SELECT eventStart FROM class_activity_event WHERE branch = '" . $branch . "' AND active = 1");
}
//$sql = mysqli_query($con, "SELECT eventStart FROM student_class_home WHERE DATE(eventStart) >= '" . $date . "'");

$DataList = [];
$newArr = [];
$EventListData = [];
$msg = "Can't Connection";

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		// echo $key . ' ' . $val;
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}
foreach($newArr as $key => $val){
	$EventListData[$key]['year'] = date('Y', strtotime($val['eventStart']));
	$EventListData[$key]['month'] = date('m', strtotime($val['eventStart']));
	$EventListData[$key]['day'] = date('d', strtotime($val['eventStart']));

}


if (!empty($EventListData)) {
	$msg = "have event";
} else {
	$msg = "don't have event";
}

// print_pre($newArr);
echo json_encode(array('msg' => $msg, 'data' => $EventListData));

//echo date('W', strtotime($date));