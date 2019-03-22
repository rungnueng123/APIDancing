<?php

include("db.php");
$eventID = $_POST['eventID'];
$userID = $_POST['userID'];

$sql = mysqli_query($con, "SELECT * FROM student_show_course_paid
									WHERE UserID = '" . $userID . "' AND eventID = '" . $eventID . "'
									LIMIT 1");

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
	$ClassListData[$key]['description'] = 'รายละเอียด : '.$val['description'];
	$ClassListData[$key]['playlist'] = 'เพลง : '.$val['playlist'];
	$ClassListData[$key]['eventStyle'] = 'สไตล์ : '.$val['courseStyleName'];
	$ClassListData[$key]['eventTeacher'] = 'ผู้สอน : '.$val['teacher'];
	$ClassListData[$key]['Active'] = $val['Active'];
	if($val['Active'] == 1){
		$ClassListData[$key]['eventStatus'] = 'สถานะ : ยังไม่ได้เช็คชื่อ';
	}else if($val['Active'] == 0){
		$ClassListData[$key]['eventStatus'] = 'สถานะ : เช็คชื่อแล้ว';
	}else{
		$ClassListData[$key]['eventStatus'] = 'สถานะ : ยกเลิกคลาส';
	}
	$ClassListData[$key]['eventDate'] = 'วันที่เรียน : '.date('d/m/Y',strtotime($val['start']));
	$ClassListData[$key]['eventTime'] = 'เวลาเรียน : '.date('H:i',strtotime($val['start'])).' - '.date('H:i',strtotime($val['end']));
	$ClassListData[$key]['eventRoom'] = 'ห้องเรียน : '.$val['room'];
	$ClassListData[$key]['eventBranch'] = 'สาขา : '.$val['Branch'];
	$ClassListData[$key]['imgUrl'] = $val['imgUrl'];

}

if (!empty($ClassListData)) {
	$msg = "success";
} else {
	$msg = "Something wrong happened! Please try again!";
}

// print_pre($newArr);
echo json_encode(array('msg' => $msg, 'class' => $ClassListData));