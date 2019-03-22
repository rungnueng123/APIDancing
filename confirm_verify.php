<?php
include("db.php");
$email = $_GET['email'];
$token = $_GET['token'];
$output = [];
$confirm_msg = "";

if(!isset($email) || !isset($token)){
	$confirm_msg = "Something wrong happened! Please try again!";
}else{
	$sql = mysqli_query($con, "SELECT UserID FROM user WHERE Email = '$email' AND token = '$token' AND isEmailConfirmed = 0");
	if(!empty($sql)){
		$confirmedSql = "UPDATE user SET isEmailConfirmed = 1, token IS NULL WHERE email = '$email'";
		if($con->query($confirmedSql)){
			$confirm_msg = 'verify success';
		}else{
			$confirm_msg = 'verify fail';
		}
	}
}
//echo json_encode($output);
?>
<script>
    var confirm_msg = <?php echo $confirm_msg?>
    window.load("https://danceschool.matchbox-station.com/register.php?confirm_msg="+confirm_msg);
</script>
