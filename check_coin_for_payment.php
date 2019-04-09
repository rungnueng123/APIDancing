<?php
include("db.php");
$userID = $_POST['userID'];
$coin = $_POST['coin'];
//$userID = 63;
//$coin = 91;

$checkCoinSql = mysqli_query($con,"SELECT CoinAmt FROM user WHERE UserID = '" . $userID . "'");

$DataList = [];
$UserListData = [];
$msg = "Your coin don't enough! Please go to shop!";
while ($arr = mysqli_fetch_array($checkCoinSql, MYSQLI_ASSOC)) {
$output['coin'] = $arr['CoinAmt'];
	foreach ($arr as $key => $val) {
//		echo $key . ' ' . $val;
		$DataList[$key] = $val;
	}
	array_push($UserListData, $DataList);
}
if($coin <= $output['coin']){
	$msg = "enough";
}
$output['message'] = $msg;
echo json_encode($output);