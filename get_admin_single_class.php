<?php

include("db.php");
//$eventID = 67;
$eventID = $_POST['eventID'];


$sql = mysqli_query($con, "SELECT * FROM admin_class_checked_activity
									WHERE eventID = '" . $eventID . "'");

$DataList = [];
$newArr = [];
$ClassListData = [];
$date = date('Y-m-d H:i:s');

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}
foreach($newArr as $key => $val){
	$ClassListData[$key]['youtubeUrl'] = substr($val['youtubeUrl'],32);
	$ClassListData[$key]['eventID'] = $val['eventID'];
	$ClassListData[$key]['eventTitle'] = $val['eventTitle'];
	$ClassListData[$key]['playlist'] = $val['playlist'];
	$ClassListData[$key]['eventStyleID'] = $val['eventStyleID'];
	$ClassListData[$key]['eventStyle'] = $val['eventStyle'];
	$ClassListData[$key]['eventTeacher'] = $val['eventTeacher'];

	$ClassListData[$key]['eventDate'] = date('d/m/Y',strtotime($val['start']));
	$ClassListData[$key]['eventTime'] = date('H:i',strtotime($val['start'])).' - '.date('H:i',strtotime($val['end']));
	$ClassListData[$key]['eventBranch'] = $val['Branch'];
	$ClassListData[$key]['description'] = $val['description'];



}

if (!empty($ClassListData)) {
	$msg = "success";
} else {
	$msg = "Something wrong happened! Please try again!";
}

// print_pre($newArr);
echo json_encode(array('msg' => $msg, 'class' => $ClassListData));