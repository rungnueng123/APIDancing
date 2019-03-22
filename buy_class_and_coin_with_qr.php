<?php

include("db.php");

$userID = $_POST['userID'];
$eventID = $_POST['eventID'];
$coin = $_POST['coin'];
$baht = $_POST['baht'];
$bahtAddCoinTxn = -$baht;
$date = date('Y-m-d H:i:s');
$msg = 'Something wrong happened! Please try again!';

$addCoinTxnSql = "INSERT INTO cointxn (UserID, TxnDate, Amount, CoinAmt, BuySell, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy)
                    VALUES ('" . $userID . "','" . $date . "','" . $bahtAddCoinTxn . "','" . $coin . "','Q','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "')";

if ($con->query($addCoinTxnSql)) {
	$userSelectCoinSql = mysqli_query($con, "SELECT CoinAmt FROM user WHERE UserID = '" . $userID . "'");
	while ($arr = mysqli_fetch_array($userSelectCoinSql, MYSQLI_ASSOC)) {
		foreach ($arr as $key => $val) {
			$coinAmt = $val;
		}
	}
	$coinUpdate = $coinAmt + $coin;
	$userUpdateCoinSql = "
	   UPDATE user
	   SET CoinAmt = '" . $coinUpdate . "'
	   WHERE UserID = '" . $userID . "'
	   ";

	if ($con->query($userUpdateCoinSql)) {
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
                    VALUES ('" . $eventID . "',1,'" . $coin . "',0,1,'" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $regisID . "')";
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
								$coinAmtUpdate = $userListData['CoinAmt'] - $coin;
								$updateCoinUserSql = mysqli_query($con, "UPDATE user SET CoinAmt = '" . $coinAmtUpdate . "' WHERE UserID = '$userID'");
							}
							$addRegisClassSql = mysqli_query($con, "INSERT INTO cointxn (UserID, TxnDate, Amount, CoinAmt, BuySell, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy)
                    				VALUES ('" . $userID . "','" . $date . "','" . $baht . "','" . $coin . "','S','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "')");
							$msg = 'Payment Success';
						}
					}
				}
			}
		}else{
			$addStudentSchedSql = "INSERT INTO studentsched (eventID, payType, coinAmt, times, Active, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, registration_RegisID)
                    VALUES ('" . $eventID . "',1,'" . $coin . "',0,1,'" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $checkUserListData['RegisID'] . "')";
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
							$coinAmtUpdate = $userListData['CoinAmt'] - $coin;
							$updateCoinUserSql = mysqli_query($con, "UPDATE user SET CoinAmt = '" . $coinAmtUpdate . "' WHERE UserID = '$userID'");
						}

						$addRegisClassSql = mysqli_query($con, "INSERT INTO cointxn (UserID, TxnDate, Amount, CoinAmt, BuySell, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy)
                    VALUES ('" . $userID . "','" . $date . "','" . $baht . "','" . $coin . "','S','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "')");

						$msg = 'Payment Success';
					}
				}
			}
		}

	}
}
$output['message'] = $msg;
echo json_encode($output);
//echo $userID.'/'.$eventID.'/'.$coin.'/'.$baht;
