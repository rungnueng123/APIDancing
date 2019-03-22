<?php

include("db.php");

$sql = mysqli_query($con,"SELECT ad_id as id, ad_title as title, ad_desc as description, ad_banner as banner FROM advertising WHERE Active = '1'");
//echo "SELECT ad_id as id, ad_title as title, ad_desc as description, ad_banner as banner FROM advertising WHERE Active = '0'";

$DataList = [];
$newArr = [];
$BannerListData = [];

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
//		echo $key.'/'.$val;
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}

foreach($newArr as $key => $val){
	$BannerListData[$key]['id'] = $val['id'];
	$BannerListData[$key]['title'] = $val['title'];
	$BannerListData[$key]['desc'] = $val['description'];
	$BannerListData[$key]['imgUrl'] = $val['banner'];

}

if (!empty($BannerListData)) {
	$msg = "success";
} else {
	$msg = "Something wrong happened! Please try again!";
}

// print_pre($newArr);
echo json_encode(array('msg' => $msg, 'data' => $BannerListData));