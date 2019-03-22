<?php
include("db.php");

$msg = '';
$passEncrypt = encyptdata($_POST['pass']);
$email = decyptdata($_POST['email']);

if (!empty($_POST['email']) && !empty($_POST['pass'])) {
	$updatePassSql = "UPDATE user SET Passwd = '" . $passEncrypt . "' WHERE email = '" . $email . "' AND facebook_id IS NULL";
	if ($con->query($updatePassSql)) {
		$msg = 'Reset Success';
	}else{
		$msg = 'Something wrong happened! Please try again';
	}
}

echo $msg;

?>