<?php

include("db.php");
$userID = $_POST['userID'];
$coin = $_POST['coin'];

$checkCoinSql = mysqli_query($con,"SELECT UserID FROM user WHERE UserID = '" . $userID . "' AND CoinAmt >= '" . $coin . "'");

$DataList = [];
$UserListData = [];
$msg = "Your coin don't enough! Please go to shop!";
while ($arr = mysqli_fetch_array($checkCoinSql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
//		echo $key . ' ' . $val;
		$DataList[$key] = $val;
	}
	array_push($UserListData, $DataList);
}
if(!empty($UserListData)){
	$msg = "enough";
}
$output['message'] = $msg;
echo json_encode($output);