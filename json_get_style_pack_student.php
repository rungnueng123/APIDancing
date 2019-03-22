<?php

include("db.php");

$stylePackSql = mysqli_query($con, "SELECT stylePackID, namePack, style_id as styleID, style, coin, times, imgUrl FROM style_pack_all WHERE active = '1'");

$DataList1 = [];
$newArr1 = [];
$StylePackListData = [];
$msg = "Something wrong happened! Please try again!";

while ($arr = mysqli_fetch_array($stylePackSql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		// echo $key . ' ' . $val;
		$DataList1[$key] = $val;
	}
	array_push($newArr1, $DataList1);
}
foreach ($newArr1 as $key => $val) {
	$StylePackListData[$key]['stylePackID'] = $val['stylePackID'];
	$StylePackListData[$key]['namePack'] = $val['namePack'];
	$StylePackListData[$key]['styleID'] = $val['styleID'];
	$StylePackListData[$key]['style'] = $val['style'];
	$StylePackListData[$key]['coin'] = $val['coin'];
	$StylePackListData[$key]['times'] = $val['times'];
	$StylePackListData[$key]['imgUrl'] = $val['imgUrl'];

}
if (!empty($StylePackListData)) {
	$msg = 'success';
}

echo json_encode(array('msg' => $msg, 'data' => $StylePackListData));