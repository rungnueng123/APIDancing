<?php

include("db.php");
$courseID = $_POST['courseID'];


$sql = mysqli_query($con, "SELECT * FROM student_course_class_activity
									WHERE courseID = '" . $courseID . "' AND active = 1");

$DataList = [];
$newArr = [];
$ClassListData = [];
$GalleryListData = [];

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}
foreach($newArr as $key => $val){
	$ClassListData[$key]['eventID'] = $val['eventID'];
	$ClassListData[$key]['imgUrl'] = $val['imgUrl'];
	$ClassListData[$key]['eventTitle'] = $val['eventTitle'];
	$ClassListData[$key]['playlist'] = $val['playlist'];
	$ClassListData[$key]['eventStyle'] = $val['eventStyle'];
	$ClassListData[$key]['eventTeacher'] = $val['eventTeacher'];

	$ClassListData[$key]['eventDate'] = date('d/m/Y',strtotime($val['start']));
	$ClassListData[$key]['eventTime'] = date('H:i',strtotime($val['start'])).' - '.date('H:i',strtotime($val['end']));

	$ClassListData[$key]['eventEmpty'] = $val['MaxStudent'] - $val['numStudent'];
	$ClassListData[$key]['eventBranch'] = $val['Branch'];

	$ClassListData[$key]['description'] = $val['description'];
	$ClassListData[$key]['coin'] = $val['coin'];

}



if (!empty($ClassListData)) {
	$msg = "success";
} else {
	$msg = "Something wrong happened! Please try again!";
}

// print_pre($newArr);
echo json_encode(array('msg' => $msg, 'class' => $ClassListData));