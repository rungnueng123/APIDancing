<?php

include("db.php");

$coinPackSql = mysqli_query($con, "SELECT id as coinPackID, namePack, baht, coin, packimg as imgUrl  FROM coinpack WHERE Active = '1'");

$DataList1 = [];
$newArr1 = [];
$CoinPackListData = [];
$msg = "Something wrong happened! Please try again!";

while ($arr = mysqli_fetch_array($coinPackSql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		// echo $key . ' ' . $val;
		$DataList1[$key] = $val;
	}
	array_push($newArr1, $DataList1);
}
foreach ($newArr1 as $key => $val) {
	$CoinPackListData[$key]['coinPackID'] = $val['coinPackID'];
	$CoinPackListData[$key]['namePack'] = $val['namePack'];
	$CoinPackListData[$key]['baht'] = $val['baht'];
	$CoinPackListData[$key]['coin'] = $val['coin'];
	$CoinPackListData[$key]['imgUrl'] = $val['imgUrl'];

}
if(!empty($CoinPackListData)){
	$msg = 'finish';
}

echo json_encode(array('msg' => $msg, 'data' => $CoinPackListData));