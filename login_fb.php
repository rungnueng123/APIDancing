<?php
//require database
include("db.php");
$FacebookID = $_POST['id'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$birthday = date('Y-m-d', strtotime($_POST['birthday'])) . ' 00:00:00';
$gender = $_POST['gender'];
$sex = 'M';
if ($gender == "female") {
	$sex = "F";
}
$username = $firstName.' '.$lastName;

//$FacebookID = '10213656365670353';
//$firstName = 'Rungnueng';
//$lastName = 'Luangkamchorn';
//$email = 'rungnueng19@hotmail.com';
//$birthday = date('Y-m-d', strtotime($_POST['birthday'])) . ' 00:00:00';
//$gender = 'male';
//$sex = 'M';
//if ($gender == "female") {
//	$sex = "F";
//}
//$username = 'Rungnueng Luangkamchorn';

$DataList = [];
$UserListData = [];
$newArr = [];


$checkUserDataList = [];
$newcheckUserDataArr = [];

$checkUserSql = mysqli_query($con, "SELECT * From user WHERE facebook_id = '" . $FacebookID . "'");
if (!empty($checkUserSql)) {
	while ($arr = mysqli_fetch_array($checkUserSql, MYSQLI_ASSOC)) {
		foreach ($arr as $key => $val) {
			$checkUserDataList[$key] = $val;
		}
		array_push($newcheckUserDataArr, $checkUserDataList);
	}
}


if (!empty($newcheckUserDataArr)) {
//	$msg = 'a';
	$sql = mysqli_query($con, "SELECT u.UserID, u.User,u.Email, u.isEmailConfirmed,g.GroupID,g.Groups 
									FROM user u LEFT JOIN group_has_user ghu ON ghu.user_UserID = u.UserID LEFT JOIN groups g ON g.GroupID = ghu.group_GroupID 
									WHERE u.facebook_id = '" . $FacebookID . "'");
	while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
		foreach ($arr as $key => $val) {
			$DataList[$key] = $val;
		}
		array_push($newArr, $DataList);
	}
} else {
//	$msg = 'b';
	$userInsertSql = "INSERT INTO user (User,facebook_id,Passwd,Email,Phone,Sex,BirthDate,Active,CoinAmt,isEmailConfirmed,token)
					VALUES ('" . $username . "','" . $FacebookID . "',null,'" . $email . "',null,'" . $sex . "','" . $birthday . "', 1, 0, 1, null)";


	if ($con->query($userInsertSql)) {
		$userID = mysqli_insert_id($con);
		$studentDataList = [];
		$studentGroupSql = mysqli_query($con, "SELECT GroupID FROM groups WHERE groups = 'student'");
		while ($arr = mysqli_fetch_array($studentGroupSql, MYSQLI_ASSOC)) {
			foreach ($arr as $key => $val) {
				$studentDataList[$key] = $val;
			}
		}

		$insertUserHasGroupSql = "INSERT INTO group_has_user (group_GroupID, user_UserID)
                    VALUES ('" . $studentDataList['GroupID'] . "','" . $userID . "')";
//$msg = $studentDataList['GroupID'].'/'.$userID.'/'.$studentDataList;
		if ($con->query($insertUserHasGroupSql)) {
			$sql = mysqli_query($con, "SELECT u.UserID, u.User,u.Email, u.isEmailConfirmed,g.GroupID,g.Groups 
									FROM user u LEFT JOIN group_has_user ghu ON ghu.user_UserID = u.UserID LEFT JOIN groups g ON g.GroupID = ghu.group_GroupID 
									WHERE u.facebook_id = '" . $FacebookID . "'");

			while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
				foreach ($arr as $key => $val) {
					$DataList[$key] = $val;
				}
				array_push($newArr, $DataList);
			}
		}
	}
}
if (!empty($newArr)) {
	foreach ($newArr as $key => $val) {
		$UserListData[$key] = $val;
		$msg = "Login finish";
	}
}

//$msg =  $firstName.' '.$lastName.''.$email.''.$birthday.''.$sex;

echo json_encode(array('msg' => $msg, 'data' => $UserListData));


//exit();
?>