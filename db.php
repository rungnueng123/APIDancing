<?php
$host = "127.0.0.1";
$user_name = "adminpan_admindb";
$user_password = "m0C0mdb@1nf0n3t";
$db_name = "DancingDB";
$host_url = "https://danceschool.matchbox-station.com/MDancingPHP";
$secret_key = "42NTz5PQepR(Nd9";
date_default_timezone_set('Asia/Bangkok');
//$host = "127.0.0.1";
//$user_name = "root";
//$user_password = "1234";
//$db_name = "dancing";

$con = mysqli_connect($host, $user_name, $user_password, $db_name);
mysqli_query($con,"SET CHARACTER SET UTF8");

// Check connection
if ($con->connect_error) {
	die("Connection failed: " . $con->connect_error);
}
//echo "Connected successfully";

function print_pre($object)
{
	?>
	<pre><?php print_r($object); ?></pre><?php
}

function encyptdata($string){
	$secretKey="42NTz5PQepR(Nd9";
	$encrypted_string=openssl_encrypt($string,"AES-128-ECB",$secretKey);
	return $encrypted_string;
}

function decyptdata($string){
	$secretKey="42NTz5PQepR(Nd9";
	$encrypted_string=openssl_decrypt($string,"AES-128-ECB",$secretKey);
	return $encrypted_string;
}

?>