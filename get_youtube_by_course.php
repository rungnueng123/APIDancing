<?php

include("db.php");

//$courseID = 1;
$courseID = $_POST['courseID'];

$sql = mysqli_query($con, "SELECT ClipLink as youtube FROM course WHERE CourseID = '" . $courseID . "'");

$DataList = [];
$youtube = '';
$msg = 'Something wrong happened! Please try again!';

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$youtube = substr($val,32);
	}
}

if(!empty($youtube)){
	$msg = 'success';
}
echo json_encode(array('msg' => $msg, 'youtube' => $youtube));