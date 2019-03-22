<?php
//require database
include("db.php");
//$year = "2019";
//$month = "02";
//$day = "08";
$year = $_POST['year'];
if (strlen($_POST['month']) == 1) {
	$month = '0' . $_POST['month'];
} else {
	$month = $_POST['month'];
}
$day = $_POST['date'];
$date = $year . '-' . $month . '-' . $day;

$monthName = date("F",strtotime($date));

$sql = mysqli_query($con, "SELECT * FROM student_class_home WHERE DATE(eventStart) = '" . $date . "' AND active = 1");
$num = mysqli_num_rows($sql);

$DataList = [];
$newArr = [];
$ClassListData = [];
$msg = "Can't Connection";

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		// echo $key . ' ' . $val;
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}
foreach($newArr as $key => $val){
	$ClassListData[$key]['eventID'] = $val['eventID'];
	$ClassListData[$key]['eventStart'] = date('H:i', strtotime($val['eventStart']));
	$ClassListData[$key]['eventEnd'] = date('H:i', strtotime($val['eventEnd']));
	$ClassListData[$key]['title'] = $val['title'];
	$ClassListData[$key]['CourseID'] = $val['CourseID'];
	$ClassListData[$key]['gallery1'] = $val['gallery1'];
	$ClassListData[$key]['playlistTitle'] = $val['playlistTitle'];
	$ClassListData[$key]['courseStyleName'] = $val['courseStyleName'];
	$ClassListData[$key]['teacher'] = $val['teacher'];

}


if (!empty($ClassListData)) {
	$msg = "have class";
} else {
	$msg = "don't have class";
}

// print_pre($newArr);
echo json_encode(array('msg' => $msg, 'data' => $ClassListData, 'monthName' => $monthName));
?>