<?php

include("db.php");
$styleID = $_POST['styleID'];

if (!empty($styleID)) {
	$sql = mysqli_query($con, "SELECT c.CourseID, c.bannerfile as imgUrl, c.Course, c.Description, c.CourseLength, cs.courseStyleName as style 
			FROM course c LEFT JOIN coursestyle cs ON cs.courseStyleID = c.coursestyle_courseStyleID
			WHERE c.coursestyle_courseStyleID = '" . $styleID . "'");
//	$sql = mysqli_query($con, "SELECT e.id, e.end, e.active,c.CourseID, c.bannerfile as imgUrl, c.Course, c.Description, c.CourseLength, cs.courseStyleName as style
//			FROM course c LEFT JOIN coursestyle cs ON cs.courseStyleID = c.coursestyle_courseStyleID LEFT JOIN events e ON e.course_id = c.CourseID
//			WHERE c.coursestyle_courseStyleID = '" . $styleID . "'
//            ORDER BY e.id, e.end");
}else{
	$sql = mysqli_query($con, "SELECT c.CourseID, c.bannerfile as imgUrl, c.Course, c.Description, c.CourseLength, cs.courseStyleName as style 
			FROM course c LEFT JOIN coursestyle cs ON cs.courseStyleID = c.coursestyle_courseStyleID");
//	$sql = mysqli_query($con, "SELECT e.id, e.end, e.active,c.CourseID, c.bannerfile as imgUrl, c.Course, c.Description, c.CourseLength, cs.courseStyleName as style
//			FROM course c LEFT JOIN coursestyle cs ON cs.courseStyleID = c.coursestyle_courseStyleID LEFT JOIN events e ON e.course_id = c.CourseID
//            ORDER BY e.id, e.end");
}

$DataList = [];
$newArr = [];
$CourseListData = [];
$now = date("Y-m-d H:i:s");

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}
//$i = 0;
foreach ($newArr as $key => $val) {
//	if($now < $val['end'] && $val['active'] == 1) {
		$CourseListData[$key]['imgUrl'] = $val['imgUrl'];
		$CourseListData[$key]['courseID'] = $val['CourseID'];
		$CourseListData[$key]['courseName'] = $val['Course'];
		$CourseListData[$key]['courseStyle'] = $val['style'];
		$CourseListData[$key]['courseLength'] = $val['CourseLength'];
		$CourseListData[$key]['courseDesc'] = $val['Description'];
//		$i++;
//	}

}

if (!empty($CourseListData)) {
	$msg = "success";
} else {
	$msg = "Something wrong happened! Please try again!";
}

// print_pre($newArr);
echo json_encode(array('msg' => $msg, 'course' => $CourseListData));