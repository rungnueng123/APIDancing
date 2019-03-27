<?php
include("db.php");

//$userID = '27';
$userID = $_POST['userID'];
$DataList = [];
$newArr = [];
$UserListData = [];
$msg = "fail";
//$userID = $_POST['userID'];
if (!empty($userID)) {
	$userSql = mysqli_query($con, "SELECT UserID, User, Phone, BirthDate, CoinAmt FROM user WHERE UserID = '" . $userID . "'");
	if (!empty($userSql)) {
		while ($arr = mysqli_fetch_array($userSql, MYSQLI_ASSOC)) {
			foreach ($arr as $key => $val) {
				$DataList[$key] = $val;
			}
			array_push($newArr, $DataList);
		}
	}
	if (!empty($newArr)) {
		foreach ($newArr as $key => $val) {
			$UserListData[$key]['UserID'] = $val['UserID'];
			$UserListData[$key]['User'] = $val['User'];
			$UserListData[$key]['Phone'] = $val['Phone'];
			$UserListData[$key]['CoinAmt'] = $val['CoinAmt'];
			$UserListData[$key]['Birth'] = date('d/m/Y', strtotime($val['BirthDate']));
		}
	}

}
if(!empty($UserListData)){
	$msg = "success";
}
echo json_encode(array('msg' => $msg, 'data' => $UserListData));


?>