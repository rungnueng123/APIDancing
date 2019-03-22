<?php

include("db.php");
$styleID = $_POST['styleID'];

if (!empty($styleID)) {
	$sql = mysqli_query($con, "SELECT c.CourseID, c.bannerfile as imgUrl, c.Course, c.Description, c.CourseLength, cs.courseStyleName as style 
			FROM course c LEFT JOIN coursestyle cs ON cs.courseStyleID = c.coursestyle_courseStyleID
			WHERE c.coursestyle_courseStyleID = '" . $styleID . "'");
}else{
	$sql = mysqli_query($con, "SELECT c.CourseID, c.bannerfile as imgUrl, c.Course, c.Description, c.CourseLength, cs.courseStyleName as style 
			FROM course c LEFT JOIN coursestyle cs ON cs.courseStyleID = c.coursestyle_courseStyleID");
}

$DataList = [];
$newArr = [];
$CourseListData = [];

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}

foreach ($newArr as $key => $val) {
	$CourseListData[$key]['imgUrl'] = $val['imgUrl'];
	$CourseListData[$key]['courseID'] = $val['CourseID'];
	$CourseListData[$key]['courseName'] = $val['Course'];
	$CourseListData[$key]['courseStyle'] = $val['style'];
	$CourseListData[$key]['courseLength'] = $val['CourseLength'];
	$CourseListData[$key]['courseDesc'] = $val['Description'];

}

if (!empty($CourseListData)) {
	$msg = "success";
} else {
	$msg = "Something wrong happened! Please try again!";
}

// print_pre($newArr);
echo json_encode(array('msg' => $msg, 'course' => $CourseListData));