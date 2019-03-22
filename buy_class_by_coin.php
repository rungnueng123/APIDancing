<?php
include("db.php");
//$eventID = 60;
//$userID = 27;
//$coinAmt = 4;
$eventID = $_POST['eventID'];
$userID = $_POST['userID'];
$coinAmt = $_POST['coin'];
$date = date('Y-m-d H:i:s');

//echo $eventID.' '.$userID;
//exit();
$msg = '';
if (!empty($eventID) && !empty($userID)) {
	$checkUserSql = mysqli_query($con, "SELECT RegisID
											FROM registration WHERE user_UserID = '" . $userID . "'");
	$DataList = [];
	$checkUserListData = [];
	$newArr = [];
	$msg = "Something wrong happened! Please try again!";
	while ($arr = mysqli_fetch_array($checkUserSql, MYSQLI_ASSOC)) {
		foreach ($arr as $key => $val) {
//		echo $key . ' ' . $val;
			$DataList[$key] = $val;
		}
		array_push($newArr, $DataList);
	}
	foreach ($newArr as $val) {
		$checkUserListData = $val;
	}

	if (empty($checkUserListData)) {
		$addRegisClassSql = "INSERT INTO registration (CoinAmt, Times, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, user_UserID)
                    VALUES (0,0,'" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $userID . "')";
		if ($con->query($addRegisClassSql)) {
			$regisID = mysqli_insert_id($con);
			$addStudentSchedSql = "INSERT INTO studentsched (eventID, payType, coinAmt, times, Active, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, registration_RegisID)
                    VALUES ('" . $eventID . "',1,'" . $coinAmt . "',0,1,'" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $regisID . "')";
			if ($con->query($addStudentSchedSql)) {
				$sumCoinAmtSql = mysqli_query($con, "SELECT SUM(coinAmt) as sumCoin FROM studentsched WHERE registration_RegisID = '" . $regisID .
					"'");
				$DataList1 = [];
				$newArr1 = [];
				$sumCoinListData = [];
				while ($arr = mysqli_fetch_array($sumCoinAmtSql, MYSQLI_ASSOC)) {
					foreach ($arr as $key => $val) {
						$DataList1[$key] = $val;
					}
					array_push($newArr1, $DataList1);
				}
				foreach ($newArr1 as $val) {
					$sumCoinListData = $val;
				}
//				print_r($sumCoinListData);
				if (!empty($sumCoinListData)) {
					$updateRegisSql = "UPDATE registration SET CoinAmt = '" . $sumCoinListData['sumCoin'] . "' WHERE RegisID = '" . $regisID . "'";
					if ($con->query($updateRegisSql)) {
						$DataList2 = [];
						$newArr2 = [];
						$userListData = [];
						$getCoinUserSql = mysqli_query($con, "SELECT CoinAmt
											FROM user WHERE UserID = '" . $userID . "'");
						while ($arr = mysqli_fetch_array($getCoinUserSql, MYSQLI_ASSOC)) {
							foreach ($arr as $key => $val) {
								$DataList2[$key] = $val;
							}
							array_push($newArr2, $DataList2);
						}
						foreach ($newArr2 as $val) {
							$userListData = $val;
						}
						if (!empty($userListData)) {
							$coinAmtUpdate = $userListData['CoinAmt'] - $coinAmt;
							$updateCoinUserSql = mysqli_query($con, "UPDATE user SET CoinAmt = '" . $coinAmtUpdate . "' WHERE UserID = '$userID'");
						}

						$DataList3 = [];
						$newArr3 = [];
						$selectRegisNo = mysqli_query($con, "SELECT RegisNo
											FROM registration WHERE RegisID = '" . $regisID . "'");
						while ($arr = mysqli_fetch_array($selectRegisNo, MYSQLI_ASSOC)) {
							foreach ($arr as $key => $val) {
								$DataList3[$key] = $val;
							}
							array_push($newArr3, $DataList3);
						}
						$RegisNo = '';
						foreach ($newArr3 as $val) {
							$RegisNo = $val['RegisNo'];
						}

						$DataList4 = [];
						$newArr4 = [];
						$calAmountSql = mysqli_query($con, "SELECT value
											FROM syssetting WHERE sysDesc = 'CoinBase'");
						while ($arr = mysqli_fetch_array($calAmountSql, MYSQLI_ASSOC)) {
							foreach ($arr as $key => $val) {
								$DataList4[$key] = $val;
							}
							array_push($newArr4, $DataList4);
						}
						$Amount = '';
						foreach ($newArr4 as $val) {
							$valueCoin = $val['value'];
							$Amount = $valueCoin * $coinAmt;
						}

						$addRegisClassSql = mysqli_query($con, "INSERT INTO cointxn (UserID, DocNo, TxnDate, Amount, CoinAmt, BuySell, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy)
                    VALUES ('" . $userID . "','" . $RegisNo . "','" . $date . "','" . $Amount . "','" . $coinAmt . "','S','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "')");


						$msg = 'Payment Success';
					}
				}

			}
		}
	} else {
		$addStudentSchedSql = "INSERT INTO studentsched (eventID, payType, coinAmt, times, Active, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, registration_RegisID)
                    VALUES ('" . $eventID . "',1,'" . $coinAmt . "',0,1,'" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $checkUserListData['RegisID'] . "')";
		if ($con->query($addStudentSchedSql)) {
			$sumCoinAmtSql = mysqli_query($con, "SELECT SUM(coinAmt) as sumCoin FROM studentsched WHERE registration_RegisID = '" . $checkUserListData['RegisID'] . "'");
			$DataList1 = [];
			$newArr1 = [];
			$sumCoinListData = [];
			while ($arr = mysqli_fetch_array($sumCoinAmtSql, MYSQLI_ASSOC)) {
				foreach ($arr as $key => $val) {
					$DataList1[$key] = $val;
				}
				array_push($newArr1, $DataList1);
			}
			foreach ($newArr1 as $val) {
				$sumCoinListData = $val;
			}
//			print_r($sumCoinListData);
			if (!empty($sumCoinListData)) {
				$updateRegisSql = "UPDATE registration SET CoinAmt = '" . $sumCoinListData['sumCoin'] . "' WHERE RegisID = '" . $checkUserListData['RegisID'] . "'";
				if ($con->query($updateRegisSql)) {
					$DataList2 = [];
					$newArr2 = [];
					$userListData = [];
					$getCoinUserSql = mysqli_query($con, "SELECT CoinAmt
											FROM user WHERE UserID = '" . $userID . "'");
					while ($arr = mysqli_fetch_array($getCoinUserSql, MYSQLI_ASSOC)) {
						foreach ($arr as $key => $val) {
							$DataList2[$key] = $val;
						}
						array_push($newArr2, $DataList2);
					}
					foreach ($newArr2 as $val) {
						$userListData = $val;
					}
					if (!empty($userListData)) {
						$coinAmtUpdate = $userListData['CoinAmt'] - $coinAmt;
						$updateCoinUserSql = mysqli_query($con, "UPDATE user SET CoinAmt = '" . $coinAmtUpdate . "' WHERE UserID = '" . $userID . "'");
					}

					$DataList3 = [];
					$newArr3 = [];
					$selectRegisNo = mysqli_query($con, "SELECT RegisNo
											FROM registration WHERE RegisID = '" . $checkUserListData['RegisID'] . "'");
					while ($arr = mysqli_fetch_array($selectRegisNo, MYSQLI_ASSOC)) {
						foreach ($arr as $key => $val) {
							$DataList3[$key] = $val;
						}
						array_push($newArr3, $DataList3);
					}
					$RegisNo = '';
					foreach ($newArr3 as $val) {
						$RegisNo = $val['RegisNo'];
					}

					$DataList4 = [];
					$newArr4 = [];
					$calAmountSql = mysqli_query($con, "SELECT value
											FROM syssetting WHERE sysDesc = 'CoinBase'");
					while ($arr = mysqli_fetch_array($calAmountSql, MYSQLI_ASSOC)) {
						foreach ($arr as $key => $val) {
							$DataList4[$key] = $val;
						}
						array_push($newArr4, $DataList4);
					}
					$Amount = '';
					foreach ($newArr4 as $val) {
						$valueCoin = $val['value'];
						$Amount = $valueCoin * $coinAmt;
					}

					$addRegisClassSql = mysqli_query($con, "INSERT INTO cointxn (UserID, TxnDate, Amount, CoinAmt, BuySell, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy)
                    VALUES ('" . $userID . "','" . $date . "','" . $Amount . "','" . $coinAmt . "','S','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "')");

					$msg = 'Payment Success';
				}
			}

		}
	}


} else {
	$msg = 'Something wrong happened! Please try again!';
}

$output['message'] = $msg;
echo json_encode($output);