<?php

include("db.php");

$sql = mysqli_query($con, "SELECT courseStyleID, courseStyleName, courseStyleDesc, courseStyleImage FROM coursestyle ORDER BY courseStyleID");

$DataList = [];
$newArr = [];
$StyleListData = [];

$DataList['courseStyleID'] = '0';
$DataList['courseStyleName'] = 'ALL STYLE';
$DataList['courseStyleDesc'] = 'All Style';
$DataList['courseStyleImage'] = 'imgBanner/bg_all.png';
array_push($newArr, $DataList);
while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}

foreach($newArr as $key => $val){
	$StyleListData[$key]['courseStyleID'] = $val['courseStyleID'];
	$StyleListData[$key]['courseStyleName'] = $val['courseStyleName'];
	$StyleListData[$key]['courseStyleDesc'] = $val['courseStyleDesc'];
	$StyleListData[$key]['courseStyleImage'] = $val['courseStyleImage'];

}

if (!empty($StyleListData)) {
	$msg = "success";
} else {
	$msg = "Something wrong happened! Please try again!";
}

// print_pre($newArr);
echo json_encode(array('msg' => $msg, 'style' => $StyleListData));