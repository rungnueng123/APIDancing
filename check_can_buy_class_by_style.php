<?php

include("db.php");
//$eventStyleID = 1;
//$userID = 27;
$eventStyleID = $_POST['eventStyleID'];
$userID = $_POST['userID'];

$time = '';

// เหลือเช็คแพ็คเกจหมดอายุ
$checkStyleTimeSql = mysqli_query($con,"SELECT times FROM user_has_stylepack 
									WHERE user_UserID = '" . $userID . "' AND coursestyle_courseStyleID = '" . $eventStyleID . "' AND times > '0'
									ORDER BY CreatedDate ASC
									LIMIT 1");
$DataList = [];
$newArr = [];
while ($arr = mysqli_fetch_array($checkStyleTimeSql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
//		echo $key . ' ' . $val;
		$time = $val;
	}
}

//print_pre($newArr);
if($time > 0){
	$output['msg'] = 'can';
}else{
	$output['msg'] = 'cant';
}
echo json_encode($output);