<?php
include('db.php');

$sql = mysqli_query($con, "SELECT title, link, author FROM suggest_video WHERE active = 1");
$DataList = [];
$newArr = [];
$VideoListData = [];
$msg = 'Something wrong happened! Please try again!';

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}

foreach ($newArr as $key => $val) {
	$VideoListData[$key]['title'] = $val['title'];
	$splitUrl = explode("=", $val['link']);
	$VideoListData[$key]['youtubeUrl'] = $splitUrl['1'];
	$VideoListData[$key]['author'] = $val['author'];
//	print_pre($ytarr);
//	exit();


}

if (!empty($VideoListData)) {
	$msg = 'success';
}
echo json_encode(array('msg' => $msg, 'data' => $VideoListData));
//print_pre($VideoListData);
