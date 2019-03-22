<?php

include("db.php");
$userID = $_POST['userID'];

$sql = mysqli_query($con, "SELECT * FROM student_show_course_paid
									WHERE UserID = '" . $userID . "'");

$DataList = [];
$newArr = [];
$ClassListData = [];

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}

foreach($newArr as $key => $val){
	$ClassListData[$key]['eventID'] = $val['eventID'];
	$ClassListData[$key]['eventTitle'] = $val['eventTitle'];
	$ClassListData[$key]['description'] = $val['description'];
	$ClassListData[$key]['playlist'] = $val['playlist'];
	$ClassListData[$key]['eventStyle'] = $val['courseStyleName'];
	$ClassListData[$key]['eventTeacher'] = $val['teacher'];
	$ClassListData[$key]['active'] = $val['Active'];
	$ClassListData[$key]['eventDate'] = date('d/m/Y',strtotime($val['start']));
	$ClassListData[$key]['eventTime'] = date('H:i',strtotime($val['start'])).' - '.date('H:i',strtotime($val['end']));
	$ClassListData[$key]['eventBranch'] = $val['Branch'];
	$ClassListData[$key]['imgUrl'] = $val['imgUrl'];

}

if (!empty($ClassListData)) {
	$msg = "success";
} else {
	$msg = "Something wrong happened! Please try again!";
}

// print_pre($newArr);
echo json_encode(array('msg' => $msg, 'class' => $ClassListData));