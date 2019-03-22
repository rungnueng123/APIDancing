<?php
//require database
include("db.php");
$CourseID = $_POST['courseID'];

$sql = mysqli_query($con, "SELECT c.CourseID, c.Course, c.CourseLength, c.Description, c.ClipLink, cp.CoinAmt, cs.courseStyleName, c.gallery1, c.gallery2, c.gallery3, c.gallery4
								FROM course c LEFT JOIN courseprice cp ON cp.course_CourseID = c.CourseID LEFT JOIN coursestyle cs ON cs.courseStyleID = c.coursestyle_courseStyleID
								WHERE c.CourseID = '" . $CourseID . "'");
$num = mysqli_num_rows($sql);

$DataList = [];
$newArr = [];
$CourseListData = [];
$GalleryListData = [];
$msg = "Can't Connection";

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}
foreach($newArr as $key => $val){
	$CourseListData[$key]['CourseID'] = $val['CourseID'];
	$CourseListData[$key]['Course'] = $val['Course'];
	$CourseListData[$key]['CourseLength'] = $val['CourseLength'];
	$CourseListData[$key]['Description'] = $val['Description'];
	$CourseListData[$key]['ClipLink'] = substr($val['ClipLink'],32);
	$CourseListData[$key]['CoinAmt'] = $val['CoinAmt'];
	$CourseListData[$key]['courseStyleName'] = $val['courseStyleName'];


	for($i = 1;$i<=4;$i++){
		$GalleryListData[$i-1]['gallery'] = $val['gallery'.$i];
	}

//	$GalleryListData['1']['gallery'] = $val['gallery1'];
//	$GalleryListData['2']['gallery'] = $val['gallery2'];
//	$GalleryListData['3']['gallery'] = $val['gallery3'];
//	$GalleryListData['4']['gallery'] = $val['gallery4'];

}
//print_pre($CourseListData);
//print_pre($GalleryListData);
//exit();



if (!empty($CourseListData)) {
	$msg = "success";
} else {
	$msg = "Something wrong happened! Please try again!";
}

// print_pre($newArr);
echo json_encode(array('msg' => $msg, 'class' => $CourseListData, 'gallery' => $GalleryListData));
?>