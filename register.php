<?php
include("db.php");

use PHPMailer\PHPMailer\PHPMailer;

//$username = 'b';
//$password = 'b';
//$email = 'rungnueng19@gmail.com';
//$phone = '0909454547';
//$sex = 'M';
//$birth = '1995-01-19 00:00:00';


//$splitBirth = explode("-", $_POST['birth']);
//$day = $splitBirth[0];
//$month = $splitBirth[1];
//$year = $splitBirth[2];
$username = $_POST['username'];
$pass = $_POST['password'];
$passEncrypt = encyptdata($pass);
$email = $_POST['email'];
$phone = $_POST['phone'];
if ($_POST['sex'] == 'MALE') {
	$sex = 'M';
} else {
	$sex = 'F';
}
//$birth = $year . '-' . $month . '-' . $day . ' 00:00:00';
$msg = "aaa";
$token = "";
$statusToken = "used";

$checkUserDataList = [];
$newcheckUserDataArr = [];
$checkEmailDataList = [];
$newcheckEmailDataArr = [];

if (!empty($username)) {
	$checkUserSql = mysqli_query($con, "SELECT UserID
FROM user WHERE User = '" . $username . "' AND facebook_id = null");

	if (!empty($checkUserSql)) {
		while ($arr = mysqli_fetch_array($checkUserSql, MYSQLI_ASSOC)) {
			foreach ($arr as $key => $val) {
				$checkUserDataList[$key] = $val;
			}
			array_push($newcheckUserDataArr, $checkUserDataList);
		}
	}


	if (!empty($newcheckUserDataArr)) {
		$msg = "Username already exists";
	} else {
		$checkEmailSql = mysqli_query($con, "SELECT UserID FROM User WHERE Email = '" . $email . "' AND facebook_id = null");

		if (!empty($checkEmailSql)) {
			while ($arr = mysqli_fetch_array($checkEmailSql, MYSQLI_ASSOC)) {
				foreach ($arr as $key => $val) {
					$checkEmailDataList[$key] = $val;
				}
				array_push($newcheckEmailDataArr, $checkEmailDataList);
			}
		}

		if (!empty($newcheckEmailDataArr)) {
			$msg = "Email already exists";
		} else {
			/*
			$newToken = checkToken();
			if($newToken == "used"){
				$newToken = checkToken();
			}else{

				$con->query("INSERT INTO User (User, Passwd, Email, Phone, Sex, BirthDate, Active, CoinAmt, isEmailConfirmed, token, )");
			}
			*/
			$newToken = checkToken($con);
			$userInsertSql = "INSERT INTO user (User, Passwd, Email, Phone, Sex, Active, CoinAmt, isEmailConfirmed, token)
                    VALUES ('" . $username . "','" . $passEncrypt . "','" . $email . "','" . $phone . "','" . $sex . "',1,0,0,'" . $newToken . "')";
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

				if ($con->query($insertUserHasGroupSql)) {
					include_once "PHPMailer/PHPMailer.php";
					$mail = new PHPMailer();
					try {
						$mail->setFrom('info@beatsbox.com');
					} catch (\PHPMailer\PHPMailer\Exception $e) {
					}
					$mail->addAddress($email, $username);
					$mail->Subject = "Please verify email!";
					$mail->isHTML(true);
					$mail->Body = "
		            Please click on the link below:<br><br>
		            <a href='$host_url/register.php?email=$email&token=$newToken'>Click Here</a>
		            ";

					try {
						if ($mail->send()) {
							$msg = "You have been registered! Please verify your email!";
						} else {
							$msg = "Something wrong happened! Please try again!";
						}
					} catch (\PHPMailer\PHPMailer\Exception $e) {
					}
				}

			} else {
				$msg = "Something wrong happened! Please try again!";
			}
		}
	}
	$output['message'] = $msg;
	echo json_encode($output);
}
if (!empty($_GET['token'])) {
	$email = $_GET['email'];
	$token = $_GET['token'];
	$output = [];

	if (!isset($email) || !isset($token)) {
		$output['message'] = "Something wrong happened! Please try again!";
	} else {
		$sql = mysqli_query($con, "SELECT UserID FROM user WHERE Email = '$email' AND token = '$token' AND isEmailConfirmed = 0");
		if (!empty($sql)) {
			$confirmedSql = "UPDATE user SET isEmailConfirmed = 1, token = '' WHERE email = '$email'";
			if ($con->query($confirmedSql)) {
				$output['message'] = 'verify success';
			} else {
				$output['message'] = 'verify fail';
			}
		} else {
			$output['message'] = 'verify fail';
		}
	}

	echo '<script>window.close();</script>';
	exit();
}

function checkToken($con)
{
	$token = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM!$/()*';
	$token = str_shuffle($token);
	$token = substr($token, 0, 10);
	$statusToken = "";
	$checkTokenSql = mysqli_query($con, "SELECT UserID FROM USER WHERE token = '" . $token . "'");

	if ($checkTokenSql->num_rows > 0) {
		checkToken($con);
	} else {
		$statusToken = $token;
	}
	return $statusToken;

}

?>
