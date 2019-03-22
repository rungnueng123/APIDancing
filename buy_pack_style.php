<?php

include("db.php");

//$stylePackID = 4;
//$userID = 27;
$stylePackID = $_POST['stylePackID'];
$userID = $_POST['userID'];
$date = date('Y-m-d H:i:s');
$msg = 'Something wrong happened! Please try again!';

if (!empty($stylePackID) && !empty($userID)) {
	$selectCourseStyleSql = mysqli_query($con, "SELECT id as stylepack_id, style_id as coursestyle_courseStyleID, coin, times FROM stylepack sp WHERE id = '" . $stylePackID . "'");
	$DataList = [];
	$newArr = [];
	$CourseStyleListData = [];
	while ($arr = mysqli_fetch_array($selectCourseStyleSql, MYSQLI_ASSOC)) {
		foreach ($arr as $key => $val) {
//		echo $key . ' ' . $val;
			$DataList[$key] = $val;
		}
		array_push($newArr, $DataList);
	}
	foreach ($newArr as $val) {
		$CourseStyleListData = $val;
	}
//	print_pre($CourseStyleListData);
	if (!empty($CourseStyleListData)) {
		// get coin amount user
		$userRealCoinAmt = '';
		$selectUserCoinSql = mysqli_query($con,"SELECT CoinAmt FROM user WHERE UserID = '" . $userID . "'");
		while ($arr = mysqli_fetch_array($selectUserCoinSql, MYSQLI_ASSOC)) {
			foreach ($arr as $key => $val) {
//		echo $key . ' ' . $val;
				$userRealCoinAmt = $val;
			}
		}
		$userCoinAmt = $userRealCoinAmt - $CourseStyleListData['coin'];
		//update coin user
		$updateCoinUserSql = "UPDATE user SET CoinAmt = '" . $userCoinAmt . "' WHERE UserID = $userID";

		if($con->query($updateCoinUserSql)) {
			//check have style pack yet
			$checkHavePackStyleSql = mysqli_query($con, "SELECT id,times FROM user_has_stylepack WHERE user_UserID = '" . $userID . "' AND stylepack_id = '" . $stylePackID . "'");
			$DataList1 = [];
			$newArr1 = [];
			$checkHavePackStyleListData = [];
			while ($arr = mysqli_fetch_array($checkHavePackStyleSql, MYSQLI_ASSOC)) {
				foreach ($arr as $key => $val) {
//		echo $key . ' ' . $val;
					$DataList1[$key] = $val;
				}
				array_push($newArr1, $DataList1);
			}
			foreach ($newArr1 as $val) {
				$checkHavePackStyleListData = $val;
			}
//			print_pre($checkHavePackStyleListData);
			if (empty($checkHavePackStyleListData)) {
				$addUserHasStylePackSql = "INSERT INTO user_has_stylepack (user_UserID, stylepack_id, coursestyle_courseStyleID, times, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy)
                    VALUES ('" . $userID . "','" . $stylePackID . "','" . $CourseStyleListData['coursestyle_courseStyleID'] . "','" . $CourseStyleListData['times'] . "','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "')";
//		echo $addUserHasStylePackSql;
				if ($con->query($addUserHasStylePackSql)) {
					$user_has_stylepack_id = mysqli_insert_id($con);
					$addCoinTxnSql = mysqli_query($con, "INSERT INTO paymentstyletxn (user_UserID, stylepack_id, times, BuyUse, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, user_has_stylepack_id, coin)
                    VALUES ('" . $userID . "','" . $stylePackID . "','" . $CourseStyleListData['times'] . "','B','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $user_has_stylepack_id . "','" . $CourseStyleListData['coin'] . "')");
					$msg = 'success';
				}
			} else {
				$timesUpdate = $checkHavePackStyleListData['times'] + $CourseStyleListData['times'];
				$updateUserHasStylePackSql = "UPDATE user_has_stylepack SET times = '" . $timesUpdate . "' WHERE id = '" . $checkHavePackStyleListData['id'] . "'";
				if ($con->query($updateUserHasStylePackSql)) {
					$addCoinTxnSql = mysqli_query($con, "INSERT INTO paymentstyletxn (user_UserID, stylepack_id, times, BuyUse, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, user_has_stylepack_id, coin)
                    VALUES ('" . $userID . "','" . $stylePackID . "','" . $CourseStyleListData['times'] . "','B','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $checkHavePackStyleListData['id'] . "','" . $CourseStyleListData['coin'] . "')");
					$msg = 'success';
				}
			}
		}
	}
}
$output['message'] = $msg;
echo json_encode($output);