<?php

include("db.php");
//$eventID = 67;
$eventID = $_POST['eventID'];
$userID = $_POST['userID'];


$sql = mysqli_query($con, "SELECT * FROM student_course_class_activity
									WHERE eventID = '" . $eventID . "'");

$DataList = [];
$newArr = [];
$ClassListData = [];
$date = date('Y-m-d H:i:s');
//echo $date;

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}
foreach($newArr as $key => $val){
	$ClassListData[$key]['eventID'] = $val['eventID'];
	$ClassListData[$key]['imgUrl'] = $val['imgUrl'];
	$splitUrl = explode("=", $val['youtubeUrl']);
	$videoID = $splitUrl['1'];
	$ClassListData[$key]['youtubeUrl'] = $videoID;
	$ClassListData[$key]['eventTitle'] = $val['eventTitle'];
	$ClassListData[$key]['playlist'] = $val['playlist'];
	$ClassListData[$key]['eventStyleID'] = $val['eventStyleID'];
	$ClassListData[$key]['eventStyle'] = $val['eventStyle'];
	$ClassListData[$key]['eventTeacher'] = $val['eventTeacher'];

	$ClassListData[$key]['eventDate'] = date('d/m/Y',strtotime($val['start']));
	$ClassListData[$key]['eventTime'] = date('H:i',strtotime($val['start'])).' - '.date('H:i',strtotime($val['end']));

	$ClassListData[$key]['eventEmpty'] = $val['MaxStudent'] - $val['numStudent'];
	$ClassListData[$key]['eventBranch'] = $val['Branch'];

	$ClassListData[$key]['description'] = $val['description'];
	$ClassListData[$key]['coin'] = $val['coin'];
//	if($date < $val['start']){


	if($date < $val['start']){
		$ClassListData[$key]['canBuy'] = 'can';
	}else{
		$ClassListData[$key]['canBuy'] = 'cant';
	}

}

if (!empty($ClassListData)) {
	$msg = "success";
} else {
	$msg = "Something wrong happened! Please try again!";
}

$checkSql = mysqli_query($con,"SELECT userID FROM check_buy_class_already WHERE userID ='" . $userID . "' AND eventID = '" . $eventID . "'");
while ($arr = mysqli_fetch_array($checkSql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		if(empty($val['userID'])){
			$buyAlready = 'yes';
		}else{
			$buyAlready = 'no';
		}
	}
}

// print_pre($newArr);
echo json_encode(array('msg' => $msg, 'class' => $ClassListData, 'buyAlready' => $buyAlready));