<?php
include("db.php");

use PHPMailer\PHPMailer\PHPMailer;

$email = $_POST['email'];
$output = [];
$msg = "";
$checkUserDataList = [];
$newcheckUserDataArr = [];
$username = "";
$password = "";


if(isset($email)){
	$checkUserSql = mysqli_query($con,"SELECT User, Passwd FROM user WHERE Email = '" . $email . "' AND facebook_id IS null");
	while ($arr = mysqli_fetch_array($checkUserSql, MYSQLI_ASSOC)) {
		foreach ($arr as $key => $val) {
			$checkUserDataList[$key] = $val;
		}
	}
//	print_pre($checkUserDataList);
	$username = $checkUserDataList['User'];
	$password = decyptdata($checkUserDataList['Passwd']);
	$email_encrypt = encyptdata($email);
//	echo $username.' '.$password;
	if(!empty($username) && !empty($password)){
		include_once "PHPMailer/PHPMailer.php";
		$mail = new PHPMailer();
		try {
			$mail->setFrom('info@beatsbox.com');
		} catch (\PHPMailer\PHPMailer\Exception $e) {
		}
		$mail->addAddress($email, $username);
		$mail->Subject = "Forgot Your Password";
		$mail->isHTML(true);
		$mail->Body = "
            Click on the link below for reset password:<br><br>
            <a href='$host_url/reset_pass.php?email=$email_encrypt'>Click Here</a>
            ";

		try {
			if ($mail->send()) {
				$msg = "You have been requested! Please check your email!";
			} else {
				$msg = "Something wrong happened! Please try again!";
			}
		} catch (\PHPMailer\PHPMailer\Exception $e) {
		}
	}else{
//		$msg = "Something wrong happened! Please try again!";
		$msg = "Your Email Address is not registered";
	}
}else{
	$msg = "Email is required!";
}
//echo $msg;
$output['message'] = $msg;
echo json_encode($output);