<?php
//require database
include("db.php");
//$username = 'admin';
//$pass = 'admin';
$username = $_POST['username'];
$pass = $_POST['password'];

$passEncrypt = encyptdata($pass);
//$passEncrypt=openssl_encrypt($pass,"AES-128-ECB",$secret_key);
//$username = 'admin';
//$password = 'm0C0m';
//check login

$checkUserDataList = [];
$newcheckUserDataArr = [];

//$sql = mysqli_query($con, "SELECT u.UserID, u.User,u.Email, u.isEmailConfirmed,g.GroupID,g.Groups
//FROM user u LEFT JOIN group_has_user ghu ON ghu.user_UserID = u.UserID LEFT JOIN groups g ON g.GroupID = ghu.group_GroupID
//WHERE u.User = '" . $username . "' OR u.Email = '" . $username . "' AND u.Passwd = '" . $passEncrypt . "' AND u.facebook_id = null");

$sql = mysqli_query($con,"SELECT UserID, User, Email, isEmailConfirmed, GroupID, Groups FROM check_login 
							WHERE (User = '" . $username . "' OR Email = '" . $username . "') AND Pass = '" . $passEncrypt . "' AND facebook_id IS NULL");

$DataList = [];
$UserListData = [];
$newArr = [];
$msg = " Username or Password Invalid";
while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
//		echo $key . ' ' . $val;
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}
// print_pre($UserListData);

if (!empty($newArr)) {
	foreach ($newArr as $key => $val) {
		$UserListData[$key] = $val;
		if ($val['isEmailConfirmed'] == 1) {
			$msg = "Login finish";
		} else {
			$msg = "Please verify email";
		}
	}
}
echo json_encode(array('msg' => $msg, 'data' => $UserListData));


// if($conn->query($mysql_qry) === TRUE) {
// 	$output['message'] = "Course Successfully Added";
// //	echo "Course Successfully Added";
// }else{
// 	$output['message'] = "Sorry, Unable to add courseName. Try Again";
// //	echo "Sorry, Unable to add courseName. Try Again";
// }
// echo json_encode($output);
$con->close();
?>