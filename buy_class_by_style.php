<?php

include("db.php");
//$eventStyleID = 1;
//$userID = 27;
//$eventID = 72;
$eventStyleID = $_POST['eventStyleID'];
$userID = $_POST['userID'];
$eventID = $_POST['eventID'];
//echo $eventStyleID.'/'.$userID.'/'.$eventID;
//exit();

$date = date('Y-m-d H:i:s');
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
                    VALUES ('" . $eventID . "',2,0,1,1,'" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $regisID . "')";
			if ($con->query($addStudentSchedSql)) {
				$sumTimesSql = mysqli_query($con, "SELECT SUM(times) as sumTimes FROM studentsched WHERE registration_RegisID = '" . $regisID .
					"'");
				$DataList1 = [];
				$newArr1 = [];
				$sumTimeListData = [];
				while ($arr = mysqli_fetch_array($sumTimesSql, MYSQLI_ASSOC)) {
					foreach ($arr as $key => $val) {
						$DataList1[$key] = $val;
					}
					array_push($newArr1, $DataList1);
				}
				foreach ($newArr1 as $val) {
					$sumTimeListData = $val;
				}
				if (!empty($sumTimeListData)) {
					$updateRegisSql = "UPDATE registration SET Times = '" . $sumTimeListData['sumTimes'] . "' WHERE RegisID = '" . $regisID . "'";
					if ($con->query($updateRegisSql)) {
						$getIdAndTimeSql = mysqli_query($con, "SELECT id, times, stylepack_id FROM user_has_stylepack 
									WHERE user_UserID = '" . $userID . "' AND coursestyle_courseStyleID = '" . $eventStyleID . "' AND times > '0'
									ORDER BY CreatedDate ASC
									LIMIT 1");
						$DataList2 = [];
						$newArr2 = [];
						$IdAndTimeListData = [];
						while ($arr = mysqli_fetch_array($getIdAndTimeSql, MYSQLI_ASSOC)) {
							foreach ($arr as $key => $val) {
//		echo $key . ' ' . $val;
								$DataList[$key] = $val;
							}
							array_push($newArr, $DataList);
						}
						foreach ($newArr as $val) {
							$IdAndTimeListData = $val;
						}
						$timeUpdate = $IdAndTimeListData['times'] - 1;

						$updateUserHasStyleSql = "UPDATE user_has_stylepack SET times = '" . $timeUpdate . "' WHERE id = '" . $IdAndTimeListData['id'] . "'";
						if ($con->query($updateUserHasStyleSql)) {
							$insertPaymentStyleTxnSql = "INSERT INTO paymentstyletxn (user_UserID, stylepack_id, times, BuyUse, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, user_has_stylepack_id, coin)
                    			VALUES ('" . $userID . "','" . $IdAndTimeListData['stylepack_id'] . "',1,'U','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $IdAndTimeListData['id'] . "',0)";
							if ($con->query($insertPaymentStyleTxnSql)) {
								$msg = 'success';
							}
						}

					}
				}
			}
		}
	} else {
		$addStudentSchedSql = "INSERT INTO studentsched (eventID, payType, coinAmt, times, Active, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, registration_RegisID)
                    VALUES ('" . $eventID . "',2,0,1,1,'" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $checkUserListData['RegisID'] . "')";
		if ($con->query($addStudentSchedSql)) {
			$sumTimesSql = mysqli_query($con, "SELECT SUM(times) as sumTimes FROM studentsched WHERE registration_RegisID = '" . $checkUserListData['RegisID'] .
				"'");
			$DataList1 = [];
			$newArr1 = [];
			$sumTimeListData = [];
			while ($arr = mysqli_fetch_array($sumTimesSql, MYSQLI_ASSOC)) {
				foreach ($arr as $key => $val) {
					$DataList1[$key] = $val;
				}
				array_push($newArr1, $DataList1);
			}
			foreach ($newArr1 as $val) {
				$sumTimeListData = $val;
			}
			if (!empty($sumTimeListData)) {
				$updateRegisSql = "UPDATE registration SET Times = '" . $sumTimeListData['sumTimes'] . "' WHERE RegisID = '" . $checkUserListData['RegisID'] . "'";
				if ($con->query($updateRegisSql)) {
					$getIdAndTimeSql = mysqli_query($con, "SELECT id, times, stylepack_id FROM user_has_stylepack 
									WHERE user_UserID = '" . $userID . "' AND coursestyle_courseStyleID = '" . $eventStyleID . "' AND times > '0'
									ORDER BY CreatedDate ASC
									LIMIT 1");
					$DataList2 = [];
					$newArr2 = [];
					$IdAndTimeListData = [];
					while ($arr = mysqli_fetch_array($getIdAndTimeSql, MYSQLI_ASSOC)) {
						foreach ($arr as $key => $val) {
//		echo $key . ' ' . $val;
							$DataList[$key] = $val;
						}
						array_push($newArr, $DataList);
					}
					foreach ($newArr as $val) {
						$IdAndTimeListData = $val;
					}
					$timeUpdate = $IdAndTimeListData['times'] - 1;

					$updateUserHasStyleSql = "UPDATE user_has_stylepack SET times = '" . $timeUpdate . "' WHERE id = '" . $IdAndTimeListData['id'] . "'";
					if ($con->query($updateUserHasStyleSql)) {
						$insertPaymentStyleTxnSql = "INSERT INTO paymentstyletxn (user_UserID, stylepack_id, times, BuyUse, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, user_has_stylepack_id, coin)
                    			VALUES ('" . $userID . "','" . $IdAndTimeListData['stylepack_id'] . "',1,'U','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $IdAndTimeListData['id'] . "',0)";
						if ($con->query($insertPaymentStyleTxnSql)) {
							$msg = 'success';
						}
					}

				}
			}
		}
	}


} else {
	$msg = 'Something wrong happened! Please try again!';
}

$output['message'] = $msg;
echo json_encode($output);